<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\TicketSellingReportData;
use App\Models\TicketSellingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Audit;

class TicketSellingReportController extends BaseProjectDocumentController
{

    public function create(Project $project)
    {

        $document = $this->getDocument($project, 'TICKET_SELLING_REPORT');

        $data = null;
        $items = collect();

        if ($document) {

            $data = TicketSellingReportData::where(
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
        $formCode = 'TICKET_SELLING_REPORT';
        return view('org.projects.documents.ticket-selling-report.create', [

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

        $validator = \Validator::make($request->all(), [

            'items.*.quantity' => ['required','integer','regex:/^\d+$/'],
            'items.*.series_control_numbers' => ['required','string','max:255'],
            'items.*.price_per_ticket' => ['required','numeric'],
            'items.*.remarks' => ['nullable','string','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],

        ]);

        if ($validator->fails()) {

            $this->getOrCreateDocument($project, 'TICKET_SELLING_REPORT');

            return back()
                ->with('warning', 'Form has errors. Saved as draft instead.')
                ->withErrors($validator)
                ->withInput();
        }


        $document = $this->getOrCreateDocument($project, 'TICKET_SELLING_REPORT');

        if ($document->isLocked() && !$document->edit_mode) {
            abort(403, 'This document is already approved and cannot be edited.');
        }


        DB::transaction(function () use ($request, $document) {

            $data = TicketSellingReportData::updateOrCreate(

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

                TicketSellingItem::create([

                    'ticket_selling_report_id' => $data->id,

                    'quantity' => $item['quantity'],
                    'series_control_numbers' => $item['series_control_numbers'],
                    'price_per_ticket' => $item['price_per_ticket'],
                    'amount' => ($item['quantity'] ?? 0) * ($item['price_per_ticket'] ?? 0),
                    'remarks' => $item['remarks'] ?? null,

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

        return back()->with('success', 'Ticket Selling Report saved as draft.');

    }



    private function resetApprovalsAfterEdit(ProjectDocument $document): void
    {

        if ($document->status === 'draft') {
            return;
        }

        $document->signatures()
            ->whereIn('role', [
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
        $formType = FormType::where('code', 'TICKET_SELLING_REPORT')->firstOrFail();

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
            'Ticket Selling Report submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'ticket_selling_report'
                ]
            ]
        );

        return back()->with('success', 'Ticket Selling Report submitted successfully.');
    }



    public function approve(Project $project)
    {

        $document = $this->getDocument($project,'TICKET_SELLING_REPORT');

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

        $document = $this->getDocument($project,'TICKET_SELLING_REPORT');

        if ($document->status !== 'submitted') {
            return back()->with('error','This document cannot be returned.');
        }

        $this->handleReturn(
            $project,
            $document,
            $request->remarks
        );

        return back()->with('success','Ticket Selling Report returned for revision.');

    }

}