<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Documents\BaseProjectDocumentController;
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

class ProjectDocumentActionController extends Controller
{




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

    

    public function requestEdit(Project $project, string $formCode, Request $request)
    {
        $request->validate([
            'remarks' => 'required|string|max:2000',
        ]);

        $document = ProjectDocument::where('project_id', $project->id)
            ->whereHas('formType', fn ($q) => $q->where('code', $formCode))
            ->firstOrFail();

        $document->update([
            'edit_requested' => true,
            'edit_requested_at' => now(),
            'edit_requested_by' => auth()->id(),
            'edit_request_remarks' => $request->remarks,
        ]);

        $document->timelines()->create([
            'user_id' => auth()->id(),
            'action' => 'edit_requested',
            'remarks' => $request->remarks,
            'old_status' => $document->status,
            'new_status' => $document->status,
        ]);

        return back()->with('success', 'Edit request submitted successfully.');
    }



    protected function handleRequestSubmit(Project $project, ProjectDocument $document): void
    {
        $oldStatus = $document->status;

        DB::transaction(function () use ($document, $project, $oldStatus) {

            if ($document->edit_mode && !$document->edit_requires_full_approval) {

                $document->signatures()->delete();

                $sacdevId = $this->resolveUserByRole($project, 'sacdev_admin');

                ProjectDocumentSignature::create([
                    'project_document_id' => $document->id,
                    'user_id' => $sacdevId,
                    'role' => 'sacdev_admin',
                    'status' => 'pending'
                ]);

            } else {

                $document->signatures()->delete();
                $this->createWorkflow($document);
            }

            $document->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'edit_mode' => false
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
            'SACDEV approval was reverted. Document is now pending SACDEV approval again.'
        );
    }

}
