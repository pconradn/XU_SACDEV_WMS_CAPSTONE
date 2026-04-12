<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Models\FormType;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\ProjectDocumentSignature;
use App\Models\SolicitationApplicationData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Audit;

class SolicitationApplicationController extends BaseProjectDocumentController
{

    public function create(Project $project)
    {
        $document = $this->getDocument($project, 'SOLICITATION_APPLICATION');

        $data = null;

        if ($document) {
            $data = SolicitationApplicationData::where(
                'project_document_id',
                $document->id
            )->first();
        }

        $user = auth()->user();

        $orgId = session('active_org_id');
        $syId  = session('encode_sy_id');

        $orgRole = $this->getOrgRole($user->id, $orgId, $syId);
        $roles   = $this->resolveRoleFlags($orgRole);

        $isProjectHead = $this->isProjectHead($project, $user->id);
        $currentSignature = $this->getCurrentSignature($document, $user->id);

        $isReadOnly = $this->computeReadOnly($document, $isProjectHead);
        $formCode = 'SOLICITATION_APPLICATION';
        return view('org.projects.documents.solicitation.create', [
            'project'          => $project,
            'document'         => $document,
            'data'             => $data,
            'currentSignature' => $currentSignature,
            'isReadOnly'       => $isReadOnly,
            'isProjectHead'    => $isProjectHead,
            'formCode' => $formCode,
            ...$roles
        ]);
    }


    public function store(Request $request, Project $project)
    {

        $validator = \Validator::make($request->all(), [

            'activity_name' => ['required','string','max:255','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],
            'purpose' => ['required','string','regex:/^[\pL\pN\s\.\-\,\(\)\'\"\/]+$/u'],

            'duration_from' => ['required','date'],
            'duration_to' => ['required','date'],

            'target_amount' => ['nullable','numeric',],
            'desired_letter_count' => ['required','integer','regex:/^\d+$/'],

            'letter_draft_link' => ['nullable','url','max:500'],

        ]);

        if ($validator->fails()) {

            $this->getOrCreateDocument($project, 'SOLICITATION_APPLICATION');

            return back()
                ->with('warning', 'Form has errors. Saved as draft instead.')
                ->withErrors($validator)
                ->withInput();
        }


        $document = $this->getOrCreateDocument($project, 'SOLICITATION_APPLICATION');

        if ($document->isLocked() && !$document->edit_mode) {
            abort(403, 'This document is already approved and cannot be edited.');
        }


        DB::transaction(function () use ($request, $document) {

            SolicitationApplicationData::updateOrCreate(
                [
                    'project_document_id' => $document->id
                ],
                [
                    'activity_name' => $request->activity_name,
                    'purpose' => $request->purpose,

                    'duration_from' => $request->duration_from,
                    'duration_to' => $request->duration_to,

                    'target_amount' => $request->target_amount,
                    'desired_letter_count' => $request->desired_letter_count,

                    'target_student_orgs' => $request->boolean('target_student_orgs'),
                    'target_xu_officers' => $request->boolean('target_xu_officers'),
                    'target_private_individuals' => $request->boolean('target_private_individuals'),
                    'target_alumni' => $request->boolean('target_alumni'),
                    'target_private_companies' => $request->boolean('target_private_companies'),

                    'target_others' => $request->boolean('target_others'),
                    'target_others_specify' => $request->target_others_specify,

                    'letter_draft_link' => $request->letter_draft_link
                ]
            );

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

        return back()->with('success', 'Solicitation application saved as draft.');
    }

    public function submit(Project $project)
    {
        $formType = FormType::where('code', 'SOLICITATION_APPLICATION')->firstOrFail();

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

        $document->load('signatures', 'formType', 'project');

        Audit::log(
            'document.submitted',
            'Solicitation application submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'solicitation_application',
                    'edit_mode_submission' => $document->edit_mode 
                ]
            ]
        );

        return back()->with('success', 'Solicitation application submitted successfully.');
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







    public function approve(Project $project)
    {
        $document = $this->getDocument($project,'SOLICITATION_APPLICATION');

        if ($document->status !== 'submitted') {
            return back()->with('error','This form is not awaiting approval.');
        }

        $this->handleApproval($project,$document);

        return back()->with('success','Solicitation application approved.');
    }



    public function return(Request $request, Project $project)
    {
        $request->validate([
            'remarks' => ['required','string']
        ]);

        $document = $this->getDocument($project,'SOLICITATION_APPLICATION');

        if ($document->status !== 'submitted') {
            return back()->with('error','This form cannot be returned.');
        }

        $this->handleReturn(
            $project,
            $document,
            $request->remarks
        );

        return back()->with('success','Solicitation form returned for revision.');
    }


}