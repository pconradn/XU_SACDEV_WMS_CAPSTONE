<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationSchoolYear;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class AdminOrgBySyController extends Controller
{
    private const SESSION_KEY = 'admin_orgs_sy_id';

    private function selectedSyId(Request $request): int
    {
        return (int) $request->session()->get(self::SESSION_KEY, 0);
    }

    private function activeSy(): ?SchoolYear
    {
        // adjust if you use a different “active” mechanism
        return SchoolYear::query()->where('is_active', true)->first();
    }

    public function index(Request $request)
    {
        $activeSy = $this->activeSy();

        $schoolYears = SchoolYear::query()
            ->orderByDesc('id')
            ->get(['id', 'name', 'is_active']);

        $syId = (int) $request->query('sy_id', $this->selectedSyId($request));

        // if none selected yet, default to active SY if available
        if ($syId <= 0 && $activeSy) {
            $syId = (int) $activeSy->id;
            $request->session()->put(self::SESSION_KEY, $syId);
        }

        $q = trim((string) $request->query('q', ''));

        $orgSyQuery = OrganizationSchoolYear::query()
            ->with(['organization'])
            ->when($syId > 0, fn($qq) => $qq->where('school_year_id', $syId))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->whereHas('organization', function ($oq) use ($q) {
                    $oq->where('name', 'like', "%{$q}%")
                       ->orWhere('acronym', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id');

        $orgSys = $orgSyQuery->paginate(20)->withQueryString();

        $selectedSy = $syId > 0 ? $schoolYears->firstWhere('id', $syId) : null;

        return view('admin.orgs_by_sy.index', [
            'schoolYears' => $schoolYears,
            'activeSy' => $activeSy,
            'selectedSy' => $selectedSy,
            'orgSys' => $orgSys,
            'q' => $q,
        ]);
    }

    public function setSy(Request $request)
    {
        $data = $request->validate([
            'school_year_id' => ['required', 'integer', 'exists:school_years,id'],
        ]);

        $request->session()->put(self::SESSION_KEY, (int) $data['school_year_id']);

        return redirect()->route('admin.orgs_by_sy.index')->with('status', 'School year context updated.');
    }

    public function show(Request $request, Organization $organization)
    {
        $syId = $this->selectedSyId($request);
        if ($syId <= 0) {
            return redirect()->route('admin.orgs_by_sy.index')
                ->with('error', 'Select a school year first.');
        }

        $activeSy = $this->activeSy();
        $selectedSy = SchoolYear::find($syId);

        // ensure org is registered in that SY
        $orgSy = OrganizationSchoolYear::query()
            ->with(['president'])
            ->where('organization_id', $organization->id)
            ->where('school_year_id', $syId)
            ->first();

        if (!$orgSy) {
            return redirect()->route('admin.orgs_by_sy.index')
                ->with('error', 'This organization has no registration record for the selected school year.');
        }

        return view('admin.orgs_by_sy.show', [
            'organization' => $organization,
            'orgSy' => $orgSy,
            'selectedSy' => $selectedSy,
            'activeSy' => $activeSy,
        ]);
    }





    public function majorOfficers(Request $request, Organization $organization)
    {
        return redirect()
            ->route('admin.orgs_by_sy.show', $organization)
            ->with('status', 'Major Officer Roles page will be added next.');
    }
}