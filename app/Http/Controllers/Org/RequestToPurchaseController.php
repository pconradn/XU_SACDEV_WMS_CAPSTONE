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
use App\Models\RequestToPurchaseData;
use App\Models\RequestToPurchaseItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestToPurchaseController extends BaseProjectDocumentController
{

    public function create(Project $project)
    {

        $document = $this->getDocument($project, 'REQUEST_TO_PURCHASE');

        $data = null;
        $items = collect();

        if ($document) {

            $data = RequestToPurchaseData::where(
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

        return view('org.projects.documents.request-to-purchase.create', [

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

            'items.*.quantity' => ['required','integer'],
            'items.*.unit' => ['nullable','string','max:50'],
            'items.*.particulars' => ['required','string','max:255'],
            'items.*.unit_price' => ['required','numeric'],
            'items.*.vendor' => ['nullable','string','max:255'],

        ]);


        $document = $this->getOrCreateDocument($project, 'REQUEST_TO_PURCHASE');

        if ($document->isLocked()) {
            abort(403, 'This document is already approved and cannot be edited.');
        }


        DB::transaction(function () use ($request, $document) {

            $data = RequestToPurchaseData::updateOrCreate(

                [
                    'project_document_id' => $document->id
                ],

                [
                    'xu_finance_amount' => $request->xu_finance_amount ?? 0,
                    'membership_fee_amount' => $request->membership_fee_amount ?? 0,
                    'pta_amount' => $request->pta_amount ?? 0,
                    'solicitations_amount' => $request->solicitations_amount ?? 0,

                    'others_amount' => $request->others_amount ?? 0,
                    'others_label' => $request->others_label,
                ]

            );


            $data->items()->delete();

            foreach ($request->items as $item) {

                RequestToPurchaseItem::create([

                    'request_to_purchase_id' => $data->id,

                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'] ?? null,
                    'particulars' => $item['particulars'],
                    'unit_price' => $item['unit_price'],
                    'amount' => ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0),
                    'vendor' => $item['vendor'] ?? null,

                ]);

            }


            $this->resetApprovalsAfterEdit($document);

        });


        $action = $request->input('action');

        if ($action === 'submit') {
            return $this->submit($project);
        }

        return redirect()
            ->route('org.projects.documents.hub', $project)
            ->with('success', 'Request to Purchase saved as draft.');

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

        $formType = FormType::where('code', 'REQUEST_TO_PURCHASE')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();


        if ($document->status !== 'draft') {
            return back()->with('error', 'This form is already submitted.');
        }


        DB::transaction(function () use ($project, $document) {

            $document->update([

                'status' => 'submitted',
                'submitted_at' => now(),

                'remarks' => null,
                'returned_by' => null,
                'returned_at' => null,

            ]);


            $document->signatures()->delete();


            /*
            |---------------------------------------
            | Project Head (auto-signed)
            |---------------------------------------
            */
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


            /*
            |---------------------------------------
            | Treasurer
            |---------------------------------------
            */
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


            /*
            |---------------------------------------
            | President
            |---------------------------------------
            */
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


            /*
            |---------------------------------------
            | Moderator
            |---------------------------------------
            */
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


            /*
            |---------------------------------------
            | SACDEV Admin
            |---------------------------------------
            */
            $admin = User::where('system_role','sacdev_admin')->firstOrFail();

            $this->createSignature(
                $document->id,
                $admin->id,
                'sacdev_admin'
            );

        });


        return back()->with('success', 'Request to Purchase submitted successfully.');

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

        $formType = FormType::where('code', 'REQUEST_TO_PURCHASE')->firstOrFail();

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


        $formType = FormType::where('code', 'REQUEST_TO_PURCHASE')->firstOrFail();


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


        return back()->with('success', 'Request to Purchase returned for revision.');

    }

}