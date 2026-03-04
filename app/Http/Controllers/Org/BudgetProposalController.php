<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Http\Controllers\Org\ProjectProposalController;
use App\Models\BudgetItem;
use App\Models\BudgetProposalData;
use App\Models\FormType;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\ProjectDocumentSignature;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetProposalController extends BaseProjectDocumentController
{


    public function create(Project $project)
    {
        $document = $this->getDocument($project, 'budget_proposal');

        $budget = $document?->budgetProposal()->with('items')->first();

        $user = auth()->user();

        $orgId = session('active_org_id');
        $syId  = session('encode_sy_id');

        $orgRole = $this->getOrgRole($user->id, $orgId, $syId);

        $roles = $this->resolveRoleFlags($orgRole);

        $isProjectHead = $this->isProjectHead($project, $user->id);

        $currentSignature = $this->getCurrentSignature($document, $user->id);

        $isReadOnly = $this->computeReadOnly($document, $isProjectHead);

        $document = ProjectDocument::with('signatures.user')
            ->where('project_id', $project->id)
            ->where('form_type_id', 2)
            ->first();

        return view('org.projects.documents.budget-proposal.create', [
            'project'          => $project,
            'document'         => $document,
            'budget'           => $budget,
            'currentSignature' => $currentSignature,
            'isReadOnly'       => $isReadOnly,
            'isProjectHead'    => $isProjectHead,
            ...$roles
        ]);
    }

    public function store(Request $request, Project $project)
    {
        $document = $this->getOrCreateDocument($project, 'budget_proposal');

        $action = $request->input('action', 'draft');
        //dd($request);

        if ($action === 'submit') {

            $request->validate([
                'counterpart_amount_per_pax' => ['required','numeric','min:0'],
                'counterpart_pax'            => ['required','numeric','min:0'],
                'pta_amount'                 => ['nullable','numeric','min:0'],
                'raised_funds'               => ['nullable','numeric','min:0'],
            ]);

        }


        if ($document->isLocked()) {
            abort(403, 'This document is already approved and cannot be edited.');
        }

        DB::transaction(function () use ($request, $document) {


            $budget = $this->storeBudgetMeta($request, $document);

            $this->storeBudgetItems($request, $budget);

            $this->recalculateBudgetTotals($budget);

    

            $this->resetApprovalsAfterEdit($document);

        });



        if ($action === 'submit') {
            return $this->submit($project);
        }

        return redirect()
            ->route('org.projects.documents.hub', $project)
            ->with('success', 'Budget proposal saved as draft.');
    }

    public function submit(Project $project)
    {
        $formType = FormType::where('code', 'budget_proposal')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'draft') {
            return back()->with('error', 'This budget proposal is already submitted.');
        }

        
        $activeSy = SchoolYear::activeYear();

        if (!$activeSy) {
            return back()->with('error', 'No active school year is currently set.');
        }

        DB::transaction(function () use ($project, $document) {

            $document->update([
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            $document->signatures()->delete();


            $projectHead = ProjectAssignment::where('project_id', $project->id)
                ->where('assignment_role', 'project_head')
                ->whereNull('archived_at')
                ->firstOrFail();

            $this->createSignature(
                $document->id,
                $projectHead->user_id,
                'project_head',
                'signed'
            );

    

            $treasurer = OrgMembership::where('organization_id', $project->organization_id)
                ->where('school_year_id', $project->school_year_id)
                ->where('role', 'treasurer')
                ->whereNull('archived_at')
                ->firstOrFail();

            $this->createSignature(
                $document->id,
                $treasurer->user_id,
                'treasurer'
            );


            $president = OrgMembership::where('organization_id', $project->organization_id)
                ->where('school_year_id', $project->school_year_id)
                ->where('role', 'president')
                ->whereNull('archived_at')
                ->firstOrFail();

            $this->createSignature(
                $document->id,
                $president->user_id,
                'president'
            );


            $moderator = OrgMembership::where('organization_id', $project->organization_id)
                ->where('school_year_id', $project->school_year_id)
                ->where('role', 'moderator')
                ->whereNull('archived_at')
                ->firstOrFail();

            $this->createSignature(
                $document->id,
                $moderator->user_id,
                'moderator'
            );

    

            $admin = User::where('system_role', 'sacdev_admin')->firstOrFail();

            $this->createSignature(
                $document->id,
                $admin->id,
                'sacdev_admin'
            );
        });

        return back()->with('success', 'Budget proposal submitted successfully.');
    }

    protected function getBudgetDocument(Project $project): ProjectDocument
    {
        $formType = FormType::where('code', 'BUDGET_PROPOSAL')->firstOrFail();

        return ProjectDocument::firstOrCreate([
            'project_id' => $project->id,
            'form_type_id' => $formType->id
        ]);
    }

    protected function storeBudgetMeta(Request $request, ProjectDocument $document)
    {
        return BudgetProposalData::updateOrCreate(
            [
                'project_document_id' => $document->id
            ],
            [
                'counterpart_amount_per_pax' => $request->counterpart_amount_per_pax,
                'counterpart_pax' => $request->counterpart_pax,
                'counterpart_total' => $request->counterpart_total,
                'pta_amount' => $request->pta_amount,
                'raised_funds' => $request->raised_funds,
                'amount_charged_to_org' => $request->amount_charged_to_org
            ]
        );
    }


    protected function recalculateBudgetTotals(BudgetProposalData $budget)
    {
        $sectionTotals = [];
        $grandTotal = 0;

        foreach ($budget->items as $item) {

            $section = $item->section;

            if (!isset($sectionTotals[$section])) {
                $sectionTotals[$section] = 0;
            }

            $sectionTotals[$section] += $item->amount;

            $grandTotal += $item->amount;
        }

        $budget->update([
            'section_totals' => $sectionTotals,
            'total_expenses' => $grandTotal
        ]);
    }


    protected function storeBudgetItems(Request $request, BudgetProposalData $budget)
    {
        
        $budget->items()->delete();

        $sections = [
            'cash_advance',
            'fund_transfer',
            'xucmpc',
            'bookstore',
            'central_purchasing',
            'counterpart'
        ];

        $sectionTotals = [];
        $grandTotal = 0;

        foreach ($sections as $section) {

            $qtys  = $request->input("$section.qty", []);
            $units = $request->input("$section.unit", []);
            $parts = $request->input("$section.particulars", []);
            $prices = $request->input("$section.price", []);

            foreach ($parts as $i => $particular) {

                if (!$particular) {
                    continue;
                }

                $qty   = (float) ($qtys[$i] ?? 0);
                $price = (float) ($prices[$i] ?? 0);

            
                $amount = $qty * $price;

                BudgetItem::create([
                    'budget_proposal_data_id' => $budget->id,
                    'section'       => $section,
                    'qty'           => $qty,
                    'unit'          => $units[$i] ?? null,
                    'particulars'   => $particular,
                    'price_per_unit'=> $price,
                    'amount'        => $amount,
                ]);

                $sectionTotals[$section] = ($sectionTotals[$section] ?? 0) + $amount;
                $grandTotal += $amount;
            }
        }

        $budget->update([
            'section_totals' => $sectionTotals,
            'total_expenses' => $grandTotal
        ]);
    }


    protected function markSubmitted(ProjectDocument $document)
    {
        $document->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $document->signatures()->delete();
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

        $document->update([
            'status' => 'draft',
            'submitted_at' => null,
        ]);
    }


    private function createSignature(
        int $documentId,
        int $userId,
        string $role,
        string $status = 'pending'
    ): void {

        ProjectDocumentSignature::create([
            'project_document_id' => $documentId,
            'user_id' => $userId,
            'role' => $role,
            'status' => $status,
            'signed_at' => $status === 'signed' ? now() : null,
        ]);

    }









}

