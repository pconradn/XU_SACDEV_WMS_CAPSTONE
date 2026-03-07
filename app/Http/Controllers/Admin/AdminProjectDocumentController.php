<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\FormType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProjectDocumentController extends Controller
{

    public function hub(Project $project)
    {
        $documents = ProjectDocument::query()
            ->with(['formType', 'signatures.user'])
            ->where('project_id', $project->id)
            ->get()
            ->keyBy(fn($d) => $d->formType->code);

        return view('admin.projects.documents.hub', [
            'project'   => $project,
            'documents' => $documents,
        ]);
    }


    public function open(Project $project, $formType)
    {
        $document = ProjectDocument::query()
            ->with(['signatures.user','formType'])
            ->where('project_id', $project->id)
            ->whereHas('formType', fn($q) => $q->where('code', $formType))
            ->firstOrFail();

        $viewMap = [
            'PROJECT_PROPOSAL'       => 'org.projects.documents.project-proposal.create',
            'BUDGET_PROPOSAL'        => 'org.projects.documents.budget-proposal.create',
            'OFF_CAMPUS_APPLICATION' => 'org.projects.documents.off-campus.create',
            'SOLICITATION_APPLICATION' => 'org.projects.documents.solicitation.create',
        ];

        $view = $viewMap[$formType] ?? abort(404);

        $proposal = null;
        $budget = null;
        $activity = null;
        $participants = collect();
        $solicitation = null;

        if ($formType === 'PROJECT_PROPOSAL') {

            $proposal = $document->proposalData;

        }

        if ($formType === 'BUDGET_PROPOSAL') {

            $budget = $document->budgetProposal()->with('items')->first();

        }

        if ($formType === 'OFF_CAMPUS_APPLICATION') {

            $activity = \App\Models\OffCampusActivityData::with('participants')
                ->where('project_document_id', $document->id)
                ->first();

            if ($activity) {
                $participants = $activity->participants;
            }

        }

        if ($formType === 'SOLICITATION_APPLICATION') {

            $solicitation = \App\Models\SolicitationApplicationData::where(
                'project_document_id',
                $document->id
            )->first();

        }

        $userId = auth()->id();

        $currentSignature = $document->signatures
            ->where('user_id', $userId)
            ->first();

        return view($view, [
            'project' => $project,
            'document' => $document,
            'proposal' => $proposal,
            'budget' => $budget,
            'activity' => $activity,
            'participants' => $participants,
            'data' => $solicitation,
            'isReadOnly' => true,
            'isProjectHead' => false,
            'currentSignature' => $currentSignature
        ]);
    }

    public function approve(Project $project, $formCode)
    {
        
        $allowedForms = [
            'PROJECT_PROPOSAL',
            'BUDGET_PROPOSAL',
            'OFF_CAMPUS_APPLICATION',
            'SOLICITATION_APPLICATION'
        ];

        //dd($formCode);
        if (!in_array($formCode, $allowedForms)) {
            abort(404);
        }

        $formType = FormType::where('code', $formCode)->firstOrFail();

        $document = ProjectDocument::with('signatures')
            ->where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'submitted') {
            return back()->with('error', 'This document is not awaiting approval.');
        }

        $userId = auth()->id();

        $userSignature = $document->signatures
            ->where('user_id', $userId)
            ->first();

        if (!$userSignature) {
            return back()->with('error', 'You are not part of the approval workflow.');
        }

        if ($userSignature->status === 'signed') {
            return back()->with('error', 'You have already approved this document.');
        }

        $currentPending = $document->signatures
            ->where('status', 'pending')
            ->sortBy('id')
            ->first();

        if (!$currentPending) {
            return back()->with('error', 'No pending approvals remain.');
        }

        if ($currentPending->user_id !== $userId) {
            return back()->with('error', 'It is not your turn to approve yet.');
        }

        DB::transaction(function () use ($document, $currentPending) {

            $currentPending->update([
                'status' => 'signed',
                'signed_at' => now(),
            ]);

            $remaining = $document->signatures()
                ->where('status', 'pending')
                ->exists();

            if (!$remaining) {
                $document->update([
                    'status' => 'approved_by_sacdev',
                ]);
            }

        });

        return back()->with('success', 'Document approved successfully.');
    }

    public function return(Request $request, Project $project, $formCode)
    {
        $request->validate([
            'remarks' => ['required', 'string'],
        ]);

        $allowedForms = [
            'PROJECT_PROPOSAL',
            'BUDGET_PROPOSAL',
            'OFF_CAMPUS_APPLICATION',
            'SOLICITATION_APPLICATION'
        ];

        if (!in_array($formCode, $allowedForms)) {
            abort(404);
        }

        $formType = FormType::where('code', $formCode)->firstOrFail();

        $document = ProjectDocument::with('signatures')
            ->where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'submitted') {
            return back()->with('error', 'This document cannot be returned.');
        }

        $userId = auth()->id();

        $userSignature = $document->signatures
            ->where('user_id', $userId)
            ->first();

        if (!$userSignature) {
            return back()->with('error', 'You are not part of the approval workflow.');
        }

        $currentPending = $document->signatures
            ->where('status', 'pending')
            ->sortBy('id')
            ->first();

        if (!$currentPending) {
            return back()->with('error', 'No pending approvals remain.');
        }

        if ($currentPending->user_id !== $userId) {
            return back()->with('error', 'It is not your turn to return this document yet.');
        }

        DB::transaction(function () use ($document, $request) {

            foreach ($document->signatures as $signature) {
                $signature->update([
                    'status' => 'pending',
                    'signed_at' => null,
                ]);
            }

            $document->update([
                'status' => 'draft',
                'remarks' => $request->remarks,
                'returned_by' => auth()->id(),
                'returned_at' => now(),
            ]);

        });

        return back()->with('success', 'Document returned for revision.');
    }


}