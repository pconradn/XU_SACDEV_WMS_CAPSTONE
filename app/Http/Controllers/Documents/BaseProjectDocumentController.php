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

        

        $signature->user->notify(new ReregActionNotification([
            'title' => 'Document awaiting review',
            'message' => $document->formType->name .
                ' for project "' . $document->project->title . '" requires your approval.',
            'action_url' => route('org.projects.documents.hub', $document->project),
            'meta' => [
                'document_id' => $document->id,
                'form_type'   => $document->formType->code,
                'project_id'  => $document->project->id
            ]
        ]));
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

    protected function approvalFlow(string $formCode): array
    {
        $flows = [

            'PROJECT_PROPOSAL' => [
                'project_head',
                'auditor',
                'treasurer',
                'president',
                'moderator',
                'sacdev_admin'
            ],

            'BUDGET_PROPOSAL' => [
                'project_head',
                'treasurer',
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
                'auditor',
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

            $assignment = ProjectAssignment::where('project_id',$project->id)
                ->where('assignment_role','project_head')
                ->whereNull('archived_at')
                ->firstOrFail();

            return $assignment->user_id;
        }

        if ($role === 'sacdev_admin') {

            return User::where('system_role','sacdev_admin')
                ->firstOrFail()
                ->id;
        }

        $member = OrgMembership::where('organization_id',$project->organization_id)
            ->where('school_year_id',$project->school_year_id)
            ->where('role',$role)
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

        DB::transaction(function () use ($document,$signature) {

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

        });

        $document->load('signatures','formType','project');

        $this->notifyProjectHead(
            $project,
            $document,
            auth()->user()->name.' approved the '.$document->formType->name.'.'
        );

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

        });

        $this->notifyProjectHead(
            $project,
            $document,
            'Your '.$document->formType->name.' was returned for revision by'
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





}