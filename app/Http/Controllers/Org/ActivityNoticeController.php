<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Models\CancellationNoticeData;
use App\Models\FormType;
use App\Models\OrgMembership;
use App\Models\PostponementNoticeData;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use App\Models\User;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityNoticeController extends BaseProjectDocumentController

{

    public function createPostponement(Project $project)
    {

        $formType = FormType::where('code','POSTPONEMENT_NOTICE')->firstOrFail();

        $existing = ProjectDocument::where('project_id',$project->id)
            ->where('form_type_id',$formType->id)
            ->whereNull('archived_at')
            ->whereNotIn('status',['approved_by_sacdev'])
            ->exists();

        if($existing){
            return back()->with('error','A postponement notice already exists and is still pending approval.');
        }

        $document = ProjectDocument::create([
            'project_id' => $project->id,
            'form_type_id' => $formType->id,
            'created_by_user_id' => auth()->id(),
            'status' => 'draft'
            
        ]);

        return redirect()->route(
            'org.projects.documents.postponement.edit',
            [$project,$document]
        );
    }


    public function createCancellation(Project $project)
    {

        $formType = FormType::where('code','CANCELLATION_NOTICE')->firstOrFail();

        $existing = ProjectDocument::where('project_id',$project->id)
            ->where('form_type_id',$formType->id)
            ->whereNull('archived_at')
            ->whereNotIn('status',['approved_by_sacdev'])
            ->exists();

        if($existing){
            return back()->with('error','A cancellation notice already exists and is still pending approval.');
        }

        $document = ProjectDocument::create([
            'project_id' => $project->id,
            'form_type_id' => $formType->id,
            'created_by_user_id' => auth()->id(),
            'status' => 'draft'
        ]);

        return redirect()->route(
            'org.projects.documents.cancellation.edit',
            [$project,$document]
        );
    }


    public function editPostponement(Project $project, ProjectDocument $document)
    {
        if ($document->project_id !== $project->id) {
            abort(404);
        }

        if (!in_array($document->status, ['draft', 'returned', 'submitted','Approved'])) {
            return back()->with('error', 'This notice can no longer be edited.');
        }

        $data = PostponementNoticeData::where(
            'project_document_id',
            $document->id
        )->first();

        $isProjectHead = ProjectAssignment::where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->where('user_id', auth()->id())
            ->whereNull('archived_at')
            ->exists();

        $currentSignature = $document->signatures()
            ->where('user_id', auth()->id())
            ->first();

        $isReadOnly = !$isProjectHead || $document->status === 'submitted';

        return view(
            'org.projects.documents.postponement.create',
            compact(
                'project',
                'document',
                'data',
                'isProjectHead',
                'currentSignature',
                'isReadOnly'
            )
        );
    }



    public function editCancellation(Project $project, ProjectDocument $document)
    {
        if ($document->project_id !== $project->id) {
            abort(404);
        }

        if (!in_array($document->status, ['draft', 'returned', 'submitted', 'Approved'])) {
            return back()->with('error', 'This notice can no longer be edited.');
        }

        $data = CancellationNoticeData::where(
            'project_document_id',
            $document->id
        )->first();

        $isProjectHead = ProjectAssignment::where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->where('user_id', auth()->id())
            ->whereNull('archived_at')
            ->exists();

        $currentSignature = $document->signatures()
            ->where('user_id', auth()->id())
            ->first();

        $isReadOnly = !$isProjectHead || $document->status === 'submitted';

        return view(
            'org.projects.documents.cancellation.create',
            compact(
                'project',
                'document',
                'data',
                'isProjectHead',
                'currentSignature',
                'isReadOnly'
            )
        );
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




    public function storePostponement(Request $request, Project $project, ProjectDocument $document)
    {
        if(!in_array($document->status,['draft','returned','submitted'])){
            return back()->with('error','This notice can no longer be modified.');
        }

        $data = $request->validate([
            'reason' => 'nullable|string|max:2000',
            'new_date' => 'required|date',
            'new_start_time' => ['required','regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'new_end_time'   => ['required','regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'venue' => 'required|string|max:255'
        ]);

        DB::transaction(function() use ($data,$document,$request,$project) {

            PostponementNoticeData::updateOrCreate(
                ['project_document_id'=>$document->id],
                $data
            );

            if (!$document->edit_mode) {
                $this->resetApprovalsAfterEdit($document);
            }

            $action = $request->action;

            if ($document && $document->edit_mode) {
                $action = 'submit';
            }

            if ($action === 'submit') {

                $this->submitDocument($project,$document);

                Audit::log(
                    'document.submitted',
                    'Postponement notice submitted',
                    [
                        'actor_user_id' => auth()->id(),
                        'organization_id' => $project->organization_id,
                        'school_year_id' => $project->school_year_id,
                        'meta' => [
                            'document_id' => $document->id,
                            'form_type' => 'postponement_notice'
                        ]
                    ]
                );
            }

        });

        if (($request->action === 'submit') || ($document && $document->edit_mode)) {
            return back()->with('success','Postponement notice submitted successfully.');
        }

        return back()->with('success','Postponement notice saved.');
    }

    public function storeCancellation(Request $request, Project $project, ProjectDocument $document)
    {
        if(!in_array($document->status,['draft','returned','submitted'])){
            return back()->with('error','This notice can no longer be modified.');
        }

        $data = $request->validate([
            'reason' => 'required|string|max:2000'
        ]);

        DB::transaction(function() use ($data,$document,$request,$project) {

            CancellationNoticeData::updateOrCreate(
                ['project_document_id'=>$document->id],
                $data
            );

            if (!$document->edit_mode) {
                $this->resetApprovalsAfterEdit($document);
            }

            $action = $request->action;

            if ($document && $document->edit_mode) {
                $action = 'submit';
            }

            if ($action === 'submit') {

                $this->submitDocument($project,$document);

                Audit::log(
                    'document.submitted',
                    'Cancellation notice submitted',
                    [
                        'actor_user_id' => auth()->id(),
                        'organization_id' => $project->organization_id,
                        'school_year_id' => $project->school_year_id,
                        'meta' => [
                            'document_id' => $document->id,
                            'form_type' => 'cancellation_notice'
                        ]
                    ]
                );
            }

        });

        if (($request->action === 'submit') || ($document && $document->edit_mode)) {
            return back()->with('success','Cancellation notice submitted successfully.');
        }

        return back()->with('success','Cancellation notice saved.');
    }



    public function archive(Project $project, ProjectDocument $document)
    {

        if($document->status !== 'draft'){
            return back()->with('error','Only draft notices can be removed.');
        }

        $document->update([
            'archived_at'=>now()
        ]);

        return back()->with('success','Notice removed.');
    }



    public function approve(Project $project, ProjectDocument $document)
    {

        if ($document->status !== 'submitted') {
            return back()->with('error','This document is not awaiting approval.');
        }

        $this->handleApproval($project, $document);

        return back()->with('success','Approval recorded.');

    }



    public function return(Request $request, Project $project, ProjectDocument $document)
    {

        $request->validate([
            'remarks' => ['required','string','max:2000']
        ]);

        if ($document->status !== 'submitted') {
            return back()->with('error','This document cannot be returned.');
        }

        $this->handleReturn(
            $project,
            $document,
            $request->remarks
        );

        return back()->with('success','Notice returned for revision.');

    }




    protected function submitDocument(Project $project, ProjectDocument $document)
    {
        if ($document->status !== 'draft' && !$document->edit_mode) {
            abort(403, 'This form cannot be submitted.');
        }

        $this->handleRequestSubmit($project, $document);
    }



    protected function createSignature($documentId,$userId,$role,$status='pending')
    {

        DB::table('project_document_signatures')->insert([

            'project_document_id'=>$documentId,
            'user_id'=>$userId,
            'role'=>$role,
            'status'=>$status,
            'created_at'=>now(),
            'updated_at'=>now()

        ]);

    }

}