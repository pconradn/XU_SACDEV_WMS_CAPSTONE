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
            'SELLING_APPLICATION'    => 'org.projects.documents.selling.create',
            'REQUEST_TO_PURCHASE'    => 'org.projects.documents.request-to-purchase.create',
        ];

        $view = $viewMap[$formType] ?? abort(404);


        $proposal = null;
        $budget = null;
        $activity = null;
        $participants = collect();
        $solicitation = null;
        $selling = null;
        $purchase = null;

        $items = collect();


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


        if ($formType === 'SELLING_APPLICATION') {

            $selling = \App\Models\SellingApplicationData::where(
                'project_document_id',
                $document->id
            )->first();

            if ($selling) {
                $items = $selling->items;
            }

        }


        if ($formType === 'REQUEST_TO_PURCHASE') {

            $purchase = \App\Models\RequestToPurchaseData::where(
                'project_document_id',
                $document->id
            )->first();

            if ($purchase) {
                $items = $purchase->items;
            }

        }


        if ($formType === 'POSTPONEMENT_NOTICE') {

            $postponement = \App\Models\PostponementNoticeData::where(
                'project_document_id',
                $document->id
            )->first();

        }

        if ($formType === 'CANCELLATION_NOTICE') {

            $cancellation = \App\Models\CancellationNoticeData::where(
                'project_document_id',
                $document->id
            )->first();

        }


        $user = auth()->user();
        $userId = $user->id;

        $isAdmin = $user->system_role === 'sacdev_admin';


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

            'data' => $purchase ?? $solicitation ?? $selling,
            'items' => $items,

            'isReadOnly' => true,
            'isProjectHead' => false,
            'currentSignature' => $currentSignature,

            'isAdmin' => $isAdmin,
            //'cancellation' => $cancellation,
            //'postponement' => $postponement,

        ]);
    }


    public function approve(Request $request, Project $project, $formCode){

        $allowedForms = [
            'PROJECT_PROPOSAL',
            'BUDGET_PROPOSAL',
            'OFF_CAMPUS_APPLICATION',
            'SOLICITATION_APPLICATION',
            'SELLING_APPLICATION',
            'REQUEST_TO_PURCHASE',
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

        /*
        |--------------------------------------------------------------------------
        | Validate extra fields for solicitation approval
        |--------------------------------------------------------------------------
        */

        if ($formCode === 'SOLICITATION_APPLICATION') {

            $request->validate([
                'approved_letter_count' => ['required','integer','min:1'],
                'control_series_start' => ['required','string','max:255'],
                'control_series_end' => ['required','string','max:255'],
            ]);

        }

        DB::transaction(function () use ($request, $document, $currentPending, $formCode) {

            if ($formCode === 'SOLICITATION_APPLICATION') {

                \App\Models\SolicitationLetterBatch::updateOrCreate(
                    [
                        'project_document_id' => $document->id
                    ],
                    [
                        'approved_letter_count' => $request->approved_letter_count,
                        'control_series_start' => $request->control_series_start,
                        'control_series_end' => $request->control_series_end,
                    ]
                );

            }
            
            


            if ($formCode === 'SELLING_APPLICATION') {

                $data = \App\Models\SellingApplicationData::where(
                    'project_document_id',
                    $document->id
                )->first();

                if ($data && $request->has('items')) {

                    foreach ($request->items as $itemId => $row) {

                        if (!empty($row['remarks'])) {

                            \App\Models\SellingApplicationItem::where('id', $itemId)
                                ->update([
                                    'remarks' => $row['remarks']
                                ]);

                        }

                        

                    }

                }

            //dd($request->has('items'));   

            }

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
            'SOLICITATION_APPLICATION',
            'SELLING_APPLICATION',
            'REQUEST_TO_PURCHASE',
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

    public function retract(Project $project, $formCode)
    {

        $formType = FormType::where('code', $formCode)->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'approved_by_sacdev') {
            return back()->with('error', 'Only approved documents can be retracted.');
        }

        DB::transaction(function () use ($document) {

            $document->signatures()
                ->where('role', 'sacdev_admin')
                ->update([
                    'status' => 'pending',
                    'signed_at' => null
                ]);

            $document->update([
                'status' => 'submitted'
            ]);

            if ($document->formType->code === 'SOLICITATION_APPLICATION') {

                $document->solicitationBatches()->delete();

            }

            if ($document->formType->code === 'SELLING_APPLICATION') {

                $data = \App\Models\SellingApplicationData::where(
                    'project_document_id',
                    $document->id
                )->first();

                if ($data) {

                    \App\Models\SellingApplicationItem::where(
                        'selling_application_data_id',
                        $data->id
                    )->update([
                        'remarks' => null
                    ]);

                }

            }



        });

        return back()->with('success', 'SACDEV approval has been retracted.');

    }


}