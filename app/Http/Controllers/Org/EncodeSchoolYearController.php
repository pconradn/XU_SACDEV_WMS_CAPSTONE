<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\OrgMembership;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class EncodeSchoolYearController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        // ✅ all SY where user has membership (any org)
        $allowedSyIds = OrgMembership::query()
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->distinct()
            ->pluck('school_year_id')
            ->map(fn($v) => (int)$v)
            ->all();

        $schoolYears = SchoolYear::query()
            ->whereIn('id', $allowedSyIds ?: [-1])
            ->orderByDesc('id')
            ->get();

        $selectedSyId = (int) $request->session()->get('encode_sy_id', 0);

        // default to newest allowed SY if none selected
        if ($selectedSyId <= 0 && $schoolYears->count() > 0) {
            $selectedSyId = (int) $schoolYears->first()->id;
            $request->session()->put('encode_sy_id', $selectedSyId);
        }

        return view('org.encode-school-year', [
            'allowedSchoolYears' => $schoolYears,
            'selectedEncodeSyId' => $selectedSyId,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $allowedSyIds = OrgMembership::query()
            ->where('user_id', $user->id)
            ->whereNull('archived_at')
            ->distinct()
            ->pluck('school_year_id')
            ->map(fn($v) => (int)$v)
            ->all();

        $data = $request->validate([
            'encode_sy_id' => ['required', 'integer'],
        ]);

        $encodeSyId = (int) $data['encode_sy_id'];

        if (!in_array($encodeSyId, $allowedSyIds, true)) {
            return back()->with('status', 'Invalid school year selection.');
        }

    
        $request->session()->put('encode_sy_id', $encodeSyId);

        
        $currentOrgId = (int) $request->session()->get('active_org_id', 0);
        if ($currentOrgId > 0) {
            $stillAllowed = OrgMembership::query()
                ->where('user_id', $user->id)
                ->where('school_year_id', $encodeSyId)
                ->where('organization_id', $currentOrgId)
                ->whereNull('archived_at')
                ->exists();

            if (!$stillAllowed) {
                $request->session()->forget('active_org_id');
            }
        }

        return redirect()->route('org.home')->with('status', 'School year updated.');
    }
}
