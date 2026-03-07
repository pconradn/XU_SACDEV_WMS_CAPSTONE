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


        DB::transaction(function () use ($project, $document) {

            $document->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'remarks' => null,
                'returned_by' => null,
                'returned_at' => null,
            ]);

            $document->signatures()->delete();


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


            $admin = User::where('system_role','sacdev_admin')->firstOrFail();

            $this->createSignature(
                $document->id,
                $admin->id,
                'sacdev_admin'
            );

        });

        return back()->with('success', 'Solicitation application submitted successfully.');
    }



    public function approve(Project $project)
    {

        $formType = FormType::where('code','SOLICITATION_APPLICATION')->firstOrFail();

        $document = ProjectDocument::where('project_id',$project->id)
            ->where('form_type_id',$formType->id)
            ->firstOrFail();

        if ($document->status !== 'submitted') {
            return back()->with('error','This form is not awaiting approval.');
        }

        $userId = auth()->id();

        $userSignature = ProjectDocumentSignature::where('project_document_id',$document->id)
            ->where('user_id',$userId)
            ->first();

        if (!$userSignature) {
            return back()->with('error','You are not part of the approval workflow.');
        }

        if ($userSignature->status === 'signed') {
            return back()->with('error','You have already approved this form.');
        }

        $currentPending = ProjectDocumentSignature::where('project_document_id',$document->id)
            ->where('status','pending')
            ->orderBy('id')
            ->first();

        if ($currentPending->user_id !== $userId) {
            return back()->with('error','It is not your turn to approve yet.');
        }

        DB::transaction(function () use ($document,$currentPending) {

            $currentPending->update([
                'status' => 'signed',
                'signed_at' => now()
            ]);

            $remaining = ProjectDocumentSignature::where('project_document_id',$document->id)
                ->where('status','pending')
                ->exists();

            if (!$remaining) {
                $document->update([
                    'status' => 'approved'
                ]);
            }

        });

        return back()->with('success','Solicitation application approved.');
    }



    public function return(Request $request, Project $project)
    {

        $request->validate([
            'remarks' => ['required','string']
        ]);

        $formType = FormType::where('code','SOLICITATION_APPLICATION')->firstOrFail();

        $document = ProjectDocument::with('signatures')
            ->where('project_id',$project->id)
            ->where('form_type_id',$formType->id)
            ->firstOrFail();

        if ($document->status !== 'submitted') {
            return back()->with('error','This form cannot be returned.');
        }

        DB::transaction(function () use ($document,$request) {

            foreach ($document->signatures as $signature) {
                $signature->update([
                    'status' => 'pending',
                    'signed_at' => null
                ]);
            }

            $document->update([
                'status' => 'draft',
                'remarks' => $request->remarks,
                'returned_by' => auth()->id(),
                'returned_at' => now()
            ]);

        });

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