<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Models\FeesCollectionReportData;
use App\Models\FeesCollectionItem;
use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Audit;

class FeesCollectionReportController extends BaseProjectDocumentController
{

    public function create(Project $project)
    {

        $document = $this->getDocument($project, 'FEES_COLLECTION_REPORT');

        $data = null;
        $items = collect();

        if ($document) {

            $data = FeesCollectionReportData::where(
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

        return view('org.projects.documents.fees-collection.create', [

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

            'collection_from' => ['required','date'],
            'collection_to' => ['required','date'],

            'items.*.number_of_payers' => ['required','integer'],
            'items.*.amount_paid' => ['required','numeric'],
            'items.*.receipt_series' => ['nullable','string','max:255'],

        ]);


        $document = $this->getOrCreateDocument($project, 'FEES_COLLECTION_REPORT');

        if ($document->isLocked()) {
            abort(403, 'This document is already approved and cannot be edited.');
        }


        DB::transaction(function () use ($request, $document) {

            $data = FeesCollectionReportData::updateOrCreate(

                [
                    'project_document_id' => $document->id
                ],

                [
                    'activity_name' => $request->activity_name,
                    'purpose' => $request->purpose,

                    'collection_from' => $request->collection_from,
                    'collection_to' => $request->collection_to
                ]

            );


            $data->items()->delete();

            foreach ($request->items as $item) {

                FeesCollectionItem::create([

                    'fees_collection_report_id' => $data->id,

                    'number_of_payers' => $item['number_of_payers'],
                    'amount_paid' => $item['amount_paid'],
                    'receipt_series' => $item['receipt_series'] ?? null,
                    'remarks' => $item['remarks'] ?? null

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
            ->with('success', 'Fees collection report saved as draft.');

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

        $formType = FormType::where('code', 'FEES_COLLECTION_REPORT')->firstOrFail();

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
            'Fees collection report submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'fees_collection_report'
                ]
            ]
        );


        return back()->with('success', 'Fees collection report submitted successfully.');

    }



    public function approve(Project $project)
    {

        $document = $this->getDocument($project,'FEES_COLLECTION_REPORT');

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


        $document = $this->getDocument($project,'FEES_COLLECTION_REPORT');

        if ($document->status !== 'submitted') {
            return back()->with('error','This document cannot be returned.');
        }


        $this->handleReturn(
            $project,
            $document,
            $request->remarks
        );


        return back()->with('success','Report returned for revision.');

    }

}