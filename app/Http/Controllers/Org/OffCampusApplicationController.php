<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Models\FormType;
use App\Models\OffCampusActivityData;
use App\Models\OffCampusGuidelineAck;
use App\Models\OffCampusParticipant;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\ProjectDocumentSignature;
use App\Models\SchoolYear;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OffCampusApplicationController extends BaseProjectDocumentController
{

    public function create(Project $project)
    {
        $document = $this->getDocument($project, 'OFF_CAMPUS_APPLICATION');

        $activity = null;
        $participants = collect();

        if ($document) {

            $activity = \App\Models\OffCampusActivityData::with('participants')
                ->where('project_document_id', $document->id)
                ->first();

            if ($activity) {
                $participants = $activity->participants;
            }

        }

        $user = auth()->user();

        $orgId = session('active_org_id');
        $syId  = session('encode_sy_id');

        $orgRole = $this->getOrgRole($user->id, $orgId, $syId);

        $roles = $this->resolveRoleFlags($orgRole);

        $isProjectHead = $this->isProjectHead($project, $user->id);

        $currentSignature = $this->getCurrentSignature($document, $user->id);

        $isReadOnly = $this->computeReadOnly($document, $isProjectHead);

        return view('org.projects.documents.off-campus.create', [
            'project'          => $project,
            'document'         => $document,
            'activity'         => $activity,
            'participants'     => $participants,
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
            'inclusive_dates' => ['nullable','string','max:255'],
            'venue_destination' => ['required','string','max:255'],
            'remarks' => ['nullable','string'],
            'participants.*.student_name' => ['nullable','string','max:255'],
            'participants.*.course_year' => ['nullable','string','max:255'],
            'participants.*.student_mobile' => ['nullable','string','max:30'],
            'participants.*.parent_name' => ['nullable','string','max:255'],
            'participants.*.parent_mobile' => ['nullable','string','max:30'],

        ]);


        $document = $this->getOrCreateDocument($project, 'OFF_CAMPUS_APPLICATION');

        if ($document->isLocked()) {
            abort(403, 'This document is already approved and cannot be edited.');
        }

        DB::transaction(function () use ($request, $document) {

            $activity = OffCampusActivityData::updateOrCreate(
                [
                    'project_document_id' => $document->id
                ],
                [
                    'activity_name' => $request->activity_name,
                    'inclusive_dates' => $request->inclusive_dates,
                    'venue_destination' => $request->venue_destination,
                    'remarks' => $request->remarks
                ]
            );

            $this->saveParticipants($activity, $request->participants ?? []);

            $this->resetApprovalsAfterEdit($document);
        });

        $action = $request->input('action');

        if ($action === 'submit') {
            return $this->submit($project);
        }

        return redirect()
            ->route('org.projects.documents.hub', $project)
            ->with('success', 'Off-campus form saved as draft.');
    }


    private function saveParticipants(OffCampusActivityData $activity, array $participants): void
    {

        $activity->participants()->delete();

        foreach ($participants as $p) {

            if (empty($p['student_name'])) {
                continue;
            }

            $activity->participants()->create([
                'student_name' => $p['student_name'],
                'course_year' => $p['course_year'] ?? null,
                'student_mobile' => $p['student_mobile'] ?? null,
                'parent_name' => $p['parent_name'] ?? null,
                'parent_mobile' => $p['parent_mobile'] ?? null,
            ]);
        }
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
                'sacdev_admin',
            ])
            ->delete();

        $document->update([
            'status' => 'draft',
            'submitted_at' => null,
        ]);
    }


    public function submit(Project $project)
    {
        $formType = FormType::where('code', 'OFF_CAMPUS_APPLICATION')->firstOrFail();

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


            $admin = User::where('system_role', 'sacdev_admin')->firstOrFail();

            $this->createSignature(
                $document->id,
                $admin->id,
                'sacdev_admin'
            );

        });

        return back()->with('success', 'Off-campus form submitted successfully.');
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


    public function guidelines(Project $project)
    {
        $document = $this->getOrCreateDocument($project, 'OFF_CAMPUS_APPLICATION');

        $user = auth()->user();

        if (!$this->isProjectHead($project, $user->id)) {
            return redirect()->route(
                'org.projects.off-campus.create',
                $project
            );
        }

        $ack = OffCampusGuidelineAck::where('project_document_id', $document->id)
            ->where('user_id', $user->id)
            ->first();

        if ($ack) {
            return redirect()->route(
                'org.projects.off-campus.create',
                $project
            );
        }

        return view('org.projects.documents.off-campus.guidelines', [
            'project' => $project,
            'document' => $document
        ]);
    }



    public function acknowledgeGuidelines(Request $request, Project $project)
    {
        $document = $this->getOrCreateDocument($project, 'OFF_CAMPUS_APPLICATION');

        $request->validate([
            'student_id' => ['required']
        ]);

        $user = auth()->user();

        $studentId = Str::before($user->email, '@');

        if ($request->student_id !== $studentId) {
            return back()->withErrors([
                'student_id' => 'Student ID does not match your account.'
            ]);
        }

        OffCampusGuidelineAck::firstOrCreate([
            'project_document_id' => $document->id,
            'user_id' => $user->id
        ],[
            'confirmed_at' => now()
        ]);

        return redirect()->route(
            'org.projects.off-campus.create',
            $project
        );
    }


    public function approve(Project $project)
    {
        $formType = FormType::where('code', 'OFF_CAMPUS_APPLICATION')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'submitted') {
            return back()->with('error', 'This off-campus form is not awaiting approval.');
        }

        $userId = auth()->id();

        $userSignature = ProjectDocumentSignature::where('project_document_id', $document->id)
            ->where('user_id', $userId)
            ->first();

        if (!$userSignature) {
            return back()->with('error', 'You are not part of the approval workflow.');
        }

        if ($userSignature->status === 'signed') {
            return back()->with('error', 'You have already approved this off-campus form.');
        }

        $currentPending = ProjectDocumentSignature::where('project_document_id', $document->id)
            ->where('status', 'pending')
            ->orderBy('id')
            ->first();

        if (!$currentPending) {
            return back()->with('error', 'No pending approvals remain.');
        }

        if ($currentPending->user_id !== $userId) {
            return back()->with('error', 'It is not your turn to approve yet.');
        }

        DB::transaction(function () use ($document, $currentPending) {

            $currentPending->update([
                'status' => 'signed',
                'signed_at' => now(),
            ]);

            $remaining = ProjectDocumentSignature::where('project_document_id', $document->id)
                ->where('status', 'pending')
                ->exists();

            if (!$remaining) {
                $document->update([
                    'status' => 'approved',
                ]);
            }
        });

        return back()->with('success', 'Off-campus form approved successfully.');
    }

    public function return(Request $request, Project $project)
    {
        $request->validate([
            'remarks' => ['required', 'string'],
        ]);

        $formType = FormType::where('code', 'OFF_CAMPUS_APPLICATION')->firstOrFail();

        $document = ProjectDocument::with('signatures')
            ->where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'submitted') {
            return back()->with('error', 'This off-campus form cannot be returned.');
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

        return back()->with('success', 'Off-campus form returned for revision.');
    }


}