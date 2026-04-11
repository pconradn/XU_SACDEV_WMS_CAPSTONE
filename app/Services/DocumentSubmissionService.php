<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\FormType;
use App\Models\ProjectDocumentSignature;
use Illuminate\Support\Facades\DB;
use App\Support\Audit;

class DocumentSubmissionService
{
    public function handleRequestSubmit(Project $project, ProjectDocument $document): void
    {
        $oldStatus = $document->status;

        $this->ensureProjectEditable($project);

        DB::transaction(function () use ($document, $project, $oldStatus) {

            if ($document->edit_mode && !$document->edit_requires_full_approval) {

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

        Audit::log(
            'document.submitted',
            $document->formType->name . ' submitted',
            [
                'actor_user_id' => auth()->id(),
                'organization_id' => $project->organization_id,
                'school_year_id' => $project->school_year_id,
                'meta' => [
                    'document_id' => $document->id
                ]
            ]
        );
    }

    protected function ensureProjectEditable(Project $project): void
    {
        $status = strtolower((string) ($project->status ?? ''));
        $workflow = strtolower((string) ($project->workflow_status ?? ''));

        if (
            in_array($status, ['cancelled', 'canceled', 'completed'], true) ||
            in_array($workflow, ['cancelled', 'canceled', 'completed'], true)
        ) {
            abort(403, 'This project is locked. No further submissions are allowed.');
        }
    }

    protected function createWorkflow(ProjectDocument $document): void
    {
        $flow = $this->approvalFlow($document->formType->code);

        if ($document->signatures()->exists()) {
            $document->signatures()->delete();
        }

        foreach ($flow as $index => $role) {

            $userId = $this->resolveUserByRole($document->project, $role);

            ProjectDocumentSignature::create([
                'project_document_id' => $document->id,
                'user_id' => $userId,
                'role' => $role,
                'status' => $index === 0 ? 'signed' : 'pending',
                'signed_at' => $index === 0 ? now() : null
            ]);
        }
    }

    protected function approvalFlow(string $formCode): array
    {
        return [
            'project_head',
            'treasurer',
            'president',
            'moderator',
            'sacdev_admin'
        ];
    }

    protected function resolveUserByRole(Project $project, string $role): int
    {
        if ($role === 'project_head') {
            return $project->assignments()
                ->where('assignment_role', 'project_head')
                ->firstOrFail()
                ->user_id;
        }

        if ($role === 'sacdev_admin') {
            return \DB::table('cluster_user')
                ->join('users', 'users.id', '=', 'cluster_user.user_id')
                ->where('cluster_user.cluster_id', $project->organization->cluster_id)
                ->where('users.system_role', 'sacdev_admin')
                ->value('users.id');
        }

        return \App\Models\OrgMembership::where('organization_id', $project->organization_id)
            ->where('school_year_id', $project->school_year_id)
            ->where('role', $role)
            ->value('user_id');
    }

    protected function notifyNextApprover(ProjectDocument $document): void
    {
        $next = $document->signatures()
            ->where('status', 'pending')
            ->orderBy('id')
            ->with('user')
            ->first();

        if (!$next || !$next->user) return;

        \App\Support\InAppNotifier::notifyOnce($next->user, [
            'title' => 'Document awaiting review',
            'message' => $document->formType->name . ' requires your approval.',
            'route' => route('org.projects.documents.hub', $document->project),
            'dedupe_key' => 'doc_'.$document->id.'_approval',
        ]);
    }
}