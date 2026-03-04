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


    public function approve(Project $project)
    {
        $formType = FormType::where('code', 'budget_proposal')->firstOrFail();

        $document = ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'submitted') {
            return back()->with('error', 'This budget proposal is not awaiting approval.');
        }

        $userId = auth()->id();

        $userSignature = ProjectDocumentSignature::query()
            ->where('project_document_id', $document->id)
            ->where('user_id', $userId)
            ->first();

        if (!$userSignature) {
            return back()->with('error', 'You are not part of the approval workflow.');
        }

        if ($userSignature->status === 'signed') {
            return back()->with('error', 'You have already approved this budget proposal.');
        }


        $currentPending = ProjectDocumentSignature::query()
            ->where('project_document_id', $document->id)
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

            $remaining = ProjectDocumentSignature::query()
                ->where('project_document_id', $document->id)
                ->where('status', 'pending')
                ->exists();

            if (!$remaining) {
                $document->update([
                    'status' => 'approved',
                ]);
            }
        });

        return back()->with('success', 'Budget proposal approved successfully.');
    }

    public function return(Request $request, Project $project)
    {
        $request->validate([
            'remarks' => ['required', 'string'],
        ]);

        $formType = FormType::where('code', 'budget_proposal')->firstOrFail();

        $document = ProjectDocument::with('signatures')
            ->where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->firstOrFail();

        if ($document->status !== 'submitted') {
            return back()->with('error', 'This budget proposal cannot be returned.');
        }

        DB::transaction(function () use ($document) {

            $document->signatures()
                ->where('role', '!=', 'project_head')
                ->delete();

            $document->update([
                'status' => 'returned',
            ]);
        });

        return back()->with('success', 'Budget proposal returned for revision.');
    }




}