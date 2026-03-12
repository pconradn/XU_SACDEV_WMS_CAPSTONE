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

        return view('org.projects.documents.solicitation.create', [
            'project'          => $project,
            'document'         => $document,
            'data'             => $data,
            'currentSignature' => $currentSignature,
            'isReadOnly'       => $isReadOnly,
            'isProjectHead'    => $isProjectHead,
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

            'target_amount' => ['nullable','numeric'],
            'desired_letter_count' => ['required','integer'],

            'letter_draft_link' => ['nullable','url','max:500'],

        ]);


        $document = $this->getOrCreateDocument($project, 'SOLICITATION_APPLICATION');

        if ($document->isLocked()) {
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

            $this->resetApprovalsAfterEdit($document);

        });


        $action = $request->input('action');

        if ($action === 'submit') {
            return $this->submit($project);
        }

        return redirect()
            ->route('org.projects.documents.hub', $project)
            ->with('success', 'Solicitation application saved as draft.');
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
        $formType = FormType::where('code', 'SOLICITATION_APPLICATION')->firstOrFail();

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
            'Solicitation application submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id,
                    'form_type' => 'solicitation_application'
                ]
            ]
        );

        return back()->with('success', 'Solicitation application submitted successfully.');
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

}