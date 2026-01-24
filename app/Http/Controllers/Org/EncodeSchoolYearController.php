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
        $activeSy = SchoolYear::activeYear();

        if (!$activeSy) {
            abort(403, 'No active school year.');
        }

        $orgId = (int) $request->session()->get('active_org_id', 0);

        // Must have selected org first (multi-org)
        if (!$orgId) {
            return redirect()->route('org.home')->with('status', 'Please select an organization first.');
        }

        // Must be president in active SY for selected org
        $isPresident = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $activeSy->id)
            ->whereNull('archived_at')
            ->where('role', 'president')
            ->exists();

        if (!$isPresident) {
            abort(403, 'Only the President can choose a school year to encode.');
        }

        // Determine "next SY" by start_date / id ordering
        $nextSy = SchoolYear::query()
            ->where('id', '!=', $activeSy->id)
            ->where(function ($q) use ($activeSy) {
                // If you have start_date, use it
                if ($activeSy->start_date) {
                    $q->where('start_date', '>', $activeSy->start_date);
                } else {
                    // fallback: higher id
                    $q->where('id', '>', $activeSy->id);
                }
            })
            ->orderBy('start_date')
            ->orderBy('id')
            ->first();

        $allowed = collect([$activeSy])->when($nextSy, fn ($c) => $c->push($nextSy));

        $selectedEncodeSyId = (int) $request->session()->get('encode_sy_id', $activeSy->id);
        if (!$allowed->pluck('id')->contains($selectedEncodeSyId)) {
            $selectedEncodeSyId = $activeSy->id;
            $request->session()->put('encode_sy_id', $selectedEncodeSyId);
        }

        return view('org.encode-school-year', [
            'activeSy' => $activeSy,
            'nextSy' => $nextSy,
            'allowedSchoolYears' => $allowed,
            'selectedEncodeSyId' => $selectedEncodeSyId,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $activeSy = SchoolYear::activeYear();

        if (!$activeSy) {
            abort(403, 'No active school year.');
        }

        $orgId = (int) $request->session()->get('active_org_id', 0);
        if (!$orgId) {
            return redirect()->route('org.home')->with('status', 'Please select an organization first.');
        }

        // Must be president in active SY for selected org
        $isPresident = OrgMembership::query()
            ->where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $activeSy->id)
            ->whereNull('archived_at')
            ->where('role', 'president')
            ->exists();

        if (!$isPresident) {
            abort(403, 'Only the President can update encode school year.');
        }

        // Compute next SY same as show()
        $nextSy = SchoolYear::query()
            ->where('id', '!=', $activeSy->id)
            ->where(function ($q) use ($activeSy) {
                if ($activeSy->start_date) {
                    $q->where('start_date', '>', $activeSy->start_date);
                } else {
                    $q->where('id', '>', $activeSy->id);
                }
            })
            ->orderBy('start_date')
            ->orderBy('id')
            ->first();

        $allowedIds = collect([$activeSy->id])->when($nextSy, fn ($c) => $c->push($nextSy->id));

        $data = $request->validate([
            'encode_sy_id' => ['required', 'integer'],
        ]);

        $encodeSyId = (int) $data['encode_sy_id'];

        if (!$allowedIds->contains($encodeSyId)) {
            return back()->with('status', 'Invalid school year selection.');
        }

        $request->session()->put('encode_sy_id', $encodeSyId);

        return redirect()->route('org.home')->with('status', 'Encoding School Year updated.');
    }
}
