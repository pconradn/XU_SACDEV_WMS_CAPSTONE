<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Models\FormType;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\ProjectDocumentSignature;
use App\Models\SellingApplicationData;
use App\Models\SellingApplicationItem;
use App\Models\User;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellingApplicationController extends BaseProjectDocumentController
{

    public function create(Project $project)
    {

        $document = $this->getDocument($project, 'SELLING_APPLICATION');

        $data = null;
        $items = collect();

        if ($document) {

            $data = SellingApplicationData::where(
                'project_document_id',
                $document->id
            )->first();

            if ($data) {
                $items = $data->items;
            }

        }

        $user = auth()->user();

        $isAdmin = $user->system_role === 'sacdev_admin';

        $orgId = session('active_org_id');
        $syId  = session('encode_sy_id');

        $orgRole = $this->getOrgRole($user->id, $orgId, $syId);

        $roles = $this->resolveRoleFlags($orgRole);

        $isProjectHead = $this->isProjectHead($project, $user->id);

        $currentSignature = $this->getCurrentSignature($document, $user->id);

        $isReadOnly = $this->computeReadOnly($document, $isProjectHead);

        return view('org.projects.documents.selling.create', [

            'project' => $project,
            'document' => $document,
            'data' => $data,
            'items' => $items,
            'currentSignature' => $currentSignature,
            'isReadOnly' => $isReadOnly,
            'isProjectHead' => $isProjectHead,
            'isAdmin' => $isAdmin,

            ...$roles

        ]);

    }



    public function store(Request $request, Project $project)
    {

        $request->validate([

            'activity_name' => ['required','string','max:255'],
            'purpose' => ['required','string'],

            'duration_from' => ['required','date'],
            'duration_to' => ['required','date'],

            'projected_sales' => ['nullable','numeric'],

            'items.*.quantity' => ['required','integer'],
            'items.*.particulars' => ['required','string','max:255'],
            'items.*.selling_price' => ['required','numeric'],
            //'items.*.remarks' => ['nullable','string','max:255'],

        ]);


        $document = $this->getOrCreateDocument($project, 'SELLING_APPLICATION');

        if ($document->isLocked() && !$document->edit_mode) {
            abort(403, 'This document is already approved and cannot be edited.');
        }


        DB::transaction(function () use ($request, $document) {

            $data = SellingApplicationData::updateOrCreate(

                [
                    'project_document_id' => $document->id
                ],

                [
                    'activity_name' => $request->activity_name,
                    'purpose' => $request->purpose,

                    'duration_from' => $request->duration_from,
                    'duration_to' => $request->duration_to,

                    'projected_sales' => $request->projected_sales,
                ]

            );



            $data->items()->delete();

            foreach ($request->items as $item) {

                SellingApplicationItem::create([

                    'selling_application_data_id' => $data->id,

                    'quantity' => $item['quantity'],
                    'particulars' => $item['particulars'],
                    'selling_price' => $item['selling_price'],
                    'remarks' => $item['remarks'] ?? null

                ]);

            }


            if (!$document->edit_mode) {
                $this->resetApprovalsAfterEdit($document);
            }

        });


        $action = $request->input('action');

        if ($document && $document->edit_mode) {
            $action = 'submit';
        }

        if ($action === 'submit') {
            return $this->submit($project);
        }

        return redirect()
            ->route('org.projects.documents.hub', $project)
            ->with('success', 'Selling application saved as draft.');

    }



    private function resetApprovalsAfterEdit(ProjectDocument $document): void
    {

        if ($document->status === 'draft') {
            return;
        }

        $document->signatures()
            ->whereIn('role', [
                'president',
                'moderator',
                'sacdev_admin'
            ])
            ->delete();

        $document->update([
            'status' => 'draft',
            'submitted_at' => null
        ]);

    }



    public function submit(Project $project)
    {
        $formType = FormType::where('code', 'SELLING_APPLICATION')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'draft' && !$document->edit_mode) {
            return back()->with('error', 'This form cannot be submitted.');
        }

        $this->handleRequestSubmit($project, $document);

        $document->load('signatures','formType','project');

        Audit::log(
            'document.submitted',
            'Selling application submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'selling_application'
                ]
            ]
        );

        return back()->with('success', 'Selling application submitted successfully.');
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

    public function approve(Project $project)
    {

        $formType = FormType::where('code', 'SELLING_APPLICATION')->firstOrFail();

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


        $currentPending->update([
            'status' => 'signed',
            'signed_at' => now(),
        ]);


        return back()->with('success', 'Approval recorded.');
    }


    public function return(Request $request, Project $project)
    {

        $request->validate([
            'remarks' => ['required', 'string'],
        ]);


        $formType = FormType::where('code', 'SELLING_APPLICATION')->firstOrFail();


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


        return back()->with('success', 'Application returned for revision.');
    }


}