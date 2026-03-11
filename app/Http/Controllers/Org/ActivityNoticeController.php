<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\Models\FormType;
use App\Models\ProjectDocument;

use App\Models\PostponementNoticeData;
use App\Models\CancellationNoticeData;

use App\Models\ProjectAssignment;
use App\Models\OrgMembership;
use App\Models\User;

class ActivityNoticeController extends Controller
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
            'org.projects.postponement.edit',
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
            'org.projects.cancellation.edit',
            [$project,$document]
        );
    }


    public function editPostponement(Project $project, ProjectDocument $document)
    {
        if ($document->project_id !== $project->id) {
            abort(404);
        }

        if (!in_array($document->status, ['draft', 'returned', 'submitted'])) {
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

        if (!in_array($document->status, ['draft', 'returned', 'submitted'])) {
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



    /* -----------------------------------------------------------
        STORE POSTPONEMENT
    ------------------------------------------------------------ */

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

            $this->resetApprovalsAfterEdit($document);

            if ($request->action === 'submit') {
                $this->submitDocument($project,$document);
            }

        });

        return back()->with('success','Postponement notice saved.');
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



    /* -----------------------------------------------------------
        STORE CANCELLATION
    ------------------------------------------------------------ */

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

            $this->resetApprovalsAfterEdit($document);

            if ($request->action === 'submit') {
                $this->submitDocument($project,$document);
            }

        });

        return back()->with('success','Cancellation notice saved.');
    }


    /* -----------------------------------------------------------
        ARCHIVE NOTICE
    ------------------------------------------------------------ */

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


    /* -----------------------------------------------------------
        APPROVE DOCUMENT
    ------------------------------------------------------------ */

    public function approve(Project $project, ProjectDocument $document)
    {

        $signature = $document->signatures()
            ->where('user_id',auth()->id())
            ->where('status','pending')
            ->firstOrFail();

        $signature->update([
            'status'=>'signed',
            'signed_at'=>now()
        ]);

        if (!$document->nextPendingRole()) {

            $document->update([
                'status'=>'approved_by_sacdev',
                'reviewed_at'=>now(),
                'reviewed_by_user_id'=>auth()->id()
            ]);

        }

        return back()->with('success','Document approved.');
    }


    /* -----------------------------------------------------------
        RETURN DOCUMENT
    ------------------------------------------------------------ */

    public function return(Request $request, Project $project, ProjectDocument $document)
    {

        $data = $request->validate([
            'remarks'=>'required|string|max:2000'
        ]);

        $document->update([
            'status'=>'returned',
            'remarks'=>$data['remarks'],
            'returned_by'=>auth()->id(),
            'returned_at'=>now()
        ]);

        return back()->with('success','Document returned for revision.');
    }


    /* -----------------------------------------------------------
        SUBMIT DOCUMENT
    ------------------------------------------------------------ */

    protected function submitDocument(Project $project, ProjectDocument $document)
    {

        $document->update([
            'status'=>'submitted',
            'submitted_at'=>now(),
            'remarks'=>null,
            'returned_by'=>null,
            'returned_at'=>null
        ]);

        $document->signatures()->delete();


        $projectHead = ProjectAssignment::where('project_id',$project->id)
            ->where('assignment_role','project_head')
            ->whereNull('archived_at')
            ->firstOrFail();


        $this->createSignature(
            $document->id,
            $projectHead->user_id,
            'project_head',
            'signed'
        );


        $president = OrgMembership::where('organization_id',$project->organization_id)
            ->where('school_year_id',$project->school_year_id)
            ->where('role','president')
            ->whereNull('archived_at')
            ->firstOrFail();


        $this->createSignature(
            $document->id,
            $president->user_id,
            'president'
        );


        $moderator = OrgMembership::where('organization_id',$project->organization_id)
            ->where('school_year_id',$project->school_year_id)
            ->where('role','moderator')
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
    }


    /* -----------------------------------------------------------
        CREATE SIGNATURE
    ------------------------------------------------------------ */

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