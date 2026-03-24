<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\FormType;
use App\Models\OrganizationSchoolYear;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\ProjectDocumentSignature;
use App\Models\ProjectProposalData;
use App\Models\ProjectProposalGuest;
use App\Models\ProjectProposalObjective;
use App\Models\ProjectProposalPartner;
use App\Models\ProjectProposalPlanOfAction;
use App\Models\ProjectProposalRole;
use App\Models\ProjectProposalSuccessIndicator;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Notifications\ReregActionNotification;
use App\Support\Audit;

class ProjectProposalController extends BaseProjectDocumentController
{


    public function create(Request $request, Project $project)
    {
        $document = $this->getDocument($project, 'project_proposal');

        $proposal = $document?->proposalData;

        $user = auth()->user();

        //dd($proposal->end_time);

        $orgId = session('active_org_id');
        $syId  = session('encode_sy_id');

        $orgRole = $this->getOrgRole($user->id, $orgId, $syId);

        $roles = $this->resolveRoleFlags($orgRole);

        $isProjectHead = $this->isProjectHead($project, $user->id);

        $currentSignature = $this->getCurrentSignature($document, $user->id);

        $isReadOnly = $this->computeReadOnly($document, $isProjectHead);
        $isAdmin = false;

        return view('org.projects.documents.project-proposal.create', [
            'project'          => $project,
            'document'         => $document,
            'proposal'         => $proposal,
            'currentSignature' => $currentSignature,
            'isReadOnly'       => $isReadOnly,
            'isProjectHead'    => $isProjectHead,
            'isAdmin'          => $isAdmin,
            ...$roles
        ]);
    }

    public function submit(Project $project)
    {
        $formType = FormType::where('code', 'project_proposal')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'draft') {
            return back()->with('error', 'This proposal is already submitted.');
        }

        $activeSy = SchoolYear::activeYear();

        if (!$activeSy) {
            return back()->with('error', 'No active school year is currently set.');
        }

        $isRegistered = OrganizationSchoolYear::where('organization_id', $project->organization_id)
            ->where('school_year_id', $activeSy->id)
            ->exists();

        if (!$isRegistered) {
            return back()->with('error', 'This organization is not registered for the active school year.');
        }

        DB::transaction(function () use ($document) {

            $document->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'remarks' => null,
                'returned_by' => null,
                'returned_at' => null,
            ]);

            $document->signatures()->delete();

            $this->createWorkflow($document);

        });

        $document->load('signatures','formType','project');

        $this->notifyNextApprover($document);

        Audit::log(
            'document.submitted',
            'Project Proposal submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'project_proposal'
                ]
            ]
        );

        return back()->with('success', 'Project Proposal submitted successfully.');
    }

    public function store(Request $request, Project $project)
    {
        $formType = FormType::query()
            ->where('code', 'project_proposal')
            ->firstOrFail();


        $data = $this->validateRequest($request);


        if (
            empty($data['on_campus_venue']) &&
            empty($data['off_campus_venue'])
        ) {
            return back()
                ->withErrors([
                    'venue' => 'At least one venue must be specified.'
                ])
                ->withInput();
        }

        [$data, $clean] = $this->normalizeData($data);

        DB::transaction(function () use ($project, $formType, $data, $clean) {

            $document = $this->saveDocument($project, $formType);

            if ($document->isLocked()) {
                abort(403, 'This proposal is already approved by the moderator and is locked.');
            }

            $proposal = $this->saveMainProposal($document->id, $data);

            $this->saveFundSources($proposal, $data['fund_sources'] ?? []);

            $this->saveMultiEntries($document->id, $clean, $data);

            $this->resetApprovalsAfterEdit($document);
        });

        $this->ensureOffCampusDocument($project);

        $action = $request->input('action');

        if ($action === 'submit') {
            return $this->submit($project);
        }

        return back()->with('success', 'Project Proposal saved as draft.');     
    }

    protected function ensureOffCampusDocument(Project $project): void
    {
        $proposal = $project->proposalDocument?->proposalData;

        if (!$proposal) {
            return;
        }

        $formType = FormType::where('code', 'OFF_CAMPUS_APPLICATION')->first();

        if (!$formType) {
            return;
        }

        $document = ProjectDocument::firstOrCreate(
            [
                'project_id' => $project->id,
                'form_type_id' => $formType->id
            ],
            [
                'status' => 'draft'
            ]
        );

        if (!empty($proposal->off_campus_venue)) {
            $document->update(['is_active' => true]);
        } else {
            $document->update(['is_active' => false]);
        }


    }

    private function resetApprovalsAfterEdit(ProjectDocument $document): void
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

    private function validateRequest(Request $request): array
    {
        $startTime = $request->start_time . ':00';
        return $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
           

            'start_time' => [
                'nullable',
                Rule::anyOf([
                    'date_format:H:i',
                    'date_format:H:i:s',
                ]),
            ],


            'end_time' => [
                'nullable',
                Rule::anyOf([
                    'date_format:H:i',
                    'date_format:H:i:s',
                ]),
            ],

            'on_campus_venue' => ['nullable', 'string', 'max:255'],
            'off_campus_venue' => ['nullable', 'string', 'max:255'],

            'engagement_type' => ['required', 'in:organizer,partner,participant'],
            'main_organizer' => ['nullable', 'string', 'max:255'],

            'project_nature' => ['nullable', 'array'],
            'project_nature.*' => ['string', 'max:100'],

            'sdg' => ['nullable', 'array'],
            'sdg.*' => ['string', 'max:255'],

            'area_focus' => ['nullable', 'array'],
            'area_focus.*' => ['string', 'max:100'],

            'description' => ['required', 'string'],
            'org_link' => ['required', 'string'],
            'org_cluster' => ['nullable', 'string', 'max:255'],

            'total_budget' => ['nullable', 'numeric', 'min:0'],
            'fund_sources' => ['nullable', 'array'],
            'fund_sources.*' => ['nullable', 'numeric', 'min:0'],

            'audience_type' => ['required', 'string'],
            'xu_subtypes' => ['nullable', 'array'],
            'xu_subtypes.*' => ['string'],
            'audience_details' => ['nullable', 'string'],

            'expected_xu_participants' => ['nullable', 'integer'],
            'expected_non_xu_participants' => ['nullable', 'integer'],
            'has_guest_speakers' => ['nullable', 'boolean'],

            'objectives' => ['nullable', 'array'],
            'objectives.*' => ['nullable', 'string'],

            'success_indicators' => ['nullable', 'array'],
            'success_indicators.*' => ['nullable', 'string'],

            'partners' => ['nullable', 'array'],
            'partners.*' => ['nullable', 'string'],
            'roles.*' => ['nullable', 'string'],

            'guests' => ['nullable', 'array'],
            'guests.*.full_name' => ['nullable', 'string'],
            'guests.*.affiliation' => ['nullable', 'string'],
            'guests.*.designation' => ['nullable', 'string'],

            'plan_of_actions' => ['nullable', 'array'],
            'plan_of_actions.*.date' => ['nullable', 'date'],
            'plan_of_actions.*.time' => ['nullable'],
            'plan_of_actions.*.activity' => ['nullable', 'string'],
            'plan_of_actions.*.venue' => ['nullable', 'string'],
            'roles' => ['nullable', 'array'],
        ]);

        
    }
    
    private function saveDocument(Project $project, $formType)
    {
        return ProjectDocument::updateOrCreate(
            [
                'project_id' => $project->id,
                'form_type_id' => $formType->id,
            ],
            [
                'created_by_user_id' => auth()->id(),
                'status' => 'draft',
            ]
        );
    }

    private function saveMainProposal(int $documentId, array $data)
    {
        //dd($data['end_time']);
        $proposal = ProjectProposalData::updateOrCreate(
            ['project_document_id' => $documentId],
            [
                'start_date' => $data['start_date'],
                'venue_name' => "",
                'end_date' => $data['end_date'],
                'start_time' => $data['start_time'] ?? null,
                'end_time' => $data['end_time'] ?? null,
                'on_campus_venue' => $data['on_campus_venue'] ?? null,
                'off_campus_venue' => $data['off_campus_venue'] ?? null,
                'engagement_type' => $data['engagement_type'],
                'main_organizer' => $data['main_organizer'] ?? null,
                'project_nature' => implode(', ', $data['project_nature'] ?? []),
                'sdg' => implode(', ', $data['sdg'] ?? []),
                'area_focus' => implode(', ', $data['area_focus'] ?? []),
                'description' => $data['description'],
                'org_link' => $data['org_link'],

                'xu_subtypes' => isset($data['xu_subtypes'])
                    ? implode(', ', $data['xu_subtypes'])
                    : null,


                'org_cluster' => $data['org_cluster'] ?? null,
                'total_budget' => $data['total_budget'] ?? null,
                'audience_type' => $data['audience_type'],
                'audience_details' => $data['audience_details'] ?? null,
                'expected_xu_participants' => $data['expected_xu_participants'] ?? null,
                'expected_non_xu_participants' => $data['expected_non_xu_participants'] ?? null,
                'has_guest_speakers' => (bool) ($data['has_guest_speakers'] ?? false),
            ]
        );

        $project = Project::findOrFail($proposal->projectDocument->project_id);

        $this->syncProjectDetailsFromProposal($project, $data);

        return $proposal;
    }

    private function syncProjectDetailsFromProposal(Project $project, array $data): void
    {


        $onCampus = trim($data['on_campus_venue'] ?? '');
        $offCampus = trim($data['off_campus_venue'] ?? '');

        $venueParts = [];                  
        if (!empty($onCampus)) {
            $venueParts[] = $onCampus;
        }

        if (!empty($offCampus)) {
            $venueParts[] = $offCampus;
        }

        $venueString = !empty($venueParts)
            ? implode(', ', $venueParts)
            : null;

                    
        $venueType = !empty($offCampus)
            ? 'off_campus' 
            : (!empty($onCampus) ? 'on_campus' : null);

        //dd($venueType);
 
        $project->update([

            'implementation_start_date' => $data['start_date'] ?? null,
            'implementation_end_date'   => $data['end_date'] ?? null,

            'implementation_start_time' => $data['start_time'] ?? null,
            'implementation_end_time'   => $data['end_time'] ?? null,

            'implementation_venue' => $venueString,
            'implementation_venue_type' => $venueType,


            'description' => $data['description'] ?? $project->description,
        ]);
    }

    private function saveFundSources(ProjectProposalData $proposal, array $fundSources)
    {
        $proposal->fundSources()->delete();

        foreach ($fundSources as $source => $amount) {

            if ($amount === null || $amount === '') {
                continue;
            }

            $proposal->fundSources()->create([
                'source_name' => $source,
                'amount' => $amount,
            ]);
        }
    }

    private function saveMultiEntries(int $documentId, array $clean, array $data): void
    {
  
        ProjectProposalObjective::where('project_document_id', $documentId)->delete();

        if (!empty($clean['objectives'])) {
            ProjectProposalObjective::insert(
                array_map(fn($txt) => [
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
                array_map(fn($txt) => [
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
                array_map(fn($name) => [
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
                array_map(fn($g) => [
                    'project_document_id' => $documentId,
                    'full_name' => trim((string)($g['full_name'] ?? '')),
                    'affiliation' => !empty($g['affiliation']) ? trim($g['affiliation']) : null,
                    'designation' => !empty($g['designation']) ? trim($g['designation']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['guests'])
            );
        }

   
        ProjectProposalPlanOfAction::where('project_document_id', $documentId)->delete();

        if (!empty($clean['plan'])) {
            ProjectProposalPlanOfAction::insert(
                array_map(fn($row) => [
                    'project_document_id' => $documentId,
                    'date' => !empty($row['date']) ? $row['date'] : $data['start_date'],
                    'time' => !empty($row['time']) ? substr($row['time'], 0, 5) : null,
                    'activity' => trim((string)($row['activity'] ?? '')),
                    'venue' => !empty($row['venue']) ? trim($row['venue']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['plan'])
            );
        }

     
        ProjectProposalRole::where('project_document_id', $documentId)->delete();

        if (!empty($clean['roles'])) {
            ProjectProposalRole::insert(
                array_map(fn($role) => [
                    'project_document_id' => $documentId,
                    'role_name' => trim($role),
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $clean['roles'])
            );
        }
    }

    private function normalizeData(array $data): array
    {
        $cleanStrings = function (?array $arr): array {
            $arr = is_array($arr) ? $arr : [];
            $arr = array_map(fn($v) => is_string($v) ? trim($v) : $v, $arr);
            return array_values(array_filter($arr, fn($v) => is_string($v) ? $v !== '' : !empty($v)));
        };

        $clean = [];

        
        $clean['objectives'] = $cleanStrings($data['objectives'] ?? []);
        $clean['indicators'] = $cleanStrings($data['success_indicators'] ?? []);
        $clean['partners'] = $cleanStrings($data['partners'] ?? []);
        $clean['roles'] = $cleanStrings($data['roles'] ?? []);

     
        $clean['guests'] = array_values(array_filter(
            $data['guests'] ?? [],
            function ($g) {
                return isset($g['full_name']) && trim((string)$g['full_name']) !== '';
            }
        ));

  
        $clean['plan'] = array_values(array_filter(
            $data['plan_of_actions'] ?? [],
            function ($row) {
                return isset($row['activity']) && trim((string)$row['activity']) !== '';
            }
        ));

        return [$data, $clean];
    }

    public function show(Project $project)
    {
        $formType = FormType::where('code', 'project_proposal')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->with([
                'proposalData',
                'signatures',
                'proposalData.objectives',
                'proposalData.indicators',
                'proposalData.partners',
                'proposalData.roles',
                'proposalData.guests',
                'proposalData.planOfActions',
            ])
            ->firstOrFail();

        return view('org.projects.documents.project-proposal.show', [
            'project' => $project,
            'document' => $document,
            'proposal' => $document->proposalData,
        ]);
    }

    public function approve(Project $project)
    {
        $document = $this->getDocument($project,'project_proposal');

        if ($document->status !== 'submitted') {
            return back()->with('error','This document is not awaiting approval.');
        }

        $this->handleApproval($project,$document);

        return back()->with('success','Project proposal approved successfully.');
    }

    public function return(Request $request, Project $project)
    {
        $request->validate([
            'remarks' => ['required','string']
        ]);

        $document = $this->getDocument($project,'project_proposal');

        if ($document->status !== 'submitted') {
            return back()->with('error','This document cannot be returned.');
        }

        $this->handleReturn(
            $project,
            $document,
            $request->remarks
        );

        return back()->with('success','Project proposal returned for revision.');
    }


}