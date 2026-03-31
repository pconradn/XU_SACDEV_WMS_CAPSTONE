<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use App\Models\Organization;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display organization index
     */
    private const SESSION_KEY = 'admin_orgs_sy_id';
    public function index()
    {
        $organizations = Organization::query()
            ->with('cluster')
            ->orderBy('name')
            ->get();

        $clusters = Cluster::orderBy('name')->get();
        $activeSY = SchoolYear::where('is_active', true)->first();

        return view('admin.organizations.index', compact(
            'organizations',
            'clusters',
            'activeSY'
        ));
    }

    public function open(Organization $organization, Request $request)
    {
        $schoolYearId = $request->get('school_year_id');

        if (!$schoolYearId) {
            return redirect()->route('admin.organizations.index')
                ->with('error', 'Select a school year.');
        }

        $exists = $organization->schoolYears()
            ->where('school_year_id', $schoolYearId)
            ->exists();

        if (!$exists) {
            return redirect()->route('admin.organizations.index')
                ->with('error', 'Invalid school year for this organization.');
        }

        $request->session()->put(self::SESSION_KEY, (int) $schoolYearId);

        return redirect()->route('admin.orgs_by_sy.show', $organization);
    }
    /**
     * Store new organization
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:organizations,name'],
            'acronym' => ['nullable', 'string', 'max:50'],
            'cluster_id' => ['required', 'exists:clusters,id'],
        ]);

        Organization::create($data);

        return redirect()
            ->route('admin.organizations.index')
            ->with('status', 'Organization created.');
    }

    /**
     * Update organization
     */
    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:organizations,name,' . $organization->id],
            'acronym' => ['nullable', 'string', 'max:50'],
            'cluster_id' => ['required', 'exists:clusters,id'],
        ]);

        $organization->update($data);

        return redirect()
            ->route('admin.organizations.index')
            ->with('status', 'Organization updated.');
    }

    /**
     * Archive organization (soft delete style)
     */
    public function destroy(Organization $organization)
    {
        $organization->update([
            'archived_at' => now(),
        ]);

        return redirect()
            ->route('admin.organizations.index')
            ->with('status', 'Organization archived.');
    }
}