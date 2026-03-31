<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Organization;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q'));

        $projects = collect();
        $organizations = collect();

        if ($q) {
            $projects = Project::where('title', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->limit(10)
                ->get();

            $organizations = Organization::where('name', 'like', "%{$q}%")
                ->limit(10)
                ->get();
        }

        return view('search.index', compact(
            'q',
            'projects',
            'organizations'
        ));
    }
}