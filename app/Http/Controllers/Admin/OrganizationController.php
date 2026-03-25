<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

//ORG CRUD

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::query()
            ->orderBy('name')
            ->get();

        return view('admin.organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('admin.organizations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:organizations,name'],
            'acronym' => ['nullable', 'string', 'max:50'],
        ]);


        Organization::create($data);

        return redirect()

            ->route('admin.organizations.index')
            ->with('status', 'Organization created.');
    }


    public function edit(Organization $organization)
    {
        return view('admin.organizations.edit', compact('organization'));

    }

    public function update(Request $request, Organization $organization)
    {


        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:organizations,name,' . $organization->id],
            'acronym' => ['nullable', 'string', 'max:50'],
        ]);

        $organization->update($data);

        return redirect()
            ->route('admin.organizations.index')
            ->with('status', 'Organization updated.');
    }



    public function destroy(Organization $organization)
    {
        $organization->archived_at = now();
        $organization->save();

        return redirect()
            ->route('admin.organizations.index')
            ->with('status', 'Organization archived.');
    }
}
