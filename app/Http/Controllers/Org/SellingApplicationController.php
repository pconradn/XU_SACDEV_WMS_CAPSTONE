<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Models\FormType;
use App\Models\PostponementNoticeData;
use App\Models\CancellationNoticeData;
use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityNoticeController extends BaseProjectDocumentController
{

    /* -----------------------------------------------------------
        CREATE POSTPONEMENT NOTICE
    ------------------------------------------------------------ */

    public function createPostponement(Project $project)
    {
        $document = $this->getDocument($project, 'POSTPONEMENT_NOTICE');

        $data = null;

        if ($document) {
            $data = PostponementNoticeData::where(
                'project_document_id',
                $document->id
            )->first();
        }

        $user = auth()->user();

        $isProjectHead = $this->isProjectHead($project, $user->id);

        $currentSignature = $this->getCurrentSignature($document, $user->id);

        $isReadOnly = $this->computeReadOnly($document, $isProjectHead);

        return view('org.projects.documents.postponement.create', [

            'project' => $project,
            'document' => $document,
            'data' => $data,
            'currentSignature' => $currentSignature,
            'isReadOnly' => $isReadOnly,
            'isProjectHead' => $isProjectHead

        ]);
    }


    /* -----------------------------------------------------------
        CREATE CANCELLATION NOTICE
    ------------------------------------------------------------ */

    public function createCancellation(Project $project)
    {
        $document = $this->getDocument($project, 'CANCELLATION_NOTICE');

        $data = null;

        if ($document) {
            $data = CancellationNoticeData::where(
                'project_document_id',
                $document->id
            )->first();
        }

        $user = auth()->user();

        $isProjectHead = $this->isProjectHead($project, $user->id);

        $currentSignature = $this->getCurrentSignature($document, $user->id);

        $isReadOnly = $this->computeReadOnly($document, $isProjectHead);

        return view('org.projects.documents.cancellation.create', [

            'project' => $project,
            'document' => $document,
            'data' => $data,
            'currentSignature' => $currentSignature,
            'isReadOnly' => $isReadOnly,
            'isProjectHead' => $isProjectHead

        ]);
    }


    /* -----------------------------------------------------------
        STORE POSTPONEMENT NOTICE
    ------------------------------------------------------------ */

    public function storePostponement(Request $request, Project $project)
    {

        $request->validate([

            'reason' => ['nullable','string','max:2000'],

            'new_date' => ['required','date'],

            'new_start_time' => ['required'],
            'new_end_time' => ['required'],

            'venue' => ['required','string','max:255']

        ]);

        $document = $this->getOrCreateDocument($project, 'POSTPONEMENT_NOTICE');

        if ($document->isLocked()) {
            abort(403, 'This notice can no longer be edited.');
        }

        DB::transaction(function () use ($request, $document) {

            PostponementNoticeData::updateOrCreate(

                [
                    'project_document_id' => $document->id
                ],

                [
                    'reason' => $request->reason,
                    'new_date' => $request->new_date,
                    'new_start_time' => $request->new_start_time,
                    'new_end_time' => $request->new_end_time,
                    'venue' => $request->venue
                ]

            );

            $this->resetApprovalsAfterEdit($document);

        });

        $action = $request->input('action');

        if ($action === 'submit') {
            return $this->submitPostponement($project);
        }

        return redirect()
            ->route('org.projects.documents.hub', $project)
            ->with('success','Postponement notice saved as draft.');
    }


    /* -----------------------------------------------------------
        STORE CANCELLATION NOTICE
    ------------------------------------------------------------ */

    public function storeCancellation(Request $request, Project $project)
    {

        $request->validate([
            'reason' => ['required','string','max:2000']
        ]);

        $document = $this->getOrCreateDocument($project, 'CANCELLATION_NOTICE');

        if ($document->isLocked()) {
            abort(403, 'This notice can no longer be edited.');
        }

        DB::transaction(function () use ($request, $document) {

            CancellationNoticeData::updateOrCreate(

                [
                    'project_document_id' => $document->id
                ],

                [
                    'reason' => $request->reason
                ]

            );

            $this->resetApprovalsAfterEdit($document);

        });

        $action = $request->input('action');

        if ($action === 'submit') {
            return $this->submitCancellation($project);
        }

        return redirect()
            ->route('org.projects.documents.hub', $project)
            ->with('success','Cancellation notice saved as draft.');
    }


    /* -----------------------------------------------------------
        RESET APPROVALS AFTER EDIT
    ------------------------------------------------------------ */

    private function resetApprovalsAfterEdit(ProjectDocument $document): void
    {

        if ($document->status === 'draft') {
            return;
        }

        $document->signatures()
            ->whereIn('role',[
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


    /* -----------------------------------------------------------
        SUBMIT POSTPONEMENT
    ------------------------------------------------------------ */

    public function submitPostponement(Project $project)
    {
        $formType = FormType::where('code','POSTPONEMENT_NOTICE')->firstOrFail();

        $document = ProjectDocument::where('project_id',$project->id)
            ->where('form_type_id',$formType->id)
            ->firstOrFail();

        return $this->submitNotice($project,$document);
    }


    /* -----------------------------------------------------------
        SUBMIT CANCELLATION
    ------------------------------------------------------------ */

    public function submitCancellation(Project $project)
    {
        $formType = FormType::where('code','CANCELLATION_NOTICE')->firstOrFail();

        $document = ProjectDocument::where('project_id',$project->id)
            ->where('form_type_id',$formType->id)
            ->firstOrFail();

        return $this->submitNotice($project,$document);
    }


    /* -----------------------------------------------------------
        COMMON SUBMIT LOGIC
    ------------------------------------------------------------ */

    private function submitNotice(Project $project, ProjectDocument $document)
    {

        if ($document->status !== 'draft') {
            return back()->with('error','This notice is already submitted.');
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

        return back()->with('success','Notice submitted successfully.');
    }


    /* -----------------------------------------------------------
        APPROVE NOTICE
    ------------------------------------------------------------ */

    public function approve(Project $project)
    {
        $document = $this->getDocument($project,'POSTPONEMENT_NOTICE')
            ?? $this->getDocument($project,'CANCELLATION_NOTICE');

        if ($document->status !== 'submitted') {
            return back()->with('error','This notice is not awaiting approval.');
        }

        $this->handleApproval($project,$document);

        return back()->with('success','Approval recorded.');
    }


    /* -----------------------------------------------------------
        RETURN NOTICE
    ------------------------------------------------------------ */

    public function return(Request $request, Project $project)
    {

        $request->validate([
            'remarks' => ['required','string']
        ]);

        $document = $this->getDocument($project,'POSTPONEMENT_NOTICE')
            ?? $this->getDocument($project,'CANCELLATION_NOTICE');

        if ($document->status !== 'submitted') {
            return back()->with('error','This notice cannot be returned.');
        }

        $this->handleReturn(
            $project,
            $document,
            $request->remarks
        );

        return back()->with('success','Notice returned for revision.');
    }

}