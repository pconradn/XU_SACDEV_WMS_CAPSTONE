<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Notifications\ReregActionNotification;
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

        $postponementType = FormType::where('code','POSTPONEMENT_NOTICE')->first();
        $cancellationType = FormType::where('code','CANCELLATION_NOTICE')->first();

        $postponements = ProjectDocument::where('project_id',$project->id)
            ->where('form_type_id',$postponementType->id)
            ->whereNull('archived_at')
            ->latest()
            ->get();

        $cancellations = ProjectDocument::where('project_id',$project->id)
            ->where('form_type_id',$cancellationType->id)
            ->whereNull('archived_at')
            ->latest()
            ->get();
        

        

        return view('admin.projects.documents.hub', [
            'project'   => $project,
            'documents' => $documents,
            'postponements' => $postponements,
            'cancellations' => $cancellations,
        ]);
    }


    public function open(Project $project, $formType, $documentId = null)
    {

        $query = ProjectDocument::query()
            ->with([
                'signatures.user',
                'formType',
                'budgetProposal.items',
                'sellingApplication.items',
                'requestToPurchase.items',
                'feesCollectionReport.items',
                'sellingActivityReport.items',
                'solicitationSponsorshipReport.items',
                'ticketSellingReport.items',
                'liquidationData.items',

                'postponementNotice',
                'cancellationNotice',

                'documentationReport.objectives',
                'documentationReport.indicators',
                'documentationReport.partners',
                'documentationReport.attendees',
            ])
            ->where('project_id', $project->id)
            ->whereHas('formType', fn($q) => $q->where('code', $formType));

        if ($documentId !== null) {
            $document = $query->where('id', $documentId)->firstOrFail();
        } else {
            $document = $query->firstOrFail();
        }


        $viewMap = [

            'PROJECT_PROPOSAL' => 'org.projects.documents.project-proposal.create',
            'BUDGET_PROPOSAL' => 'org.projects.documents.budget-proposal.create',

            'OFF_CAMPUS_APPLICATION' => 'org.projects.documents.off-campus.create',

            'SOLICITATION_APPLICATION' => 'org.projects.documents.solicitation.create',
            'SELLING_APPLICATION' => 'org.projects.documents.selling.create',

            'REQUEST_TO_PURCHASE' => 'org.projects.documents.request-to-purchase.create',

            'FEES_COLLECTION_REPORT' => 'org.projects.documents.fees-collection.create',
            'SELLING_ACTIVITY_REPORT' => 'org.projects.documents.selling-activity-report.create',
            'SOLICITATION_SPONSORSHIP_REPORT' => 'org.projects.documents.solicitation-sponsorship-report.create',
            'TICKET_SELLING_REPORT' => 'org.projects.documents.ticket-selling-report.create',

            'DOCUMENTATION_REPORT' => 'org.projects.documents.documentation-report.create',
            'LIQUIDATION_REPORT' => 'org.projects.documents.liquidation-report.create',
            
            
            'POSTPONEMENT_NOTICE' => 'org.projects.documents.postponement.create',
            'CANCELLATION_NOTICE' => 'org.projects.documents.cancellation.create',

        ];

        $view = $viewMap[$formType] ?? abort(404);


        $proposal = $document->proposalData;
        $budget = $document->budgetProposal;

        $activity = $document->offCampusActivity;
        $participants = $activity?->participants ?? collect();

        $solicitation = $document->solicitationData;
        $selling = $document->sellingApplication;
        $purchase = $document->requestToPurchase;

        $feesCollection = $document->feesCollectionReport;
        $sellingActivity = $document->sellingActivityReport;
        $solicitationReport = $document->solicitationSponsorshipReport;
        $ticketReport = $document->ticketSellingReport;


        $report = null;

        if ($formType === 'LIQUIDATION_REPORT') {
            $report = $document->liquidationData;
        }

        if ($formType === 'DOCUMENTATION_REPORT') {
            $report = $document->documentationReport;
        }


        $documentation = $document->documentationReport;

        $objectives = $documentation?->objectives ?? collect();
        $indicators = $documentation?->indicators ?? collect();
        $partners = $documentation?->partners ?? collect();
        $attendees = $documentation?->attendees ?? collect();


        $prefill = [];

        if ($documentation) {
            $prefill = [
                'implementation_start_date' => $documentation->implementation_start_date,
                'implementation_end_date'   => $documentation->implementation_end_date,
                'implementation_start_time' => $documentation->implementation_start_time,
                'implementation_end_time'   => $documentation->implementation_end_time,
                'on_campus_venue'           => $documentation->on_campus_venue,
                'off_campus_venue'          => $documentation->off_campus_venue,
                'description'               => $documentation->description,
            ];
        }


        $items =
            $purchase?->items ??
            $selling?->items ??
            $feesCollection?->items ??
            $sellingActivity?->items ??
            $solicitationReport?->items ??
            $ticketReport?->items ??
            $report?->items ??
            collect();

        



        $data = null;

        if ($formType === 'POSTPONEMENT_NOTICE') {
            $data = $document->postponementNotice;
        }

        if ($formType === 'CANCELLATION_NOTICE') {
            $data = $document->cancellationNotice;      
        }

        if ($formType === 'FEES_COLLECTION_REPORT') {
            $data = $feesCollection;
            $items = $feesCollection?->items ?? collect();
        }


        if ($formType === 'SELLING_ACTIVITY_REPORT') {
            $data = $sellingActivity;
            $items = $sellingActivity?->items ?? collect();
        }

        if ($formType === 'SOLICITATION_SPONSORSHIP_REPORT') {
            $data = $solicitationReport;
            $items = $solicitationReport?->items ?? collect();
        }

        if ($formType === 'TICKET_SELLING_REPORT') {
            $data = $ticketReport;
            $items = $ticketReport?->items ?? collect();
        }


        $user = auth()->user();
        $userId = $user->id;

        $isAdmin = $user->system_role === 'sacdev_admin';


        $currentSignature = $document->signatures
            ->where('user_id', $userId)
            ->first();


        $proposalDocument = ProjectDocument::where('project_id', $project->id)
            ->whereHas('formType', fn($q) => $q->where('code', 'PROJECT_PROPOSAL'))
            ->first();

        $proposal = $proposalDocument?->proposalData;

        //dd(
        //    $document->id,
        //    $document->postponementNotice
        //);

        return view($view, [

            'project' => $project,
            'document' => $document,

            'proposal' => $proposal,
            'budget' => $budget,

            'activity' => $activity,
            'participants' => $participants,

            'data' => $data ?? ($purchase ?? $solicitation ?? $selling),

            'feesCollection' => $feesCollection,
            'sellingActivity' => $sellingActivity,
            'solicitationReport' => $solicitationReport,
            'ticketReport' => $ticketReport,

            'documentation' => $documentation,
            'objectives' => $objectives,
            'indicators' => $indicators,
            'partners' => $partners,
            'attendees' => $attendees,

            'prefill' => $prefill,

            'report' => $report,

            'items' => $items,

            'isReadOnly' => true,
            'isProjectHead' => false,
            'currentSignature' => $currentSignature,
            'isAdmin' => $isAdmin,
        ]);
    }


    public function showPrint(Project $project, $formType, $documentId = null)
    {
        $query = ProjectDocument::query()
            ->with([
                'signatures.user',
                'formType',
                'proposalData.guests',
                'proposalData.planOfActions',
                'budgetProposal.items',
            ])
            ->where('project_id', $project->id)
            ->whereHas('formType', fn($q) => $q->where('code', $formType));

        $document = $documentId
            ? $query->where('id', $documentId)->firstOrFail()
            : $query->firstOrFail();

        if ($document->status !== 'approved_by_sacdev') {
            abort(403, 'Document not approved for printing.');
        }

        if (auth()->user()->system_role !== 'sacdev_admin') {
            abort(403);
        }

        $proposal = $document->proposalData;
        $budget = $document->budgetProposal;

        $view = match ($formType) {
            'PROJECT_PROPOSAL' => 'admin.projects.documents.project-proposal.print',
            'BUDGET_PROPOSAL'  => 'admin.projects.documents.budget-proposal.print',
            default => abort(404),
        };

        return view($view, [
            'project' => $project,
            'document' => $document,
            'proposal' => $proposal,
            'budget' => $budget,
        ]);
    }

    public function approve(Request $request, Project $project, $formCode, $documentId = null)
    {

        $allowedForms = [

            'PROJECT_PROPOSAL',
            'BUDGET_PROPOSAL',

            'OFF_CAMPUS_APPLICATION',

            'SOLICITATION_APPLICATION',
            'SELLING_APPLICATION',

            'REQUEST_TO_PURCHASE',

            'FEES_COLLECTION_REPORT',
            'SELLING_ACTIVITY_REPORT',
            'SOLICITATION_SPONSORSHIP_REPORT',
            'TICKET_SELLING_REPORT',

            'DOCUMENTATION_REPORT',
            'LIQUIDATION_REPORT',

            // NEW
            'POSTPONEMENT_NOTICE',
            'CANCELLATION_NOTICE',

        ];

        if (!in_array($formCode, $allowedForms)) {
            abort(404);
        }

        $formType = FormType::where('code', $formCode)->firstOrFail();

        $query = ProjectDocument::with('signatures')
            ->where('project_id', $project->id)
            ->where('form_type_id', $formType->id);

        if ($documentId) {
            $document = $query->where('id', $documentId)->firstOrFail();
        } else {
            $document = $query->firstOrFail();
        }

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


        DB::transaction(function () use ($request, $document, $currentPending, $formCode) {

            /*
            |------------------------------------------------------------------
            | Special logic for certain forms
            |------------------------------------------------------------------
            */

            if ($formCode === 'SOLICITATION_APPLICATION') {

                $request->validate([
                    'approved_letter_count' => ['required','integer','min:1'],
                    'control_series_start' => ['required','string','max:255'],
                    'control_series_end' => ['required','string','max:255'],
                ]);

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


            if ($formCode === 'FEES_COLLECTION_REPORT') {

                $data = \App\Models\FeesCollectionReportData::where(
                    'project_document_id',
                    $document->id
                )->first();

                if ($data && $request->has('items')) {

                    foreach ($request->items as $itemId => $row) {

                        \App\Models\FeesCollectionItem::where('id', $itemId)
                            ->update([
                                'remarks' => $row['remarks'] ?? null
                            ]);

                    }

                }
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

    public function return(Request $request, Project $project, $formCode, $documentId = null)
    {

        $request->validate([
            'remarks' => ['required', 'string'],
        ]);

        $formType = FormType::where('code', $formCode)->firstOrFail();

        $query = ProjectDocument::with('signatures')
            ->where('project_id', $project->id)
            ->where('form_type_id', $formType->id);

        if ($documentId) {
            $document = $query->where('id', $documentId)->firstOrFail();
        } else {
            $document = $query->firstOrFail();
        }

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

    public function allowEdit(Project $project, $formCode, Request $request)
    {
        $formType = FormType::where('code', $formCode)->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        $this->handleAllowEdit(
            $project,
            $document,
            $request->input('remarks')
        );

        return back()->with('success', 'Edit request granted.');
    }
    

    protected function handleAllowEdit(Project $project, ProjectDocument $document, ?string $remarks = null): void
    {
        if (!$document->edit_requested) {
            abort(403, 'No edit request pending.');
        }

      
        if (!in_array($document->status, ['approved_by_sacdev', 'submitted'], true)) {
            abort(403, 'Edit can only be granted on submitted or approved documents.');
        }

        DB::transaction(function () use ($project, $document, $remarks) {

            $oldStatus = $document->status;

            $document->update([
                'edit_mode' => true,
                'edit_requires_full_approval' => false, 


                'edit_requested' => false,
                'edit_requested_at' => null,
                'edit_requested_by' => null,
                'edit_request_remarks' => null,
            ]);

            $document->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'edit_granted',
                'remarks' => $remarks,
                'old_status' => $oldStatus,
                'new_status' => $oldStatus,
            ]);

        });

        DB::afterCommit(function () use ($project, $document) {

            $this->notifyProjectHead(
                $project,
                $document,
                'Edit request granted. You may update and resubmit. Only SACDEV approval will be required.'
            );

        });
    }

    protected function notifyProjectHead(Project $project, ProjectDocument $document, string $message){
        
        $assignment = ProjectAssignment::where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->with('user')
            ->first();

        if (!$assignment || !$assignment->user) {
            return;
        }

        $assignment->user->notify(new ReregActionNotification([
            'title' => 'Project Document Update',
            'message' => $message,
            'action_url' => route('org.projects.documents.hub', $project),
            'meta' => [
                'document_id' => $document->id,
                'form_type'   => $document->formType->code,
                'project_id'  => $project->id
            ]
        ]));
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

            if ($document->formType->code === 'FEES_COLLECTION_REPORT') {

                $data = \App\Models\FeesCollectionReportData::where(
                    'project_document_id',
                    $document->id
                )->first();

                if ($data) {

                    \App\Models\FeesCollectionItem::where(
                        'fees_collection_report_id',
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