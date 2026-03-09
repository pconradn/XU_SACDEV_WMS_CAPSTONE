<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\FormType;
use App\Models\OrgMembership;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\ProjectDocumentSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\ReregActionNotification;
use App\Support\Audit;

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


    /**
     * Resolve role flags
     */
    protected function resolveRoleFlags(?string $orgRole): array
    {
        return [
            'isPresident' => $orgRole === 'president',
            'isTreasurer' => $orgRole === 'treasurer',
            'isModerator' => $orgRole === 'moderator',
        ];
    }



protected function notifyNextApprover(ProjectDocument $document){
    $nextRole = $document->nextPendingRole();

    if (!$nextRole || $nextRole === 'sacdev_admin') {
        return;
    }

    $signature = $document->signatures()
        ->where('role', $nextRole)
        ->with('user')
        ->first();

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






}