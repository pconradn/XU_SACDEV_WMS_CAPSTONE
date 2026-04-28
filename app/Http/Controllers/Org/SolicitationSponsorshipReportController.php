<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Documents\BaseProjectDocumentController;

use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\SolicitationSponsorshipReportData;
use App\Models\SolicitationSponsorshipItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Support\Audit;

class SolicitationSponsorshipReportController extends BaseProjectDocumentController
{

    public function create(Project $project)
    {

        $document = $this->getDocument($project, 'SOLICITATION_SPONSORSHIP_REPORT');

        $data = null;
        $items = collect();

        if ($document) {

            $data = SolicitationSponsorshipReportData::where(
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
        $formCode = 'SOLICITATION_SPONSORSHIP_REPORT';
        return view(
            'org.projects.documents.solicitation-sponsorship-report.create',
            [
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
            ]
        );

    }



    public function store(Request $request, Project $project)
    {


        $request->merge([

            'approved_letters_distributed' => $request->approved_letters_distributed
                ? str_replace(',', '', $request->approved_letters_distributed)
                : null,

            'items' => collect($request->items)->map(function ($item) {
                $item['amount_given'] = isset($item['amount_given'])
                    ? str_replace(',', '', $item['amount_given'])
                    : null;

                return $item;
            })->toArray(),

        ]);

        $validator = \Validator::make($request->all(), [

            'activity_name' => ['required','string','max:255','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],
            'purpose' => ['nullable','string'],

            'solicitation_from' => ['required','date'],
            'solicitation_to' => ['required','date'],

            'approved_letters_distributed' => ['nullable','integer','regex:/^\d+$/'],

            'items.*.control_number' => ['required','string','max:100'],
            'items.*.person_in_charge' => ['required','string','max:255','regex:/^[\pL\s\.\-\,\(\)\'\"]+$/u'],
            'items.*.recipient' => ['required','string','max:255'],
            'items.*.amount_given' => ['nullable','numeric'],
            'items.*.remarks' => ['nullable','string','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],

        ]);

        $user = auth()->user();

        $isProjectHead = $this->isProjectHead($project, $user->id);
        $isDraftee = $this->isDraftee($project, $user->id);

        $action = $request->input('action', 'draft');

        if ($action === 'submit' && $isDraftee) {
            return back()->withErrors([
                'action' => 'Only project head can submit this document.'
            ])->withInput();
        }

        if ($validator->fails()) {

            $this->getOrCreateDocument($project, 'SOLICITATION_SPONSORSHIP_REPORT');

            return back()
                ->with('warning', 'Form has errors. Saved as draft instead.')
                ->withErrors($validator)
                ->withInput();
        }


        $document = $this->getOrCreateDocument(
            $project,
                'SOLICITATION_SPONSORSHIP_REPORT'
        );

        if ($response = $this->checkConflict($request, $document)) {
            return $response;
        }

        if ($document->isLocked() && !$document->edit_mode) {
            abort(403, 'This document is already approved and cannot be edited.');
        }




        DB::transaction(function () use ($request, $document) {

            $data = SolicitationSponsorshipReportData::updateOrCreate(

                [
                    'project_document_id' => $document->id
                ],

                [
                    'activity_name' => $request->activity_name,
                    'purpose' => $request->purpose,

                    'solicitation_from' => $request->solicitation_from,
                    'solicitation_to' => $request->solicitation_to,

                    'approved_letters_distributed' => $request->approved_letters_distributed
                ]

            );


            $data->items()->delete();

            foreach ($request->items as $item) {

                SolicitationSponsorshipItem::create([

                    'solicitation_sponsorship_report_id' => $data->id,

                    'control_number' => $item['control_number'],
                    'person_in_charge' => $item['person_in_charge'],
                    'recipient' => $item['recipient'],
                    'amount_given' => $item['amount_given'] ?? null,
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

        return back()->with(
            'success',
            'Solicitation / Sponsorship Report Saved.'
        );

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
        $formType = FormType::where(
            'code',
            'SOLICITATION_SPONSORSHIP_REPORT'
        )->firstOrFail();

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
            'Solicitation Sponsorship Report submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'solicitation_sponsorship_report'
                ]
            ]
        );

        return back()->with(
            'success',
            'Solicitation / Sponsorship Report submitted successfully.'
        );
    }



    public function approve(Project $project)
    {

        $document = $this->getDocument(
            $project,
            'SOLICITATION_SPONSORSHIP_REPORT'
        );

        if ($document->status !== 'submitted') {
            return back()->with(
                'error',
                'This document is not awaiting approval.'
            );
        }

        $this->handleApproval($project, $document);

        return back()->with('success','Approval recorded.');

    }



    public function return(Request $request, Project $project)
    {

        $request->validate([
            'remarks' => ['required','string']
        ]);


        $document = $this->getDocument(
            $project,
            'SOLICITATION_SPONSORSHIP_REPORT'
        );


        if ($document->status !== 'submitted') {
            return back()->with(
                'error',
                'This document cannot be returned.'
            );
        }


        $this->handleReturn(
            $project,
            $document,
            $request->remarks
        );


        return back()->with(
            'success',
            'Solicitation / Sponsorship Report returned for revision.'
        );

    }

}