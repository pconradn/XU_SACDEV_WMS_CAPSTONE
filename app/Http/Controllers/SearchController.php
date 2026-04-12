<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q'));

        $projects = collect();

        if ($q) {

            $keywords = collect(explode(' ', $q))
                ->filter()
                ->values();

            $projects = Project::query()
                ->with([
                    'organization',
                    'assignments.user',
                    'documents.formType',
                    'documents.proposalData'
                ])

                ->where(function ($query) use ($keywords) {

                    foreach ($keywords as $word) {

                        $query->where(function ($sub) use ($word) {

                            // PROJECT FIELDS
                            $sub->where('title', 'like', "%{$word}%")
                                ->orWhere('description', 'like', "%{$word}%")
                                ->orWhere('implementing_body', 'like', "%{$word}%")
                                ->orWhere('implementation_venue', 'like', "%{$word}%")
                                ->orWhere('workflow_status', 'like', "%{$word}%");

                            // ORGANIZATION
                            $sub->orWhereHas('organization', function ($q2) use ($word) {
                                $q2->where('name', 'like', "%{$word}%");
                            });

                            // PROJECT HEAD
                            $sub->orWhereHas('assignments', function ($q3) use ($word) {
                                $q3->where(function ($q4) {
                                        $q4->where('role', 'project_head')
                                           ->orWhere('assignment_role', 'project_head');
                                    })
                                    ->whereNull('archived_at')
                                    ->whereHas('user', function ($q5) use ($word) {

                                        $q5->where(function ($u) use ($word) {

                                            $u->where('name', 'like', "%{$word}%")
                                            ->orWhere('first_name', 'like', "%{$word}%")
                                            ->orWhere('last_name', 'like', "%{$word}%")
                                            ->orWhere('middle_initial', 'like', "%{$word}%")

                                            // full name search (first + last)
                                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$word}%"])

                                            // first + middle + last
                                            ->orWhereRaw("CONCAT(first_name, ' ', middle_initial, ' ', last_name) LIKE ?", ["%{$word}%"]);
                                        });

                                    });
                                    
                            });

                        });
                    }

                })

                ->orderByDesc('created_at')
                ->paginate(10)
                ->withQueryString();
        }

        return view('search.index', [
            'q' => $q,
            'projects' => $projects,
        ]);
    }
}