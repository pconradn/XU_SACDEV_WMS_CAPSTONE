<?php

namespace App\Http\Controllers;

use App\Models\OrgMembership;
use App\Models\Organization;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class ContextController extends Controller
{
    public function show(Request $request)
    {
        $userId = (int) auth()->id();

        
        $memberships = OrgMembership::query()
            ->where('user_id', $userId)
            ->whereNull('archived_at')
            ->get(['organization_id', 'school_year_id']);

        if ($memberships->isEmpty()) {
            abort(403, 'No active organization memberships found for your account.');
        }

        // ACTIVE SY
        $activeSy = SchoolYear::where('is_active', true)->first();

        // UNIQUE IDS
        $orgIds = $memberships->pluck('organization_id')->unique()->values();
        $syIds  = $memberships->pluck('school_year_id')->unique()->values();

        // ORGANIZATIONS
        $orgs = Organization::query()
            ->whereIn('id', $orgIds)
            ->orderBy('name')
            ->get(['id', 'name']);

        // SCHOOL YEARS (FILTER: only <= active SY)
        $schoolYears = SchoolYear::query()
            ->whereIn('id', $syIds)
            ->when($activeSy, function ($q) use ($activeSy) {
                $q->where('start_date', '<=', $activeSy->end_date);
            })
            ->orderByDesc('start_date')
            ->get(['id', 'name', 'start_date']);

    
        $contexts = $orgs->map(function ($org) use ($memberships, $schoolYears, $activeSy) {

            
            $orgSyIds = $memberships
                ->where('organization_id', $org->id)
                ->pluck('school_year_id')
                ->unique();

            $validSys = $schoolYears
                ->whereIn('id', $orgSyIds)
                ->map(function ($sy) use ($activeSy) {
                    return [
                        'id' => $sy->id,
                        'label' => $sy->label ?? ('SY ' . $sy->name),
                        'is_active' => $activeSy && $sy->id === $activeSy->id,
                    ];
                })
                ->values();

            return [
                'organization' => $org,
                'school_years' => $validSys,
            ];
        });

        return view('context.select', [
            'contexts' => $contexts,
            'activeOrgId' => session('active_org_id'),
            'activeSyId' => session('encode_sy_id'),
        ]);
    }


    public function update(Request $request)
    {
        $userId = (int) auth()->id();

        $data = $request->validate([
            'active_org_id' => ['required', 'integer'],
            'encode_sy_id'  => ['required', 'integer'],
        ]);

        $orgId = (int) $data['active_org_id'];
        $syId  = (int) $data['encode_sy_id'];

        // VALIDATE COMBINATION EXISTS
        $allowed = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->exists();

        if (! $allowed) {
            return back()
                ->withErrors([
                    'active_org_id' => 'Invalid organization and school year selection.'
                ])
                ->withInput();
        }

        // STORE CONTEXT
        $request->session()->put([
            'active_org_id' => $orgId,
            'encode_sy_id'  => $syId,
        ]);

        // CLEAN OTHER CONTEXTS
        $request->session()->forget([
            'active_moderator_org_id',
            'active_moderator_sy_id'
        ]);

        return redirect()->route('dashboard');
    }

}
