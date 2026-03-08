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

        $document = ProjectDocument::create([
            'project_id' => $project->id,
            'form_type_id' => $formType->id,
            'created_by_user_id' => auth()->id(),
            'status' => 'draft'
        ]);

        return redirect()->route(
            'org.projects.activity-notices.postponement.edit',
            [$project,$document]
        );
    }

    public function createCancellation(Project $project)
    {

        $formType = FormType::where('code','CANCELLATION_NOTICE')->firstOrFail();

        $document = ProjectDocument::create([
            'project_id' => $project->id,
            'form_type_id' => $formType->id,
            'created_by_user_id' => auth()->id(),
            'status' => 'draft'
        ]);

        return redirect()->route(
            'org.projects.activity-notices.cancellation.edit',
            [$project,$document]
        );
    }

    public function editPostponement(Project $project, ProjectDocument $document)
    {

        $data = PostponementNoticeData::where(
            'project_document_id',
            $document->id
        )->first();

        return view(
            'org.projects.documents.postponement.create',
            compact('project','document','data')
        );
    }

    public function editCancellation(Project $project, ProjectDocument $document)
    {

        $data = CancellationNoticeData::where(
            'project_document_id',
            $document->id
        )->first();

        return view(
            'org.projects.documents.cancellation.create',
            compact('project','document','data')
        );
    }





    public function storePostponement(Request $request, Project $project, ProjectDocument $document)
    {

        $data = $request->validate([

            'reason' => 'nullable|string',

            'new_date' => 'required|date',

            'new_start_time' => 'required',
            'new_end_time' => 'required',

            'venue' => 'required|string'

        ]);

        DB::transaction(function() use ($data,$document,$request,$project) {

            $notice = PostponementNoticeData::updateOrCreate(

                ['project_document_id'=>$document->id],

                $data

            );


            if ($request->action === 'submit') {

                $this->submitDocument($project,$document);

            }

        });

        return back()->with('success','Postponement notice saved.');
    }

    public function storeCancellation(Request $request, Project $project, ProjectDocument $document)
    {

        $data = $request->validate([

            'reason' => 'required|string'

        ]);

        DB::transaction(function() use ($data,$document,$request,$project) {

            CancellationNoticeData::updateOrCreate(

                ['project_document_id'=>$document->id],

                $data

            );


            if ($request->action === 'submit') {

                $this->submitDocument($project,$document);

            }

        });

        return back()->with('success','Cancellation notice saved.');
    }

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
                'status'=>'approved',
                'reviewed_at'=>now(),
                'reviewed_by_user_id'=>auth()->id()
            ]);

        }

        return back()->with('success','Document approved.');
    }

    public function return(Request $request, Project $project, ProjectDocument $document)
    {

        $data = $request->validate([
            'remarks'=>'required|string'

        ]);


        $document->update([
            'status'=>'returned',
            'remarks'=>$data['remarks'],
            'returned_by'=>auth()->id(),
            'returned_at'=>now()

        ]);

        return back()->with('success','Document returned for revision.');
    }

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