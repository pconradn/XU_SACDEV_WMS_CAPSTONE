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

        // All membership rows for this user
        $memberships = OrgMembership::query()
            ->where('user_id', $userId)
            ->get(['organization_id', 'school_year_id']);

        // If user has no memberships, you can decide what to do
        if ($memberships->isEmpty()) {
            abort(403, 'No organization memberships found for your account.');
        }

        $orgIds = $memberships->pluck('organization_id')->unique()->values();
        $syIds  = $memberships->pluck('school_year_id')->unique()->values();

        // Only show orgs/SYs user belongs to
        $orgs = Organization::query()
            ->whereIn('id', $orgIds)
            ->orderBy('name')
            ->get(['id', 'name']);

        $schoolYears = SchoolYear::query()
            ->whereIn('id', $syIds)
            ->orderByDesc('id')
            ->get(['id', 'name', 'start_date']);

        return view('context.select', compact('orgs', 'schoolYears'));
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

        // ✅ Critical: validate the PAIR exists in org_memberships
        $allowed = OrgMembership::query()
            ->where('user_id', $userId)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->exists();

        if (! $allowed) {
            return back()
                ->withErrors(['active_org_id' => 'You do not have membership for that organization and school year combination.'])
                ->withInput();
        }

        $request->session()->put('active_org_id', $orgId);
        $request->session()->put('encode_sy_id', $syId);

        // Optional: clear moderator-specific selections if you still use them anywhere
        $request->session()->forget(['active_moderator_org_id', 'active_moderator_sy_id']);

        return redirect()->route('dashboard');
    }
}
