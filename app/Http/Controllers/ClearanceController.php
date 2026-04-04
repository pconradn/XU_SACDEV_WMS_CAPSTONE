<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\OrgMembership;
use App\Models\SchoolYear;

class ClearanceController extends Controller
{
    /**
     * Show the clearance search page
     */
    public function publicIndex()
    {
        return view('clearance.public');
    }
    public function index()
    {
        return view('clearance.index');
    }

    /**
     * Handle search and compute clearance
     */
    public function publicVerify(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string'
        ]);

        $studentId = trim($request->student_id);
        $email = $studentId . '@my.xu.edu.ph';

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->with([
                'error' => 'Student not found.'
            ]);
        }

        $activeSY = SchoolYear::where('is_active', 1)->first();

        if (!$activeSY) {
            return back()->with([
                'error' => 'System unavailable.'
            ]);
        }

        $membership = OrgMembership::where('user_id', $user->id)
            ->where('school_year_id', $activeSY->id)
            ->whereNull('archived_at')
            ->first();

        // No membership = cleared
        if (!$membership) {
            return view('clearance.public', [
                'user' => $user,
                'isCleared' => true,
                'pendingCount' => 0
            ]);
        }

        $projectIds = ProjectAssignment::where('user_id', $user->id)
            ->whereNull('archived_at')
            ->pluck('project_id');

        if ($projectIds->isEmpty()) {
            return view('clearance.public', [
                'user' => $user,
                'isCleared' => true,
                'pendingCount' => 0
            ]);
        }

        $projects = Project::whereIn('id', $projectIds)
            ->where('school_year_id', $activeSY->id)
            ->get();

        $blockingProjects = $projects->filter(function ($p) {
            return !in_array($p->workflow_status, [
                'completed',
                'cancelled'
            ]);
        });

        $pendingCount = $blockingProjects->count();
        $isCleared = $pendingCount === 0;

        return view('clearance.public', [
            'user' => $user,
            'isCleared' => $isCleared,
            'pendingCount' => $pendingCount
        ]);
    }


    public function search(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string'
        ]);

     
        $studentId = trim($request->student_id);
        $email = $studentId . '@my.xu.edu.ph';

       
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->with([
                'error' => 'Student not found.',
                'searched_id' => $studentId
            ]);
        }

       
        $activeSY = SchoolYear::where('is_active', 1)->first();

        if (!$activeSY) {
            return back()->with([
                'error' => 'No active school year found.',
                'searched_id' => $studentId
            ]);
        }

      
        $membership = OrgMembership::where('user_id', $user->id)
            ->where('school_year_id', $activeSY->id)
            ->whereNull('archived_at')
            ->first();


        if (!$membership) {
            return view('clearance.index', [
                'user' => $user,
                'projects' => collect(),
                'blockingProjects' => collect(),
                'isCleared' => true,
                'searched_id' => $studentId
            ]);
        }

        $assignmentQuery = ProjectAssignment::where('user_id', $user->id)
            ->whereNull('archived_at');

        $projectIds = $assignmentQuery->pluck('project_id');

      
        if ($projectIds->isEmpty()) {
            return view('clearance.index', [
                'user' => $user,
                'projects' => collect(),
                'blockingProjects' => collect(),
                'isCleared' => true,
                'searched_id' => $studentId
            ]);
        }

  
        $projects = Project::whereIn('id', $projectIds)
            ->where('school_year_id', $activeSY->id)
            ->get();

      
        $blockingProjects = $projects->filter(function ($project) {
            return !in_array($project->workflow_status, [
                'completed',
                'cancelled'
            ]);
        });

     
        $isCleared = $blockingProjects->isEmpty();

      
        $assignments = ProjectAssignment::where('user_id', $user->id)
            ->whereNull('archived_at')
            ->get()
            ->keyBy('project_id');

        // map role into project
        $projects = $projects->map(function ($project) use ($assignments) {
            $assignment = $assignments->get($project->id);
            $project->assignment_role = $assignment->assignment_role ?? 'Member';
            return $project;
        });

        return view('clearance.index', [
            'user' => $user,
            'projects' => $projects,
            'blockingProjects' => $blockingProjects,
            'isCleared' => $isCleared,
            'searched_id' => $studentId
        ]);
    }
}