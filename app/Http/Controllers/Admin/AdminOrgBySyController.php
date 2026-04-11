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
        return SchoolYear::query()->where('is_active', true)->first();
    }

    public function index(Request $request)
    {
        $activeSy = $this->activeSy();

        $schoolYears = SchoolYear::query()
            ->orderByDesc('id')
            ->get(['id', 'name', 'is_active']);

        $syId = (int) $request->query('sy_id', $this->selectedSyId($request));

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

        $orgSys = $orgSyQuery->paginate(200)->withQueryString();

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

        return redirect()->route('admin.orgs_by_sy.index')
            ->with('status', 'School year context updated.');
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


        $orgSy = OrganizationSchoolYear::query()
            ->with(['president'])
            ->where('organization_id', $organization->id)
            ->where('school_year_id', $syId)
            ->first();

        if (!$orgSy) {
            return redirect()->route('admin.orgs_by_sy.index')
                ->with('error', 'This organization has no registration record for the selected school year.');
        }


        $routes = [
            'rereg' => route('admin.rereg.hub', $organization->id),

            'officers' => route('sacdev.officers.index', [
                'organization_id' => $organization->id,
                'school_year_id' => $syId,
            ]),

            'members' => route('sacdev.members.index', [
                'organization_id' => $organization->id,
                'school_year_id' => $syId,
            ]),

            'projects' => route('admin.org.projects.index', [
                $organization->id,
                $syId
            ]),
        ];


        $orgInfo = [
            'name' => $organization->name,
            'acronym' => $organization->acronym,
            'mission' => $organization->mission,
            'vision' => $organization->vision,

            'logo_path' => $organization->logo_path,
            'hasLogo' => !empty($organization->logo_path),
            'logoUrl' => $organization->logo_path
                ? asset('storage/' . $organization->logo_path)
                : null,

            'logo_original_name' => $organization->logo_original_name,
            'logo_size_bytes' => $organization->logo_size_bytes,

            'cluster_id' => $organization->cluster_id,

            'created_at' => $organization->created_at,
            'updated_at' => $organization->updated_at,
            'archived_at' => $organization->archived_at,
        ];


        $orgMeta = [
            'president_name' => optional($orgSy->president)->name,
            'president_confirmed_at' => $orgSy->president_confirmed_at,

            'isActiveSy' => $activeSy && $selectedSy
                ? (int)$activeSy->id === (int)$selectedSy->id
                : false,
        ];


        return view('admin.orgs_by_sy.show', [
            'organization' => $organization,
            'orgInfo' => $orgInfo,   
            'orgMeta' => $orgMeta,    
            'orgSy' => $orgSy,
            'selectedSy' => $selectedSy,
            'activeSy' => $activeSy,
            'routes' => $routes,
        ]);
    }

    public function majorOfficers(Request $request, Organization $organization)
    {
        return redirect()
            ->route('admin.orgs_by_sy.show', $organization)
            ->with('status', 'Major Officer Roles page will be added next.');
    }
}