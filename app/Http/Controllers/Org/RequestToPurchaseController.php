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
use App\Support\Audit;

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

        $isAdmin = $user->isSacdev();

        $orgId = session('active_org_id');
        $syId  = session('encode_sy_id');

        $orgRole = $this->getOrgRole($user->id, $orgId, $syId);

        $roles = $this->resolveRoleFlags($orgRole);

        $isProjectHead = $this->isProjectHead($project, $user->id);

        $currentSignature = $this->getCurrentSignature($document, $user->id);

        $isReadOnly = $this->computeReadOnly($document, $isProjectHead);
        $formCode = 'REQUEST_TO_PURCHASE';
        return view('org.projects.documents.request-to-purchase.create', [

            'project' => $project,
            'document' => $document,
            'data' => $data,
            'items' => $items,
            'currentSignature' => $currentSignature,
            'isReadOnly' => $isReadOnly,
            'isProjectHead' => $isProjectHead,
            'isAdmin' => $isAdmin,
            'formCode' => $formCode,

            ...$roles

        ]);

    }



    public function store(Request $request, Project $project)
    {
 
        $items = collect($request->items)->map(function ($item) {
            return [
                ...$item,
                'quantity' => isset($item['quantity'])
                    ? (int) str_replace(',', '', $item['quantity'])
                    : 0,

                'unit_price' => isset($item['unit_price'])
                    ? (float) str_replace(',', '', $item['unit_price'])
                    : 0,
            ];
        });

        $request->merge([
            'items' => $items->toArray(),

            'xu_finance_amount' => $request->xu_finance_amount !== null && $request->xu_finance_amount !== ''
                ? (float) str_replace(',', '', $request->xu_finance_amount)
                : 0,

            'membership_fee_amount' => $request->membership_fee_amount !== null && $request->membership_fee_amount !== ''
                ? (float) str_replace(',', '', $request->membership_fee_amount)
                : 0,

            'pta_amount' => $request->pta_amount !== null && $request->pta_amount !== ''
                ? (float) str_replace(',', '', $request->pta_amount)
                : 0,

            'solicitations_amount' => $request->solicitations_amount !== null && $request->solicitations_amount !== ''
                ? (float) str_replace(',', '', $request->solicitations_amount)
                : 0,

            'others_amount' => $request->others_amount !== null && $request->others_amount !== ''
                ? (float) str_replace(',', '', $request->others_amount)
                : 0,
        ]);





        
        $validator = \Validator::make($request->all(), [

            'items.*.quantity' => ['required','integer'],
            'items.*.unit' => ['nullable','string','max:50'],
            'items.*.particulars' => ['required','string','max:255'],
            'items.*.unit_price' => ['required','numeric'],
            'items.*.vendor' => ['nullable','string','max:255'],

        ]);

        if ($validator->fails()) {

            $this->getOrCreateDocument($project, 'REQUEST_TO_PURCHASE');

            return back()
                ->with('warning', 'Form has errors. Saved as draft instead.')
                ->withErrors($validator)
                ->withInput();
        }


        $document = $this->getOrCreateDocument($project, 'REQUEST_TO_PURCHASE');

        if ($document->isLocked() && !$document->edit_mode) {
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

        return back()->with('success', 'Request to Purchase saved.');

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

        Audit::log(
            'document.submitted',
            'Request to Purchase submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'request_to_purchase'
                ]
            ]
        );

        return back()->with('success', 'Request to Purchase submitted successfully.');
    }

    public function approve(Project $project)
    {
        $document = $this->getDocument($project,'REQUEST_TO_PURCHASE');

        if ($document->status !== 'submitted') {
            return back()->with('error','This document is not awaiting approval.');
        }

        $this->handleApproval($project,$document);

        return back()->with('success','Approval recorded.');
    }

    public function return(Request $request, Project $project)
    {
        $request->validate([
            'remarks' => ['required','string']
        ]);

        $document = $this->getDocument($project,'REQUEST_TO_PURCHASE');

        if ($document->status !== 'submitted') {
            return back()->with('error','This document cannot be returned.');
        }

        $this->handleReturn(
            $project,
            $document,
            $request->remarks
        );

        return back()->with('success','Request to Purchase returned for revision.');
    }

}