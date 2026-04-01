<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\FormType;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\ProjectDocumentSignature;
use App\Models\ProjectProposalData;
use App\Models\User;
use App\Notifications\ReregActionNotification;
use App\Support\InAppNotifier;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class BaseProjectDocumentController extends Controller
{


    protected function getOrCreateDocument(Project $project, string $formCode)
    {
        $formType = FormType::where('code', $formCode)->firstOrFail();

        return ProjectDocument::firstOrCreate(
            [
                'project_id'   => $project->id,
                'form_type_id' => $formType->id,
            ],
            [
                'status' => 'draft'
            ]
        );
    }

 
    protected function getDocument(Project $project, string $formCode): ?ProjectDocument
    {
        $formType = FormType::where('code', $formCode)->firstOrFail();

        return ProjectDocument::with('signatures')
            ->where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->first();
    }


 
    protected function getOrgRole(int $userId, int $orgId, int $syId): ?string
    {
        return OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->value('role');
    }


 
    protected function isProjectHead(Project $project, int $userId): bool
    {
        return ProjectAssignment::query()
            ->where('project_id', $project->id)
            ->where('user_id', $userId)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->exists();
    }


   
    protected function getCurrentSignature(?ProjectDocument $document, int $userId)
    {
        if (!$document) {
            return null;
        }

        return $document->signatures
            ->where('user_id', $userId)
            ->first();
    }



    protected function computeReadOnly(?ProjectDocument $document, bool $isProjectHead): bool
    {
        if (!$document) {
            return false;
        }

        if ($document->status === 'draft') {
            return !$isProjectHead;
        }

        if (in_array($document->status, ['submitted','approved'])) {
            return true;
        }

        return false;
    }


    protected function resolveRoleFlags(?string $orgRole): array
    {
        return [
            'isPresident' => $orgRole === 'president',
            'isTreasurer' => $orgRole === 'treasurer',
            'isModerator' => $orgRole === 'moderator',

        ];
    }



    protected function notifyNextApprover(ProjectDocument $document){
        
        $nextRole = $document->nextPendingRole2();
        //dd($nextRole);
        if (!$nextRole || $nextRole === 'sacdev_admin') {
            return;
        }

        $signature = $document->signatures()
            ->where('role', $nextRole)
            ->with('user')
            ->first();

        //dd($signature->user);
        if (!$signature || !$signature->user) {
            return;
        }

        

        InAppNotifier::notifyOnce($signature->user, [
            'title' => 'Document awaiting review',
            'message' => $document->formType->name .
                ' for project "' . $document->project->title . '" requires your approval.',

            'action_url' => $this->resolveOrgDocumentRoute($document),

            'dedupe_key' => 'doc_'.$document->id.'_approval_queue',

            'meta' => [
                'document_id' => $document->id,
                'form_type'   => $document->formType->code,
                'project_id'  => $document->project->id
            ]
        ]);
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

        InAppNotifier::notifyOnce($assignment->user, [
            'title' => 'Project Document Update',
            'message' => $message,

            'action_url' => $this->resolveOrgDocumentRoute($document),

            'dedupe_key' => 'doc_'.$document->id.'_status_update',

            'meta' => [
                'document_id' => $document->id,
                'form_type'   => $document->formType->code,
                'project_id'  => $project->id
            ]
        ]);


    }

    protected function approvalFlow(string $formCode): array
    {
        $flows = [

            'PROJECT_PROPOSAL' => [
                'project_head',
                'treasurer',
                'finance_officer',
                'president',
                'moderator',
                'sacdev_admin'
            ],

            'BUDGET_PROPOSAL' => [
                'project_head',
                'treasurer',
                'finance_officer',
                'president',
                'moderator',
                'sacdev_admin'
            ],

            'OFF_CAMPUS_APPLICATION' => [
                'project_head',
                'president',
                'moderator',
                'sacdev_admin'
            ],

            'SOLICITATION_APPLICATION' => [
                'project_head',
                'president',
                'moderator',
                'sacdev_admin'
            ],

            'SELLING_APPLICATION' => [
                'project_head',
                'president',
                'moderator',
                'sacdev_admin'
            ],

            'REQUEST_TO_PURCHASE' => [
                'project_head',
                'treasurer',
                'president',
                'moderator',
                'sacdev_admin'
            ],

            'FEES_COLLECTION_REPORT' => [
                'project_head',
                'president',
                'moderator',
                'sacdev_admin'
            ],

            'SELLING_ACTIVITY_REPORT' => [
                'project_head',
                'president',
                'moderator',
                'sacdev_admin'
            ],

            'SOLICITATION_SPONSORSHIP_REPORT' => [
                'project_head',
                'president',
                'moderator',
                'sacdev_admin'
            ],
            
            'TICKET_SELLING_REPORT' => [
                'project_head',
                'sacdev_admin',
            ],

            'DOCUMENTATION_REPORT' => [
                'project_head',
                'president',
                'moderator',
                'sacdev_admin'
            ],

            'LIQUIDATION_REPORT' => [
                'project_head',
                'treasurer',
                'finance_officer',
                'president',
                'moderator',
                'sacdev_admin'
            ],

            'POSTPONEMENT_NOTICE' => [
                'project_head',
                'president',
                'moderator',
                'sacdev_admin'
            ],

            'CANCELLATION_NOTICE' => [
                'project_head',
                'president',
                'moderator',
                'sacdev_admin'
            ],
        ];

        return $flows[$formCode] ?? [];
    }

    protected function requiresOffCampus(ProjectProposalData $proposal): bool
    {
        return !empty(trim($proposal->off_campus_venue ?? ''));
    }

    protected function resolveUserByRole(Project $project, string $role): int
    {


        if ($role === 'project_head') {

            $assignment = ProjectAssignment::where('project_id', $project->id)
                ->where('assignment_role', 'project_head')
                ->whereNull('archived_at')
                ->firstOrFail();

            return $assignment->user_id;
        }


        if ($role === 'sacdev_admin') {

            $user = getSacdevApprover($project);

            if (!$user) {
                // fallback to old behavior (VERY SAFE)
                return User::where('system_role', 'sacdev_admin')
                    ->firstOrFail()
                    ->id;
            }

            return $user->id;
        }

        if ($role === 'osa_head') {

            $user = getOsaApprover();

            if (!$user) {
                return User::where('system_role', 'sacdev_admin')
                    ->firstOrFail()
                    ->id;
            }

            return $user->id;
        }


        $member = OrgMembership::where('organization_id', $project->organization_id)
            ->where('school_year_id', $project->school_year_id)
            ->where('role', $role)
            ->whereNull('archived_at')
            ->firstOrFail();

        return $member->user_id;
    }

    protected function createWorkflow(ProjectDocument $document): void
    {
        $flow = $this->approvalFlow($document->formType->code);

        foreach ($flow as $index => $role) {

            $userId = $this->resolveUserByRole($document->project,$role);

            ProjectDocumentSignature::create([
                'project_document_id' => $document->id,
                'user_id' => $userId,
                'role' => $role,
                'status' => $index === 0 ? 'signed' : 'pending',
                'signed_at' => $index === 0 ? now() : null
            ]);

        }
    }


    protected function handleApproval(Project $project, ProjectDocument $document): void
    {
        $userId = auth()->id();

        $signature = $document->signatures()
            ->where('user_id',$userId)
            ->firstOrFail();

        if ($signature->status === 'signed') {
            abort(403,'You have already approved this document.');
        }

        $currentPending = $document->signatures()
            ->where('status','pending')
            ->orderBy('id')
            ->first();

        if ($currentPending->user_id !== $userId) {
            abort(403,'It is not your turn to approve yet.');
        }

        DB::transaction(function () use ($document,$signature,$userId) {

            $oldStatus = $document->status;

            $signature->update([
                'status'=>'signed',
                'signed_at'=>now()
            ]);

            $remaining = $document->signatures()
                ->where('status','pending')
                ->exists();

            if (!$remaining) {
                $document->update([
                    'status'=>'approved'
                ]);
            }

            $document->timelines()->create([
                'user_id' => $userId,
                'action' => 'approved',
                'remarks' => null,
                'old_status' => $oldStatus,
                'new_status' => $document->status,
            ]);

        });

        $document->load('signatures','formType','project');

        if ($document->status === 'approved') {
            $message = $document->formType->name.' has been fully approved.';
        } else {
            $message = $document->formType->name.' was approved by '.auth()->user()->name.'.';
        }

        $this->notifyProjectHead($project, $document, $message);

        $this->notifyNextApprover($document);

        Audit::log(
            'document.approved',
            $document->formType->name.' approved',
            [
                'actor_user_id'=>auth()->id(),
                'organization_id'=>$project->organization_id,
                'school_year_id'=>$project->school_year_id,
                'meta'=>[
                    'document_id'=>$document->id
                ]
            ]
        );
    }

    protected function handleReturn(Project $project, ProjectDocument $document, string $remarks): void
    {
        DB::transaction(function () use ($document,$remarks){

            $oldStatus = $document->status;

            foreach ($document->signatures as $signature) {

                $signature->update([
                    'status'=>'pending',
                    'signed_at'=>null
                ]);

            }

            $document->update([
                'status'=>'draft',
                'remarks'=>$remarks,
                'returned_by'=>auth()->id(),
                'returned_at'=>now()
            ]);

            $document->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'returned',
                'remarks' => $remarks,
                'old_status' => $oldStatus,
                'new_status' => 'draft',
            ]);

        });

        $this->notifyProjectHead(
            $project,
            $document,
            'Your '.$document->formType->name.' was returned for revision by '.auth()->user()->name.'.'
        );

        Audit::log(
            'document.returned',
            $document->formType->name.' returned for revision',
            [
                'actor_user_id'=>auth()->id(),
                'organization_id'=>$project->organization_id,
                'school_year_id'=>$project->school_year_id,
                'meta'=>[
                    'document_id'=>$document->id
                ]
            ]
        );
    }






    public function retract(Project $project, $formCode)
    {
        $formType = FormType::where('code', $formCode)->firstOrFail();

        $document = ProjectDocument::with('signatures')
            ->where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'submitted') {
            return back()->with('error', 'Only submitted documents can be modified.');
        }

        $userId = auth()->id();

        $signatures = $document->signatures->sortBy('id')->values();

        $currentIndex = $signatures->search(fn($sig) => $sig->user_id === $userId);

        if ($currentIndex === false) {
            return back()->with('error', 'You are not part of this approval workflow.');
        }

        $currentSignature = $signatures[$currentIndex];

        if ($currentSignature->status !== 'signed') {
            return back()->with('error', 'You have not approved this document yet.');
        }

        $hasLaterApproval = $signatures
            ->slice($currentIndex + 1)
            ->contains(fn($sig) => $sig->status === 'signed');

        if ($hasLaterApproval) {
            return back()->with('error', 'Cannot retract. A later approver has already approved.');
        }

        DB::transaction(function () use ($document, $userId) {

            $document->signatures()
                ->where('user_id', $userId)
                ->update([
                    'status' => 'pending',
                    'signed_at' => null
                ]);

            $document->timelines()->create([
                'user_id' => $userId,
                'action' => 'signature_retracted',
                'remarks' => null,
                'old_status' => 'submitted',
                'new_status' => 'submitted',
            ]);
        });

        return back()->with('success', 'Your approval has been retracted.');
    }

    

    protected function handleRequestEdit(Project $project, ProjectDocument $document, string $remarks): void
    {
        if ($document->status !== 'approved_by_sacdev') {
            abort(403, 'Only SACDEV-approved documents can request edit.');
        }

        $document->update([
            'edit_requested' => true,
            'edit_requested_at' => now(),
            'edit_requested_by' => auth()->id(),
            'edit_request_remarks' => $remarks,
        ]);

        $document->timelines()->create([
            'user_id' => auth()->id(),
            'action' => 'edit_requested',
            'remarks' => $remarks,
            'old_status' => $document->status,
            'new_status' => $document->status,
        ]);
    }



    protected function handleRequestSubmit(Project $project, ProjectDocument $document): void
    {
        $oldStatus = $document->status;
        //dd($document);

        DB::transaction(function () use ($document, $project, $oldStatus) {

            

            if ($document->edit_mode && !$document->edit_requires_full_approval) {

                //dd($document);

                $document->signatures()
                    ->where('role', 'sacdev_admin')
                    ->update([
                        'status' => 'pending',
                        'signed_at' => null,
                    ]);

            } else {

                $document->signatures()->delete();
                $this->createWorkflow($document);
            }

            $document->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'remarks' => null,
                'edit_mode' => false,
            ]);

            $document->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'submitted',
                'remarks' => null,
                'old_status' => $oldStatus,
                'new_status' => 'submitted',
            ]);
        });

        $this->notifyNextApprover($document);
    }

    protected function handleRevertApproval(Project $project, ProjectDocument $document, string $remarks): void
    {
        if ($document->status !== 'approved') {
            abort(403, 'Only approved documents can be reverted.');
        }

        DB::transaction(function () use ($document, $remarks) {

            $oldStatus = $document->status;

            $sacdevSignature = $document->signatures()
                ->where('role', 'sacdev_admin')
                ->first();

            if ($sacdevSignature) {
                $sacdevSignature->update([
                    'status' => 'pending',
                    'signed_at' => null
                ]);
            }

            $document->update([
                'status' => 'submitted',
                'remarks' => $remarks,
            ]);

            $document->timelines()->create([
                'user_id' => auth()->id(),
                'action' => 'approval_reverted',
                'remarks' => $remarks,
                'old_status' => $oldStatus,
                'new_status' => 'submitted',
            ]);
        });

        $this->notifyProjectHead(
            $project,
            $document,
            $document->formType->name.' approval was reverted. It is now pending SACDEV review again.'
        );
    }


    protected function resolveOrgDocumentRoute(ProjectDocument $document): string
    {
        $map = [
            'PROJECT_PROPOSAL' => 'org.projects.documents.project-proposal.create',
            'BUDGET_PROPOSAL' => 'org.projects.documents.budget-proposal.create',
            'OFF_CAMPUS_APPLICATION' => 'org.projects.documents.off-campus.travel-form.create',

            'SOLICITATION_APPLICATION' => 'org.projects.documents.solicitation.create',
            'SELLING_APPLICATION' => 'org.projects.documents.selling.create',
            'REQUEST_TO_PURCHASE' => 'org.projects.documents.request-to-purchase.create',

            'FEES_COLLECTION_REPORT' => 'org.projects.documents.fees-collection.create',
            'SELLING_ACTIVITY_REPORT' => 'org.projects.documents.selling-report.create',
            'SOLICITATION_SPONSORSHIP_REPORT' => 'org.projects.documents.solicitation-report.create',
            'TICKET_SELLING_REPORT' => 'org.projects.documents.ticket-selling.create',

            'DOCUMENTATION_REPORT' => 'org.projects.documents.documentation.create',
            'LIQUIDATION_REPORT' => 'org.projects.documents.liquidation.create',

            'POSTPONEMENT_NOTICE' => 'org.projects.documents.postponement.create',
            'CANCELLATION_NOTICE' => 'org.projects.documents.cancellation.create',
        ];

        $routeName = $map[$document->formType->code] ?? 'org.projects.documents.hub';

        return route($routeName, $document->project);
    }

}