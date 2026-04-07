<?php

namespace App\Http\Controllers\Org;

use Illuminate\Http\Request;
use App\Models\OfficerEntry;
use App\Models\OrgMembership;
use App\Http\Controllers\Controller;

class OfficerEntryController extends Controller
{
    private function ctx(Request $request): array
    {
        return [
            'orgId' => (int) $request->session()->get('active_org_id'),
            'syId'  => (int) $request->session()->get('encode_sy_id'),
        ];
    }


    public function index(Request $request)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        $userId = auth()->id();

        // Get current user's role
        $myRole = OrgMembership::where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->where('user_id', $userId)
            ->whereNull('archived_at')
            ->value('role');

        $officers = OfficerEntry::with([
            'membership' => function ($q) use ($orgId, $syId) {
                $q->where('organization_id', $orgId)
                ->where('school_year_id', $syId)
                ->whereNull('archived_at');
            }
        ])
        ->where('organization_id', $orgId)
        ->where('school_year_id', $syId)
        ->orderBy('sort_order')
        ->get();

        return view('org.officers.index', compact('officers', 'myRole'));
    }


    public function updateQpi(Request $request, OfficerEntry $officer)
    {
        ['orgId' => $orgId, 'syId' => $syId] = $this->ctx($request);

        abort_unless(
            $officer->organization_id === $orgId &&
            $officer->school_year_id === $syId,
            404
        );

        $data = $request->validate([
            'current_first_sem_qpi' => ['required', 'numeric', 'min:0', 'max:4'],
            'current_second_sem_qpi' => ['required', 'numeric', 'min:0', 'max:4'],
        ]);

        // Update officer QPI
        $officer->update($data);

        $baseQpi = null;

        if ($officer->prev_intersession_qpi !== null) {
            $baseQpi = $officer->prev_intersession_qpi;
        } else {
            $baseQpi = collect([
                $officer->prev_first_sem_qpi,
                $officer->prev_second_sem_qpi
            ])->filter()->avg();
        }

 
        $lowCount = 0;

        if ($baseQpi !== null && $baseQpi < 2.0) $lowCount++;
        if ($officer->current_first_sem_qpi < 2.0) $lowCount++;
        if ($officer->current_second_sem_qpi < 2.0) $lowCount++;


        $membership = OrgMembership::where('officer_entry_id', $officer->id)
            ->where('organization_id', $orgId)
            ->where('school_year_id', $syId)
            ->whereNull('archived_at')
            ->first();

        if ($membership) {

            $isUnderProbation =
                ($baseQpi !== null && $baseQpi < 2.0) ||
                $officer->current_first_sem_qpi < 2.0 ||
                $officer->current_second_sem_qpi < 2.0;

            $isSuspended = $lowCount >= 3;

            $membership->update([
                'is_under_probation' => $isUnderProbation ? 1 : 0,
                'is_suspended' => $isSuspended ? 1 : 0,
            ]);
        }

        return back()->with('status', 'QPI updated successfully.');
    }
}