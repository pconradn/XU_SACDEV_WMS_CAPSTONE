<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\CoaAssignment;
use App\Models\ProjectDocument;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoaAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $coaUsers = User::where('is_coa_officer', true)->get();
        $orgs = \App\Models\Organization::orderBy('name')->get();
        $schoolYears = SchoolYear::orderByDesc('id')->get();

        $projects = collect();

        if ($request->organization_id && $request->school_year_id) {

            $projects = Project::where('organization_id', $request->organization_id)
                ->where('school_year_id', $request->school_year_id)
                ->with(['coaAssignment.coaOfficer'])
                ->orderBy('title')
                ->get();
        }

        return view('admin.coa.index', [
            'coaUsers' => $coaUsers,
            'orgs' => $orgs,
            'schoolYears' => $schoolYears,
            'projects' => $projects,
            'selectedUser' => $request->user_id,
            'selectedOrg' => $request->organization_id,
            'selectedSy' => $request->school_year_id,
        ]);
    }


    public function bulkUpdate(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'organization_id' => 'required|exists:organizations,id',
            'school_year_id' => 'required|exists:school_years,id',
            'project_ids' => 'array'
        ]);

        $user = User::findOrFail($data['user_id']);

        if (!$user->is_coa_officer) {
            return back()->withErrors([
                'user_id' => 'Selected user is not a COA officer.'
            ]);
        }

        DB::transaction(function () use ($data, $user) {

            $projects = Project::where('organization_id', $data['organization_id'])
                ->where('school_year_id', $data['school_year_id'])
                ->get();

            foreach ($projects as $project) {

                $isSelected = in_array($project->id, $data['project_ids'] ?? []);

                if ($isSelected) {

                  
                    CoaAssignment::updateOrCreate(
                        ['project_id' => $project->id],
                        [
                            'user_id' => $user->id,
                            'assigned_by' => auth()->id()
                        ]
                    );

             
                    $documents = ProjectDocument::where('project_id', $project->id)
                        ->where('status', 'submitted')
                        ->with('signatures')
                        ->get();

                    foreach ($documents as $document) {

                        $coaSig = $document->signatures
                            ->firstWhere('role', 'coa_officer');

                        if (!$coaSig) continue;

                        if ($coaSig->status === 'signed') {

                            $coaSig->update([
                                'user_id' => $user->id,
                                'status' => 'pending',
                                'signed_at' => null
                            ]);

                            $document->signatures()
                                ->where('id', '>', $coaSig->id)
                                ->update([
                                    'status' => 'pending',
                                    'signed_at' => null
                                ]);

                            $document->update([
                                'status' => 'submitted'
                            ]);

                        } else {

                            $coaSig->update([
                                'user_id' => $user->id,
                                'status' => 'pending',
                                'signed_at' => null
                            ]);
                        }
                    }

                } else {

          
                    CoaAssignment::where('project_id', $project->id)
                        ->where('user_id', $user->id)
                        ->delete();
                }
            }
        });

        return back()->with('success', 'COA assignments updated successfully.');
    }
}