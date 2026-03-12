<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\SellingActivityReportData;
use App\Models\SellingActivityReportItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Audit;

class SellingActivityReportController extends BaseProjectDocumentController
{

    public function create(Project $project)
    {

        $document = $this->getDocument($project, 'SELLING_ACTIVITY_REPORT');

        $data = null;
        $items = collect();

        if ($document) {

            $data = SellingActivityReportData::where(
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

        return view('org.projects.documents.selling-activity-report.create', [

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
            'selling_from' => ['required','date'],
            'selling_to' => ['required','date'],

            'items.*.quantity' => ['required','integer'],
            'items.*.particulars' => ['required','string','max:255'],
            'items.*.price' => ['required','numeric'],
            'items.*.amount' => ['nullable','numeric'],
            'items.*.acknowledgement_receipt_number' => ['nullable','string','max:255'],

        ]);


        $document = $this->getOrCreateDocument($project, 'SELLING_ACTIVITY_REPORT');

        if ($document->isLocked()) {
            abort(403, 'This document is already approved and cannot be edited.');
        }


        DB::transaction(function () use ($request, $document) {

            $data = SellingActivityReportData::updateOrCreate(

                [
                    'project_document_id' => $document->id
                ],

                [
                    'activity_name' => $request->activity_name,
                    'selling_from' => $request->selling_from,
                    'selling_to' => $request->selling_to,
                ]

            );


            $data->items()->delete();

            foreach ($request->items as $item) {

                SellingActivityReportItem::create([

                    'selling_activity_report_id' => $data->id,

                    'quantity' => $item['quantity'],
                    'particulars' => $item['particulars'],
                    'price' => $item['price'],
                    'amount' => $item['amount'] ?? (($item['quantity'] ?? 0) * ($item['price'] ?? 0)),
                    'acknowledgement_receipt_number' => $item['acknowledgement_receipt_number'] ?? null,

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
            ->with('success', 'Selling Activity Report saved as draft.');

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

        $formType = FormType::where('code', 'SELLING_ACTIVITY_REPORT')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'draft') {
            return back()->with('error', 'This form is already submitted.');
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
            'Selling Activity Report submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'selling_activity_report'
                ]
            ]
        );

        return back()->with('success', 'Selling Activity Report submitted successfully.');
    }



    public function approve(Project $project)
    {

        $document = $this->getDocument($project,'SELLING_ACTIVITY_REPORT');

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

        $document = $this->getDocument($project,'SELLING_ACTIVITY_REPORT');

        if ($document->status !== 'submitted') {
            return back()->with('error','This document cannot be returned.');
        }

        $this->handleReturn(
            $project,
            $document,
            $request->remarks
        );

        return back()->with('success','Selling Activity Report returned for revision.');

    }

}