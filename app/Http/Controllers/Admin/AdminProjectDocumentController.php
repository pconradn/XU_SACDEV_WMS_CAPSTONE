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

                'documentationReport.objectives',
                'documentationReport.indicators',
                'documentationReport.partners',
                'documentationReport.attendees',
            ])
            ->where('project_id', $project->id)
            ->whereHas('formType', fn($q) => $q->where('code', $formType))
            ->firstOrFail();


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


    public function approve(Request $request, Project $project, $formCode){

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

            'FEES_COLLECTION_REPORT',
            'SELLING_ACTIVITY_REPORT',
            'SOLICITATION_SPONSORSHIP_REPORT',
            'TICKET_SELLING_REPORT',

            'DOCUMENTATION_REPORT',
            'LIQUIDATION_REPORT',
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