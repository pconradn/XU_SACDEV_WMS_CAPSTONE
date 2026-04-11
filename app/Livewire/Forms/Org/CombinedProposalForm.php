<?php

namespace App\Livewire\Forms\Org;

use App\Http\Controllers\Org\BudgetProposalController;
use App\Http\Controllers\Org\ProjectProposalController;
use App\Models\BudgetItem;
use App\Models\BudgetProposalData;
use App\Models\FormType;
use App\Models\OrganizationSchoolYear;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\ProjectProposalData;
use App\Models\ProjectProposalGuest;
use App\Models\ProjectProposalObjective;
use App\Models\ProjectProposalPartner;
use App\Models\ProjectProposalPlanOfAction;
use App\Models\ProjectProposalRole;
use App\Models\ProjectProposalSuccessIndicator;
use App\Models\SchoolYear;
use App\Support\Audit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CombinedProposalForm extends Component
{
    public Project $project;

    public ?ProjectDocument $proposalDocument = null;
    public ?ProjectDocument $budgetDocument = null;

    public array $proposal = [];
    public array $budget = [];
    public array $budgetItems = [];

    public bool $isProjectHead = false;
    public bool $isReadOnly = false;
    public bool $isAdmin = false;

    public array $roleFlags = [];

    protected array $sectionKeys = [
        'cash_advance',
        'fund_transfer',
        'xucmpc',
        'bookstore',
        'central_purchasing',
    ];

    public function mount(Project $project): void
    {
        $this->project = $project->load([
            'sourceStrategicPlanProject.objectives',
            'sourceStrategicPlanProject.partners',
            'sourceStrategicPlanProject.deliverables',
            'sourceStrategicPlanProject.beneficiaries',
        ]);

        $this->proposal['has_guest_speakers'] = (int) ($this->proposal['has_guest_speakers'] ?? 0);
        $this->proposal['guests'] = $this->proposal['guests'] ?? [];

        $this->proposalDocument = $this->findDocument($project, 'PROJECT_PROPOSAL');
        $this->budgetDocument = $this->findDocument($project, 'BUDGET_PROPOSAL');

        $user = auth()->user();
        $orgId = session('active_org_id');
        $syId = session('encode_sy_id');

        $this->isAdmin = $user?->system_role === 'sacdev_admin';
        $this->isProjectHead = $this->project->assignments()
            ->where('user_id', $user?->id)
            ->exists();

        $orgRole = null;
        if ($user && $orgId && $syId) {
            $membership = \App\Models\OrgMembership::query()
                ->where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->where('user_id', $user->id)
                ->first();

            $orgRole = $membership?->role;
        }

        $this->roleFlags = [
            'isPresident' => $orgRole === 'president',
            'isModerator' => $orgRole === 'moderator',
            'isTreasurer' => $orgRole === 'treasurer',
            'isFinanceOfficer' => $orgRole === 'finance_officer',
            'isOfficer' => $orgRole === 'officer',
        ];

        $status = $this->proposalDocument?->status ?? 'draft';

        $isEditable = $this->isProjectHead && (
            in_array($status, ['draft', 'submitted', 'returned'], true) ||
            ($status === 'approved_by_sacdev' && ($this->proposalDocument?->edit_mode ?? false))
        );

        $this->isReadOnly = !$isEditable;
        $this->proposal['has_guest_speakers'] = $this->proposal['has_guest_speakers'] ?? 0;
        $this->fillProposalState();
        $this->fillBudgetState();
        $this->fund_sources['Counterpart'] = $this->counterpartTotal;

        $this->recomputeFundingTotals();
        $this->recomputeBudgetTotals();
    }

    public function render()
    {
        return view('livewire.forms.org.combined-proposal-form', [
            'project' => $this->project,
            'proposalDocument' => $this->proposalDocument,
            'budgetDocument' => $this->budgetDocument,
            'isProjectHead' => $this->isProjectHead,
            'isReadOnly' => $this->isReadOnly,
            'isAdmin' => $this->isAdmin,
            ...$this->roleFlags,
        ]);
    }

    public function addObjective(): void
    {
        $this->proposal['objectives'][] = '';
    }

    public function removeObjective(int $index): void
    {
        unset($this->proposal['objectives'][$index]);
        $this->proposal['objectives'] = array_values($this->proposal['objectives']);
    }

    public function addSuccessIndicator(): void
    {
        $this->proposal['success_indicators'][] = '';
    }

    public function removeSuccessIndicator(int $index): void
    {
        unset($this->proposal['success_indicators'][$index]);
        $this->proposal['success_indicators'] = array_values($this->proposal['success_indicators']);
    }

    public function addPartner(): void
    {
        $this->proposal['partners'][] = '';
    }

    public function removePartner(int $index): void
    {
        unset($this->proposal['partners'][$index]);
        $this->proposal['partners'] = array_values($this->proposal['partners']);
    }

    public function addRole(): void
    {
        $this->proposal['roles'][] = '';
    }

    public function removeRole(int $index): void
    {
        unset($this->proposal['roles'][$index]);
        $this->proposal['roles'] = array_values($this->proposal['roles']);
    }

    public function addGuest(): void
    {
        if ((int)($this->proposal['has_guest_speakers'] ?? 0) !== 1) {
            return;
        }

        $this->proposal['guests'][] = [
            'full_name' => '',
            'affiliation' => '',
            'designation' => '',
        ];
    }

    public function removeGuest(int $index): void
    {
        unset($this->proposal['guests'][$index]);
        $this->proposal['guests'] = array_values($this->proposal['guests']);
    }

    public function addPlanOfAction(): void
    {
        $this->proposal['plan_of_actions'][] = [
            'date' => '',
            'time' => '',
            'activity' => '',
            'venue' => '',
        ];
    }

    public function removePlanOfAction(int $index): void
    {
        unset($this->proposal['plan_of_actions'][$index]);
        $this->proposal['plan_of_actions'] = array_values($this->proposal['plan_of_actions']);
    }

    public function addBudgetRow(string $section): void
    {
        if (!isset($this->budgetItems[$section])) {
            $this->budgetItems[$section] = [];
        }

        $this->budgetItems[$section][] = [
            'qty' => null,
            'unit' => '',
            'particulars' => '',
            'price_per_unit' => null,
            'amount' => null,
        ];
    }

    public function removeBudgetRow(string $section, int $index): void
    {
        if (!isset($this->budgetItems[$section][$index])) {
            return;
        }

        unset($this->budgetItems[$section][$index]);
        $this->budgetItems[$section] = array_values($this->budgetItems[$section]);
        $this->recomputeBudgetSection($section);
        $this->recomputeBudgetTotals();
    }

    public function updated($name): void
    {
        
        if (str_starts_with($name, 'budget.counterpart_')) {
            $this->fund_sources['Counterpart'] = $this->counterpartTotal;
        }

        $this->recomputeFundingTotals();


        if (str_starts_with($name, 'fund_sources.')) {
            
        }

        
        if (str_starts_with($name, 'budgetItems.')) {
            
            $parts = explode('.', $name);

            if (count($parts) >= 4) {
                $section = $parts[1];
                $rowIndex = (int) $parts[2];

                $this->recomputeBudgetItemAmount($section, $rowIndex);
            }
        }

        
        $this->dispatch('$refresh');
    }

    public function saveDraft(): void
    {
        abort_if($this->isReadOnly, 403);
        $this->validate($this->draftRules());

        $payload = $this->payload();

        $validator = Validator::make($payload, $this->draftRules());

        if (
            !empty($this->proposal['start_date']) &&
            empty($this->proposal['end_date'])
        ) {
            $this->addError('proposal.end_date', 'End date is required if start date is set.');
            return;
        }

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        if ($validator->fails()) {
            $data = $payload;
        } else {
            $data = $validator->validated();
        }

        [$data, $clean] = $this->normalizeData($data);

        DB::transaction(function () use ($data, $clean) {
            $proposalFormType = $this->findFormType('PROJECT_PROPOSAL');
            $budgetFormType = $this->findFormType('BUDGET_PROPOSAL');

            $proposalDocument = $this->saveProposalDocument($proposalFormType);
            $budgetDocument = $this->saveBudgetDocument($budgetFormType);

            if ($proposalDocument->isLocked() && !$proposalDocument->edit_mode) {
                abort(403, 'This proposal is locked and cannot be edited.');
            }

            if ($budgetDocument->isLocked() && !$budgetDocument->edit_mode) {
                abort(403, 'This document is already approved and cannot be edited.');
            }

            $proposal = $this->saveMainProposal($proposalDocument->id, $data);
            $this->saveFundSources($proposal, $data['fund_sources'] ?? []);
            $this->saveProposalMultiEntries($proposalDocument->id, $clean, $data);

            $budget = $this->storeBudgetMeta($budgetDocument, $data);
            $this->storeBudgetItems($budget, $data['budget_items'] ?? []);
            $this->recalculateBudgetTotals($budget);

            if (!$proposalDocument->edit_mode) {
                $this->resetProposalApprovalsAfterEdit($proposalDocument);
            }

            if (!$budgetDocument->edit_mode) {
                $this->resetBudgetApprovalsAfterEdit($budgetDocument);
            }

            $this->ensureOffCampusDocument($this->project);

            $this->proposalDocument = $proposalDocument->fresh(['signatures']);
            $this->budgetDocument = $budgetDocument->fresh(['signatures']);
        });

        $this->dispatch('notify', type: 'success', message: 'Combined proposal saved as draft.');
        session()->flash('success', 'Combined proposal saved as draft.');

        $this->fillProposalState();
        $this->fillBudgetState();
        session()->flash('success', 'Draft saved successfully.');
    }




    protected function rules(): array
    {
        return $this->submitRules();
    }
        

    public function submit(): void
    {
        abort_if($this->isReadOnly, 403);

        $this->validate();

        if (
            empty($this->proposal['on_campus_venue']) &&
            empty($this->proposal['off_campus_venue'])
        ) {
            $this->addError('venue', 'At least one venue must be specified.');
            return;
        }

        if (!$this->isMatch) {
            $this->addError('budget', 'Budget mismatch: funding and expenses must match.');
            return;
        }

        $payload = $this->payload();
        [$data, $clean] = $this->normalizeData($payload);

        DB::transaction(function () use ($data, $clean) {

            $proposalFormType = $this->findFormType('PROJECT_PROPOSAL');
            $budgetFormType = $this->findFormType('BUDGET_PROPOSAL');

            $proposalDocument = $this->saveProposalDocument($proposalFormType);
            $budgetDocument = $this->saveBudgetDocument($budgetFormType);

            if ($proposalDocument->isLocked() && !$proposalDocument->edit_mode) {
                abort(403);
            }

            if ($budgetDocument->isLocked() && !$budgetDocument->edit_mode) {
                abort(403);
            }

            $proposal = $this->saveMainProposal($proposalDocument->id, $data);
            $this->saveFundSources($proposal, $data['fund_sources'] ?? []);
            $this->saveProposalMultiEntries($proposalDocument->id, $clean, $data);

            $budget = $this->storeBudgetMeta($budgetDocument, $data);
            $this->storeBudgetItems($budget, $data['budget_items'] ?? []);
            $this->recalculateBudgetTotals($budget);

            if (!$proposalDocument->edit_mode) {
                $this->resetProposalApprovalsAfterEdit($proposalDocument);
            }

            if (!$budgetDocument->edit_mode) {
                $this->resetBudgetApprovalsAfterEdit($budgetDocument);
            }

            $this->ensureOffCampusDocument($this->project);

            $this->submitProposalDocument($proposalDocument);
            $this->submitBudgetDocument($budgetDocument);

            $this->proposalDocument = $proposalDocument->fresh(['signatures']);
            $this->budgetDocument = $budgetDocument->fresh(['signatures']);
        });

        session()->flash('success', 'Proposal and Budget submitted together.');

        $this->fillProposalState();
        $this->fillBudgetState();
    }

    protected function payload(): array
    {
        return array_merge(
            $this->proposal,
            $this->budget,
            [
                'fund_sources' => $this->fund_sources,
                'budget_items' => $this->budgetItems,
            ]
        );
    }

    protected function formatDate($value): ?string
    {
        if (!$value) return null;

        if ($value instanceof \Carbon\Carbon) {
            return $value->format('Y-m-d');
        }

        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    //funding
    public array $fund_sources = [
        'Finance Office' => 0,
        'OSA-SACDEV' => 0,
        'Counterpart' => 0,
        'Solicitation' => 0,
        'Ticket-Selling' => 0,
        'Others' => 0,
    ];
    public function getCounterpartTotalProperty()
    {
        return
            $this->cleanNumber($this->budget['counterpart_amount_per_pax'] ?? 0) *
            (int) ($this->budget['counterpart_pax'] ?? 0);
    }

    public function getRaisedTotalProperty()
    {
        return
            $this->cleanNumber($this->fund_sources['Solicitation'] ?? 0) +
            $this->cleanNumber($this->fund_sources['Ticket-Selling'] ?? 0) +
            $this->cleanNumber($this->fund_sources['Others'] ?? 0);
    }

    public function getTotalBudgetProperty()
    {
        return
            $this->cleanNumber($this->budget['pta_amount'] ?? 0) +
            $this->counterpartTotal +
            $this->cleanNumber($this->fund_sources['Finance Office'] ?? 0) +
            $this->cleanNumber($this->fund_sources['OSA-SACDEV'] ?? 0) +
            $this->cleanNumber($this->fund_sources['Solicitation'] ?? 0) +
            $this->cleanNumber($this->fund_sources['Ticket-Selling'] ?? 0) +
            $this->cleanNumber($this->fund_sources['Others'] ?? 0);
    }

    public array $budgetSectionLabels = [
        'fund_transfer'      => 'A. For Fund Transfer / Direct Payment to Supplier',
        'xucmpc'             => 'B. For XUCMPC',
        'bookstore'          => 'C. For Bookstore',
        'central_purchasing' => 'D. For Central Purchasing Unit',
        'cash_advance'       => 'E. For Cash Advance (Finance Office)',
    ];

    public function updatedProposalHasGuestSpeakers($value): void
    {
        $value = (int) $value;

        $this->proposal['has_guest_speakers'] = $value;

        if ($value === 0) {
            $this->proposal['guests'] = [];
        }
    }


    public function getSectionTotal($section)
    {
        $total = 0;

        foreach ($this->budgetItems[$section] ?? [] as $row) {
            $qty = $this->cleanNumber($row['qty'] ?? 0);
            $price = $this->cleanNumber($row['price_per_unit'] ?? 0);
            $total += $qty * $price;
        }

        return $total;
    }

    public function getGrandTotalProperty()
    {
        $total = 0;

        foreach ($this->budgetSectionLabels as $code => $label) {
            $total += $this->getSectionTotal($code);
        }

        return $total;
    }

    public function getIsMatchProperty()
    {
        return round($this->grandTotal, 2) === round($this->totalBudget, 2);
    }




    protected function fillProposalState(): void
    {
        $proposal = $this->proposalDocument?->proposalData?->load('fundSources');

        $this->proposal = [
            'start_date' => $this->formatDate($proposal?->start_date) 
                ?? $this->formatDate($this->project->implementation_start_date) 
                ?? '',

            'end_date' => $this->formatDate($proposal?->end_date) 
                ?? $this->formatDate($this->project->implementation_end_date) 
                ?? '',
            'start_time' => $proposal?->start_time ? substr((string) $proposal->start_time, 0, 5) : '',
            'end_time' => $proposal?->end_time ? substr((string) $proposal->end_time, 0, 5) : '',
            'on_campus_venue' => $proposal?->on_campus_venue ?? '',
            'off_campus_venue' => $proposal?->off_campus_venue ?? '',
            'engagement_type' => $proposal?->engagement_type ?? '',
            'main_organizer' => $proposal?->main_organizer ?? '',
            'project_nature' => $this->explodeCsv($proposal?->project_nature),
            'project_nature_other' => $proposal?->project_nature_other ?? '',
            'sdg' => $this->explodeCsv($proposal?->sdg),
            'area_focus' => $this->explodeCsv($proposal?->area_focus),
            'description' => $proposal?->description ?? $this->project->description ?? '',
            'org_link' => $proposal?->org_link ?? '',
            'org_cluster' => $proposal?->org_cluster ?? '',
            'total_budget' => $proposal?->total_budget ?? null,

            'has_guest_speakers' => (int) ($proposal?->has_guest_speakers ?? 0),

            'audience_type' => $proposal?->audience_type ?? '',
            'xu_subtypes' => $this->explodeCsv($proposal?->xu_subtypes),
            'audience_details' => $proposal?->audience_details ?? '',
            'expected_xu_participants' => $proposal?->expected_xu_participants ?? null,
            'expected_non_xu_participants' => $proposal?->expected_non_xu_participants ?? null,
            
            'objectives' => $this->proposalDocument?->objectives?->pluck('objective')->values()->toArray()
                ?? $proposal?->objectives?->pluck('objective')->values()->toArray()
                ?? [''],
            'success_indicators' => $this->proposalDocument?->indicators?->pluck('indicator')->values()->toArray()
                ?? $proposal?->indicators?->pluck('indicator')->values()->toArray()
                ?? [''],
            'partners' => $this->proposalDocument?->partners?->pluck('name')->values()->toArray()
                ?? $proposal?->partners?->pluck('name')->values()->toArray()
                ?? [''],
            'roles' => $this->proposalDocument?->roles?->pluck('role_name')->values()->toArray()
                ?? $proposal?->roles?->pluck('role_name')->values()->toArray()
                ?? [''],
            'guests' => $this->proposalDocument?->guests?->map(fn ($g) => [
                    'full_name' => $g->full_name,
                    'affiliation' => $g->affiliation,
                    'designation' => $g->designation,
                ])->values()->toArray()
                ?? $proposal?->guests?->map(fn ($g) => [
                    'full_name' => $g->full_name,
                    'affiliation' => $g->affiliation,
                    'designation' => $g->designation,
                ])->values()->toArray()
                ?? [['full_name' => '', 'affiliation' => '', 'designation' => '']],
            'plan_of_actions' => $this->proposalDocument?->planOfActions?->map(fn ($p) => [
                    'date' => $p->date?->format('Y-m-d'),
                    'time' => $p->time
                        ? \Carbon\Carbon::parse($p->time)->format('H:i')
                        : '',
                    'activity' => $p->activity,
                    'venue' => $p->venue,
                ])->values()->toArray()
                ?? $proposal?->planOfActions?->map(fn ($p) => [
                    'date' => $p->date?->format('Y-m-d'),
                    'time' => $p->time
                        ? \Carbon\Carbon::parse($p->time)->format('H:i')
                        : '',
                    'activity' => $p->activity,
                    'venue' => $p->venue,
                ])->values()->toArray()
                ?? [['date' => '', 'time' => '', 'activity' => '', 'venue' => '']],
        ];

        if (empty($this->proposal['objectives'])) {
            $this->proposal['objectives'] = [''];
        }

        if (empty($this->proposal['success_indicators'])) {
            $this->proposal['success_indicators'] = [''];
        }

        if (empty($this->proposal['partners'])) {
            $this->proposal['partners'] = [''];
        }

        if (empty($this->proposal['roles'])) {
            $this->proposal['roles'] = [''];
        }

        if (empty($this->proposal['guests'])) {
            $this->proposal['guests'] = [['full_name' => '', 'affiliation' => '', 'designation' => '']];
        }

        if (empty($this->proposal['plan_of_actions'])) {
            $this->proposal['plan_of_actions'] = [['date' => '', 'time' => '', 'activity' => '', 'venue' => '']];
        }
    }

    protected function fillBudgetState(): void
    {
        $budget = $this->budgetDocument?->budgetProposal?->load('items');

        $this->budget = [
            'counterpart_amount_per_pax' => $budget?->counterpart_amount_per_pax ?? null,
            'counterpart_pax' => $budget?->counterpart_pax ?? null,
            'counterpart_total' => $budget?->counterpart_total ?? null,
            'pta_amount' => $budget?->pta_amount ?? null,
            'raised_funds' => $budget?->raised_funds ?? null,
            'amount_charged_to_org' => $budget?->amount_charged_to_org ?? null,
            'section_totals' => is_array($budget?->section_totals) ? $budget->section_totals : [],
            'total_expenses' => $budget?->total_expenses ?? 0,
        ];

        $this->budgetItems = [];

        foreach ($this->sectionKeys as $section) {
            $rows = $budget?->items?->where('section', $section)->values()->map(function ($item) {
                return [
                    'qty' => $item->qty,
                    'unit' => $item->unit,
                    'particulars' => $item->particulars,
                    'price_per_unit' => $item->price_per_unit,
                    'amount' => $item->amount,
                ];
            })->toArray() ?? [];

            $this->budgetItems[$section] = !empty($rows)
                ? $rows
                : [[
                    'qty' => null,
                    'unit' => '',
                    'particulars' => '',
                    'price_per_unit' => null,
                    'amount' => null,
                ]];
        }

        $this->fund_sources = [
            'Finance Office' => 0,
            'OSA-SACDEV' => 0,
            'Counterpart' => $this->counterpartTotal,
            'Solicitation' => 0,
            'Ticket-Selling' => 0,
            'Others' => 0,
        ];

        $proposal = $this->proposalDocument?->proposalData?->load('fundSources');

        if ($proposal && $proposal->fundSources) {
            foreach ($proposal->fundSources as $fs) {
                if (array_key_exists($fs->source_name, $this->fund_sources)) {
                    $this->fund_sources[$fs->source_name] = $fs->amount;
                }
            }
        }

        $this->recomputeFundingTotals();
        $this->recomputeBudgetTotals();
    }

    protected function findFormType(string $code): FormType
    {
        return FormType::query()
            ->whereRaw('UPPER(code) = ?', [strtoupper($code)])
            ->firstOrFail();
    }

    protected function findDocument(Project $project, string $code): ?ProjectDocument
    {
        $formType = FormType::query()
            ->whereRaw('UPPER(code) = ?', [strtoupper($code)])
            ->first();

        if (!$formType) {
            return null;
        }

        return ProjectDocument::query()
            ->with([
                'signatures.user',
                'proposalData.fundSources',
                'proposalData.objectives',
                'proposalData.indicators',
                'proposalData.partners',
                'proposalData.roles',
                'proposalData.guests',
                'proposalData.planOfActions',
                'budgetProposal.items',
            ])
            ->where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->first();
    }

    protected function saveProposalDocument(FormType $formType): ProjectDocument
    {
        return ProjectDocument::updateOrCreate(
            [
                'project_id' => $this->project->id,
                'form_type_id' => $formType->id,
            ],
            [
                'created_by_user_id' => auth()->id(),
                'status' => 'draft',
            ]
        );
    }

    protected function saveBudgetDocument(FormType $formType): ProjectDocument
    {
        return ProjectDocument::updateOrCreate(
            [
                'project_id' => $this->project->id,
                'form_type_id' => $formType->id,
            ],
            [
                'created_by_user_id' => auth()->id(),
                'status' => 'draft',
            ]
        );
    }

    protected function saveMainProposal(int $documentId, array $data): ProjectProposalData
    {
        $proposal = ProjectProposalData::updateOrCreate(
            ['project_document_id' => $documentId],
            [
                'start_date' => $this->nullIfEmpty($data['start_date'] ?? null),
                'venue_name' => '',
                'end_date' => $this->nullIfEmpty($data['end_date'] ?? null),
                'start_time' => $this->nullIfEmpty($data['start_time'] ?? null),
                'end_time' => $this->nullIfEmpty($data['end_time'] ?? null),
                'on_campus_venue' => $this->nullIfEmpty($data['on_campus_venue'] ?? null),
                'off_campus_venue' => $this->nullIfEmpty($data['off_campus_venue'] ?? null),
                'engagement_type' => $this->nullIfEmpty($data['engagement_type'] ?? null),
                'main_organizer' => $this->nullIfEmpty($data['main_organizer'] ?? null),
                'project_nature' => !empty($data['project_nature']) ? implode(', ', $data['project_nature']) : null,
                'project_nature_other' => $this->nullIfEmpty($data['project_nature_other'] ?? null),
                'sdg' => !empty($data['sdg']) ? implode(', ', $data['sdg']) : null,
                'area_focus' => !empty($data['area_focus']) ? implode(', ', $data['area_focus']) : null,
                'description' => $this->nullIfEmpty($data['description'] ?? null),
                'org_link' => $this->nullIfEmpty($data['org_link'] ?? null),
                'xu_subtypes' => !empty($data['xu_subtypes']) ? implode(', ', $data['xu_subtypes']) : null,
                'org_cluster' => $this->nullIfEmpty($data['org_cluster'] ?? null),
                'total_budget' => $this->totalBudget,
                'audience_type' => $this->nullIfEmpty($data['audience_type'] ?? null),
                'audience_details' => $this->nullIfEmpty($data['audience_details'] ?? null),
                'expected_xu_participants' => $this->nullIfEmpty($data['expected_xu_participants'] ?? null),
                'expected_non_xu_participants' => $this->nullIfEmpty($data['expected_non_xu_participants'] ?? null),
                'has_guest_speakers' => (bool) ($data['has_guest_speakers'] ?? false),
            ]
        );

        $this->syncProjectDetailsFromProposal($this->project->fresh(), $data);

        return $proposal;
    }

    protected function syncProjectDetailsFromProposal(Project $project, array $data): void
    {
        $onCampus = trim((string) ($data['on_campus_venue'] ?? ''));
        $offCampus = trim((string) ($data['off_campus_venue'] ?? ''));

        $venueParts = [];

        if ($onCampus !== '') {
            $venueParts[] = $onCampus;
        }

        if ($offCampus !== '') {
            $venueParts[] = $offCampus;
        }

        $venueString = !empty($venueParts) ? implode(', ', $venueParts) : null;

        $venueType = $offCampus !== ''
            ? 'off_campus'
            : ($onCampus !== '' ? 'on_campus' : null);

        $project->update([
            'implementation_start_date' => $this->nullIfEmpty($data['start_date'] ?? null),
            'implementation_end_date' => $this->nullIfEmpty($data['end_date'] ?? null),
            'implementation_start_time' => $this->nullIfEmpty($data['start_time'] ?? null),
            'implementation_end_time' => $this->nullIfEmpty($data['end_time'] ?? null),
            'implementation_venue' => $venueString,
            'implementation_venue_type' => $venueType,
            'description' => $this->nullIfEmpty($data['description'] ?? null) ?? $project->description,
        ]);
    }

    protected function saveFundSources(ProjectProposalData $proposal, array $fundSources): void
    {
        $proposal->fundSources()->delete();

        foreach ($fundSources as $source => $amount) {
            if ($amount === null || $amount === '') {
                continue;
            }

            $proposal->fundSources()->create([
                'source_name' => $source,
                'amount' => $this->cleanNumber($amount),
            ]);
        }
    }


    protected function sanitizeTime($value): ?string
    {
        if (!$value) return null;

        try {
            return \Carbon\Carbon::parse($value)->format('H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function saveProposalMultiEntries(int $documentId, array $clean, array $data): void
    {
        ProjectProposalObjective::where('project_document_id', $documentId)->delete();

        if (!empty($clean['objectives'])) {
            ProjectProposalObjective::insert(
                array_map(fn ($txt) => [
                    'project_document_id' => $documentId,
                    'objective' => trim($txt),
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['objectives'])
            );
        }

        ProjectProposalSuccessIndicator::where('project_document_id', $documentId)->delete();

        if (!empty($clean['indicators'])) {
            ProjectProposalSuccessIndicator::insert(
                array_map(fn ($txt) => [
                    'project_document_id' => $documentId,
                    'indicator' => trim($txt),
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['indicators'])
            );
        }

        ProjectProposalPartner::where('project_document_id', $documentId)->delete();

        if (!empty($clean['partners'])) {
            ProjectProposalPartner::insert(
                array_map(fn ($name) => [
                    'project_document_id' => $documentId,
                    'name' => trim($name),
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['partners'])
            );
        }

        ProjectProposalGuest::where('project_document_id', $documentId)->delete();

        if (!empty($clean['guests'])) {
            ProjectProposalGuest::insert(
                array_map(fn ($g) => [
                    'project_document_id' => $documentId,
                    'full_name' => trim((string) ($g['full_name'] ?? '')),
                    'affiliation' => !empty($g['affiliation']) ? trim((string) $g['affiliation']) : null,
                    'designation' => !empty($g['designation']) ? trim((string) $g['designation']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['guests'])
            );
        }

        ProjectProposalPlanOfAction::where('project_document_id', $documentId)->delete();

        if (!empty($clean['plan'])) {

            $rows = array_filter(array_map(function ($row) use ($documentId, $data) {

                $time = $this->sanitizeTime($row['time'] ?? null);

                if (!$time) {
                    return null;
                }

                return [
                    'project_document_id' => $documentId,
                    'date' => !empty($row['date']) ? $row['date'] : ($data['start_date'] ?? null),
                    'time' => $time,
                    'activity' => trim((string) ($row['activity'] ?? '')),
                    'venue' => !empty($row['venue']) ? trim((string) $row['venue']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

            }, $clean['plan']));

            if (!empty($rows)) {
                ProjectProposalPlanOfAction::insert($rows);
            }
        }

        ProjectProposalRole::where('project_document_id', $documentId)->delete();

        if (!empty($clean['roles'])) {
            ProjectProposalRole::insert(
                array_map(fn ($role) => [
                    'project_document_id' => $documentId,
                    'role_name' => trim($role),
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['roles'])
            );
        }
    }

    protected function storeBudgetMeta(ProjectDocument $document, array $data): BudgetProposalData
    {
        $budget = BudgetProposalData::firstOrCreate([
            'project_document_id' => $document->id,
        ]);

        $budget->fill([
            'counterpart_amount_per_pax' => $this->cleanNumber($data['counterpart_amount_per_pax'] ?? null),
            'counterpart_pax' => $this->cleanNumber($data['counterpart_pax'] ?? null),
            'counterpart_total' => $this->counterpartTotal,
            'pta_amount' => $this->cleanNumber($data['pta_amount'] ?? null),
            'raised_funds' => $this->raisedTotal,
            'amount_charged_to_org' => $this->nullIfEmpty($this->cleanNumber($data['amount_charged_to_org'] ?? null)),
        ]);

        $budget->save();

        return $budget;
    }

    protected function storeBudgetItems(BudgetProposalData $budget, array $itemsBySection): void
    {
        BudgetItem::where('budget_proposal_data_id', $budget->id)->delete();

        $rows = [];

        foreach ($this->sectionKeys as $section) {
            foreach ($itemsBySection[$section] ?? [] as $item) {

                $particulars = trim((string) ($item['particulars'] ?? ''));
                $unit = trim((string) ($item['unit'] ?? ''));

                $qty = $this->cleanNumber($item['qty'] ?? 0) ?? 0;
                $pricePerUnit = $this->cleanNumber($item['price_per_unit'] ?? 0) ?? 0;

                if ($particulars === '' || $qty <= 0 || $pricePerUnit <= 0) {
                    continue;
                }

                $amount = round($qty * $pricePerUnit, 2);

                $rows[] = [
                    'budget_proposal_data_id' => $budget->id,
                    'section' => $section,
                    'qty' => $qty,
                    'unit' => $unit !== '' ? $unit : null,
                    'particulars' => $particulars,
                    'price_per_unit' => $pricePerUnit,
                    'amount' => $amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($rows)) {
            BudgetItem::insert($rows);
        }
    }

    protected function recalculateBudgetTotals(BudgetProposalData $budget): void
    {
        $budget->load('items');

        $sectionTotals = [];
        $grandTotal = 0;

        foreach ($budget->items as $item) {
            $section = $item->section;

            if (!isset($sectionTotals[$section])) {
                $sectionTotals[$section] = 0;
            }

            $sectionTotals[$section] += (float) $item->amount;
            $grandTotal += (float) $item->amount;
        }

        $budget->update([
            'section_totals' => $sectionTotals,
            'total_expenses' => $grandTotal,
        ]);
    }

    protected function ensureOffCampusDocument(Project $project): void
    {
        $proposalDocument = $this->findDocument($project, 'PROJECT_PROPOSAL');
        $proposal = $proposalDocument?->proposalData;

        if (!$proposal) {
            return;
        }

        $formType = FormType::query()
            ->whereRaw('UPPER(code) = ?', ['OFF_CAMPUS_APPLICATION'])
            ->first();

        if (!$formType) {
            return;
        }

        $document = ProjectDocument::firstOrCreate(
            [
                'project_id' => $project->id,
                'form_type_id' => $formType->id,
            ],
            [
                'status' => 'draft',
            ]
        );

        $document->update([
            'is_active' => !empty($proposal->off_campus_venue),
        ]);
    }

    protected function resetProposalApprovalsAfterEdit(ProjectDocument $document): void
    {
        if ($document->status === 'draft') {
            return;
        }

        $document->signatures()
            ->whereIn('role', [
                'treasurer',
                'president',
                'moderator',
                'sacdev_admin',
            ])
            ->delete();
    }

    protected function resetBudgetApprovalsAfterEdit(ProjectDocument $document): void
    {
        if ($document->status === 'draft') {
            return;
        }

        $document->signatures()
            ->whereIn('role', [
                'treasurer',
                'president',
                'moderator',
                'sacdev_admin',
            ])
            ->delete();

        $document->update([
            'status' => 'draft',
            'submitted_at' => null,
        ]);
    }

    protected function submitProposalDocument(ProjectDocument $document): void
    {
        if ($document->status !== 'draft' && !$document->edit_mode) {
            abort(422, 'Project Proposal cannot be submitted in its current state.');
        }

        $activeSy = SchoolYear::activeYear();

        if (!$activeSy) {
            abort(422, 'No active school year is currently set.');
        }

        $isRegistered = OrganizationSchoolYear::where('organization_id', $this->project->organization_id)
            ->where('school_year_id', $activeSy->id)
            ->exists();

        if (!$isRegistered) {
            $document->update([
                'status' => 'draft',
            ]);

            abort(422, 'Organization is not registered for this school year. Saved as draft instead.');
        }

        app(\App\Services\DocumentSubmissionService::class)
            ->handleRequestSubmit($this->project, $document);

        $document->load('signatures', 'formType', 'project');

        Audit::log(
            'document.submitted',
            'Project Proposal submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $this->project->organization_id,
                'school_year_id' => $this->project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'project_proposal',
                ],
            ]
        );
    }

    protected function submitBudgetDocument(ProjectDocument $document): void
    {
        if ($document->status !== 'draft' && !$document->edit_mode) {
            abort(422, 'Budget Proposal cannot be submitted in its current state.');
        }

        $activeSy = SchoolYear::activeYear();

        if (!$activeSy) {
            abort(422, 'No active school year is currently set.');
        }

        app(\App\Services\DocumentSubmissionService::class)
            ->handleRequestSubmit($this->project, $document);

        $document->load('signatures', 'formType', 'project');

        Audit::log(
            'document.submitted',
            'Budget Proposal submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $this->project->organization_id,
                'school_year_id' => $this->project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'budget_proposal',
                ],
            ]
        );
    }

    protected function cleanNumber($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) str_replace(',', '', (string) $value);
    }

    protected function recomputeBudgetItemAmount(string $section, int $rowIndex): void
    {
        if (!isset($this->budgetItems[$section][$rowIndex])) {
            return;
        }

        $qty = $this->cleanNumber($this->budgetItems[$section][$rowIndex]['qty'] ?? null) ?? 0;
        $price = $this->cleanNumber($this->budgetItems[$section][$rowIndex]['price_per_unit'] ?? null) ?? 0;

        $this->budgetItems[$section][$rowIndex]['amount'] = round($qty * $price, 2);
    }

    protected function recomputeBudgetSection(string $section): void
    {
        $total = 0;

        foreach ($this->budgetItems[$section] ?? [] as $item) {
            $total += $this->cleanNumber($item['amount'] ?? null) ?? 0;
        }

        $this->budget['section_totals'][$section] = round($total, 2);
    }

    protected function recomputeBudgetTotals(): void
    {
        $grand = 0;

        foreach ($this->sectionKeys as $section) {
            $sectionTotal = 0;

            foreach ($this->budgetItems[$section] ?? [] as $item) {
                $sectionTotal += $this->cleanNumber($item['amount'] ?? null) ?? 0;
            }

            $this->budget['section_totals'][$section] = round($sectionTotal, 2);
            $grand += $sectionTotal;
        }

        $this->budget['total_expenses'] = round($grand, 2);
    }

    protected function recomputeFundingTotals(): void
    {
        $amountPerPax = $this->cleanNumber($this->budget['counterpart_amount_per_pax'] ?? null) ?? 0;
        $pax = $this->cleanNumber($this->budget['counterpart_pax'] ?? null) ?? 0;

        $this->budget['counterpart_total'] = round($amountPerPax * $pax, 2);

        $pta = $this->cleanNumber($this->budget['pta_amount'] ?? null) ?? 0;
        $raised = $this->cleanNumber($this->budget['raised_funds'] ?? null) ?? 0;

        $this->budget['amount_charged_to_org'] = round(
            max(($this->budget['total_expenses'] ?? 0) - ($this->budget['counterpart_total'] ?? 0) - $pta - $raised, 0),
            2
        );
    }

    protected function normalizeData(array $data): array
    {
        $cleanStrings = function (?array $arr): array {
            $arr = is_array($arr) ? $arr : [];
            $arr = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $arr);
            return array_values(array_filter($arr, fn ($v) => is_string($v) ? $v !== '' : !empty($v)));
        };

        $clean = [];

        $clean['objectives'] = $cleanStrings($data['objectives'] ?? []);
        $clean['indicators'] = $cleanStrings($data['success_indicators'] ?? []);
        $clean['partners'] = $cleanStrings($data['partners'] ?? []);
        $clean['roles'] = $cleanStrings($data['roles'] ?? []);

        $clean['guests'] = array_values(array_filter(
            $data['guests'] ?? [],
            fn ($g) => isset($g['full_name']) && trim((string) $g['full_name']) !== ''
        ));

        $clean['plan'] = $data['plan_of_actions'] ?? [];
        $clean['budget_items'] = [];

        foreach ($data['budget_items'] ?? [] as $section => $items) {
            $clean['budget_items'][$section] = $items;
        }

        return [$data, $clean];
    }

    protected function explodeCsv(?string $value): array
    {
        if (!$value) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    protected function nullIfEmpty($value)
    {
        return $value === '' ? null : $value;
    }


    protected function draftRules(): array
    {
        return [
            'proposal.start_date' => ['required','date'],
            'proposal.end_date' => ['required','date','after_or_equal:proposal.start_date'],

            'proposal.start_time' => ['required'],
            'proposal.end_time' => ['required'],

            'proposal.on_campus_venue' => ['nullable','string'],
            'proposal.off_campus_venue' => ['nullable','string'],
            'proposal.engagement_type' => ['nullable','string'],
            'proposal.main_organizer' => ['nullable','string'],

            'proposal.description' => ['nullable','string'],
            'proposal.org_link' => ['nullable','string'],

            'proposal.audience_type' => ['nullable','string'],
            'proposal.audience_details' => ['nullable','string'],

            'proposal.expected_xu_participants' => ['nullable','numeric'],
            'proposal.expected_non_xu_participants' => ['nullable','numeric'],

            'proposal.has_guest_speakers' => ['nullable','boolean'],

            'proposal.objectives' => ['nullable','array'],
            'proposal.objectives.*' => ['nullable','string'],

            'proposal.success_indicators' => ['nullable','array'],
            'proposal.success_indicators.*' => ['nullable','string'],

            'proposal.partners' => ['nullable','array'],
            'proposal.partners.*' => ['nullable','string'],

            'proposal.roles' => ['nullable','array'],
            'proposal.roles.*' => ['nullable','string'],

            'proposal.guests' => ['nullable','array'],
            'proposal.guests.*.full_name' => ['nullable','string'],

            'proposal.plan_of_actions' => ['nullable','array'],
            'proposal.plan_of_actions.*.date' => ['nullable','date'],
            'proposal.plan_of_actions.*.time' => ['nullable'],
            'proposal.plan_of_actions.*.activity' => ['nullable','string'],
            'proposal.plan_of_actions.*.venue' => ['nullable','string'],

            'budget.counterpart_amount_per_pax' => ['nullable','numeric'],
            'budget.counterpart_pax' => ['nullable','numeric'],
            'budget.pta_amount' => ['nullable','numeric'],

            'fund_sources' => ['nullable','array'],
            'fund_sources.*' => ['nullable','numeric'],

            'budgetItems' => ['nullable','array'],
            'budgetItems.*.*.qty' => ['nullable','numeric'],
            'budgetItems.*.*.price_per_unit' => ['nullable','numeric'],
            'budgetItems.*.*.particulars' => ['nullable','string'],
        ];
    }

    protected function submitRules(): array
    {
        return [
            'proposal.start_date' => ['required', 'date'],
            'proposal.end_date' => ['required', 'date', 'after_or_equal:proposal.start_date'],

            'proposal.start_time' => ['nullable', Rule::anyOf(['date_format:H:i', 'date_format:H:i:s'])],
            'proposal.end_time' => ['nullable', Rule::anyOf(['date_format:H:i', 'date_format:H:i:s'])],

            'proposal.on_campus_venue' => ['nullable', 'required_without:proposal.off_campus_venue', 'string', 'max:255'],
            'proposal.off_campus_venue' => ['nullable', 'required_without:proposal.on_campus_venue', 'string', 'max:255'],

            'proposal.engagement_type' => ['required', 'in:organizer,partner,participant'],
            'proposal.main_organizer' => ['nullable', 'string', 'max:255'],

            'proposal.project_nature' => ['required', 'array'],
            'proposal.project_nature.*' => ['string', 'max:100'],
            'proposal.project_nature_other' => ['nullable', 'string', 'max:100'],

            'proposal.sdg' => ['required', 'array'],
            'proposal.sdg.*' => ['string', 'max:255'],

            'proposal.area_focus' => ['nullable', 'array'],
            'proposal.area_focus.*' => ['string', 'max:100'],

            'proposal.description' => ['required', 'string'],
            'proposal.org_link' => ['required', 'string'],
            'proposal.org_cluster' => ['nullable', 'string', 'max:255'],

            'proposal.audience_type' => ['required', 'string'],
            'proposal.xu_subtypes' => ['nullable', 'array'],
            'proposal.xu_subtypes.*' => ['string'],
            'proposal.audience_details' => ['nullable', 'string'],

            'proposal.expected_xu_participants' => ['nullable', 'integer'],
            'proposal.expected_non_xu_participants' => ['nullable', 'integer'],

            'proposal.has_guest_speakers' => ['nullable', 'boolean'],

            'proposal.objectives' => ['required', 'array'],
            'proposal.objectives.*' => ['required', 'string'],

            'proposal.success_indicators' => ['required', 'array'],
            'proposal.success_indicators.*' => ['required', 'string'],

            'proposal.partners' => ['nullable', 'array'],
            'proposal.partners.*' => ['nullable', 'string'],

            'proposal.roles' => ['nullable', 'array'],
            'proposal.roles.*' => ['nullable', 'string'],

            'proposal.guests' => ['nullable', 'array'],
            'proposal.guests.*.full_name' => ['required_if:proposal.has_guest_speakers,1','string'],
            'proposal.guests.*.affiliation' => ['required_if:proposal.has_guest_speakers,1','string'],
            'proposal.guests.*.designation' => ['required_if:proposal.has_guest_speakers,1','string'],

            'proposal.plan_of_actions' => ['required', 'array'],
            'proposal.plan_of_actions.*.date' => ['required', 'date'],
            'proposal.plan_of_actions.*.time' => ['required'],
            'proposal.plan_of_actions.*.activity' => ['required', 'string'],
            'proposal.plan_of_actions.*.venue' => ['required', 'string'],

            'budget.counterpart_amount_per_pax' => ['required', 'numeric', 'min:0'],
            'budget.counterpart_pax' => ['required', 'numeric', 'min:0'],
            'budget.pta_amount' => ['nullable', 'numeric', 'min:0'],

            'fund_sources' => ['nullable', 'array'],
            'fund_sources.*' => ['nullable', 'numeric', 'min:0'],

            'budgetItems' => ['nullable', 'array'],
            'budgetItems.*' => ['nullable', 'array'],
            'budgetItems.*.*.unit' => ['nullable', 'string', 'max:255'],
            'budgetItems.*.*.particulars' => ['required','string'],
            'budgetItems.*.*.qty' => ['required','numeric','min:1'],
            'budgetItems.*.*.price_per_unit' => ['required','numeric','min:0.01'],
            'budgetItems.*.*.amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}