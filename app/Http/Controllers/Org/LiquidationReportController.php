<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Models\FormType;
use App\Models\LiquidationReportData;
use App\Models\LiquidationReportItem;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiquidationReportController extends BaseProjectDocumentController
{

    public function create(Project $project)
    {
        $document = $this->getDocument($project, 'LIQUIDATION_REPORT');

        $report = $document?->liquidationData;

        $user = auth()->user();

        $isProjectHead = $this->isProjectHead($project, $user->id);

        $currentSignature = $this->getCurrentSignature($document, $user->id);

        $isReadOnly = $this->computeReadOnly($document, $isProjectHead);

        $isAdmin = 'false';
        $formCode = 'LIQUIDATION_REPORT';
        return view('org.projects.documents.liquidation-report.create', [
            'project' => $project,
            'document' => $document,
            'report' => $report,
            'currentSignature' => $currentSignature,
            'isReadOnly' => $isReadOnly,
            'isProjectHead' => $isProjectHead,
            'isAdmin' => $isAdmin,
            'formCode' => $formCode,
        ]);
    }

    public function store(Request $request, Project $project)
    {
        $formType = FormType::query()
            ->where('code', 'LIQUIDATION_REPORT')
            ->firstOrFail();

        $request->merge([
            'finance_amount' => $request->finance_amount ? str_replace(',', '', $request->finance_amount) : null,
            'fund_raising_amount' => $request->fund_raising_amount ? str_replace(',', '', $request->fund_raising_amount) : null,
            'sacdev_amount' => $request->sacdev_amount ? str_replace(',', '', $request->sacdev_amount) : null,
            'pta_amount' => $request->pta_amount ? str_replace(',', '', $request->pta_amount) : null,

            'total_expenses' => $request->total_expenses ? str_replace(',', '', $request->total_expenses) : null,
            'total_advanced' => $request->total_advanced ? str_replace(',', '', $request->total_advanced) : null,
            'balance' => $request->balance ? str_replace(',', '', $request->balance) : null,
            'cluster_a_return' => $request->cluster_a_return ? str_replace(',', '', $request->cluster_a_return) : null,
            'cluster_b_return' => $request->cluster_b_return ? str_replace(',', '', $request->cluster_b_return) : null,
        ]);

        if ($request->has('items')) {
            $items = $request->items;

            foreach ($items as $i => $row) {
                if (isset($row['amount'])) {
                    $items[$i]['amount'] = str_replace(',', '', $row['amount']);
                }
            }

            $request->merge(['items' => $items]);
        }

        $validator = \Validator::make($request->all(), $this->rules());


        $user = auth()->user();

        $isProjectHead = $this->isProjectHead($project, $user->id);
        $isDraftee = $this->isDraftee($project, $user->id);

        $action = $request->input('action', 'draft');

        if ($action === 'submit' && $isDraftee) {
            return back()->withErrors([
                'action' => 'Only project head can submit this document.'
            ])->withInput();
        }

        if ($validator->fails()) {

            $this->getOrCreateDocument($project, 'LIQUIDATION_REPORT');

            return back()
                ->with('warning', 'Form has errors. Saved as draft instead.')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        [$data, $clean] = $this->normalizeData($data);

        $document = $this->getOrCreateDocument($project, 'LIQUIDATION_REPORT');

        if ($response = $this->checkConflict($request, $document)) {
            return $response;
        }

        DB::transaction(function () use ($project, $formType, $data, $clean) {

            $document = $this->saveDocument($project, $formType);

            if ($document->isLocked() && !$document->edit_mode) {
                abort(403, 'This liquidation report is already finalized.');
            }

            $report = $this->saveMainReport($document->id, $data);

            $this->saveItems($document->id, $clean);

            if (!$document->edit_mode) {
                $this->resetApprovalsAfterEdit($document);
            }

        });

        $action = request()->input('action');

        if ($action === 'submit') {

            $a = floatval($request->cluster_a_return);
            $b = floatval($request->cluster_b_return);
            $balance = floatval($request->balance);

            if (abs(($a + $b) - $balance) > 0.01) {
                return back()
                    ->withErrors(['returns' => 'Cluster A + Cluster B must equal Balance.'])
                    ->withInput();
            }
        }

        if ($document && $document->edit_mode) {
            $action = 'submit';
        }

        if ($action === 'submit') {
            return $this->submit($project);
        }

        return back()->with('success','Liquidation report saved as draft.');
    }

    public function submit(Project $project)
    {
        $formType = FormType::where('code', 'LIQUIDATION_REPORT')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'draft' && !$document->edit_mode) {
            return back()->with('error', 'This form cannot be submitted.');
        }

        $activeSy = \App\Models\SchoolYear::activeYear();

        if (!$activeSy) {
            return back()->with('error', 'No active school year is currently set.');
        }

        $isRegistered = \App\Models\OrganizationSchoolYear::where('organization_id', $project->organization_id)
            ->where('school_year_id', $activeSy->id)
            ->exists();

        if (!$isRegistered) {

            $document->update([
                'status' => 'draft'
            ]);

            return back()->with('warning', 
                'Organization is not registered for this school year. Saved as draft instead.'
            );
        }

        $this->handleRequestSubmit($project, $document);

        $document->load('signatures','formType','project');

        $this->notifyNextApprover($document);

        Audit::log(
            'document.submitted',
            'Liquidation report submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'liquidation_report'
                ]
            ]
        );

        return back()->with('success','Liquidation report submitted successfully.');
    }

    private function rules(): array
    {
        return [

            'contact_number' => ['nullable','string'],

            'finance_amount' => ['nullable','numeric'],
            'fund_raising_amount' => ['nullable','numeric'],
            'sacdev_amount' => ['nullable','numeric'],
            'pta_amount' => ['nullable','numeric'],

            'fundraising_types' => ['nullable','array'],
            'fundraising_types.*' => ['in:solicitation,counterpart,ticket_selling,selling'],
            
            'total_expenses' => ['nullable','numeric'],
            'total_advanced' => ['nullable','numeric'],
            'balance' => ['nullable','numeric'],
            'cluster_a_return' => ['nullable','numeric'],
            'cluster_b_return' => ['nullable','numeric'],

            'items' => ['nullable','array'],

            'items.*.section_label' => ['nullable','string'],
            'items.*.date' => ['nullable','date'],
            'items.*.particulars' => ['nullable','string'],
            'items.*.amount' => ['nullable','numeric'],

            'items.*.source_document_type' => ['nullable','in:OR,SR,CI,SI,AR,PV'],
            'items.*.source_document_description' => ['nullable','string'],
            'items.*.or_number' => ['nullable','string'],
        ];
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

    private function saveMainReport(int $documentId, array $data)
    {
        return LiquidationReportData::updateOrCreate(
            ['project_document_id' => $documentId],
            [
                'contact_number' => $data['contact_number'] ?? null,

                'finance_amount' => $data['finance_amount'] ?? 0,
                'fund_raising_amount' => $data['fund_raising_amount'] ?? 0,
                'sacdev_amount' => $data['sacdev_amount'] ?? 0,
                'pta_amount' => $data['pta_amount'] ?? 0,

                'fundraising_types' => $data['fundraising_types'] ?? [],

                'total_expenses' => $data['total_expenses'] ?? 0,
                'total_advanced' => $data['total_advanced'] ?? 0,
                'balance' => $data['balance'] ?? 0,

                'cluster_a_return' => $data['cluster_a_return'] ?? 0,
                'cluster_b_return' => $data['cluster_b_return'] ?? 0,
            ]
        );
    }

    private function saveItems(int $documentId, array $clean)
    {
        LiquidationReportItem::where('project_document_id', $documentId)->delete();

        if (!empty($clean['items'])) {

            LiquidationReportItem::insert(

                array_map(fn($row) => [

                    'project_document_id' => $documentId,

                    'section_label' => $row['section_label'] ?? null,

                    'date' => $row['date'] ?? null,

                    'particulars' => trim((string)($row['particulars'] ?? '')),

                    'amount' => $row['amount'] ?? 0,

                    'source_document_type' => $row['source_document_type'] ?? null,

                    'source_document_description' => $row['source_document_description'] ?? null,

                    'or_number' => $row['or_number'] ?? null,

                    'created_at' => now(),
                    'updated_at' => now(),

                ], $clean['items'])

            );
        }
    }

    private function normalizeData(array $data): array
    {
        $clean = [];

        $clean['items'] = array_values(array_filter(
            $data['items'] ?? [],
            function ($row) {
                return isset($row['particulars']) && trim($row['particulars']) !== '';
            }
        ));

        return [$data, $clean];
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


    public function approve(Project $project)
    {
        $document = $this->getDocument($project,'LIQUIDATION_REPORT');

        if ($document->status !== 'submitted') {
            return back()->with('error','This document is not awaiting approval.');
        }

        $this->handleApproval($project,$document);

        return back()->with('success','Liquidation report approved successfully.');
    }

    public function return(Request $request, Project $project)
    {
        $request->validate([
            'remarks' => ['required','string']
        ]);

        $document = $this->getDocument($project,'LIQUIDATION_REPORT');

        if ($document->status !== 'submitted') {
            return back()->with('error','This document cannot be returned.');
        }

        $this->handleReturn(
            $project,
            $document,
            $request->remarks
        );

        return back()->with('success','Liquidation report returned for revision.');
    }



}