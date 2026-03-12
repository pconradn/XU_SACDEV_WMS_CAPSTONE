<?php

namespace App\Http\Controllers\Sacdev;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\SchoolYear;
use App\Models\StrategicPlanSubmission;   // B1
use App\Models\PresidentRegistration;     // B2
use App\Models\OfficerSubmission;         // B3
use App\Models\ModeratorSubmission;       // B5
use App\Support\SacdevReregContext;
use Illuminate\Http\Request;

class SacdevReregOverviewController extends Controller
{
    // Define actionable statuses for SACDEV queue
    private const SACDEV_ACTIONABLE = [
        'submitted_to_sacdev',
        'forwarded_to_sacdev',
    ];

    public function index(Request $request)
    {
        $syId = SacdevReregContext::getSyId($request);

        $schoolYears = SchoolYear::orderBy('start_year', 'desc')->get(); // adjust columns as needed

        // Pull form rows for this SY, keyed by org_id (1 row per org+SY)
        $b1 = StrategicPlanSubmission::forSy($syId)->get()->keyBy('organization_id');
        $b2 = PresidentRegistration::where('target_school_year_id', $syId)->get()->keyBy('organization_id');
        $b3 = OfficerSubmission::where('target_school_year_id', $syId)->get()->keyBy('organization_id');
        $b5 = ModeratorSubmission::where('target_school_year_id', $syId)->get()->keyBy('organization_id');

        // "Started re-reg" org IDs = union of any existing row in any form table
        $orgIds = collect()
            ->merge($b1->keys())
            ->merge($b2->keys())
            ->merge($b3->keys())
            ->merge($b5->keys())
            ->unique()
            ->values();

        $organizations = Organization::whereIn('id', $orgIds)
            ->orderBy('name') // adjust to your column
            ->get()
            ->keyBy('id');

        // Build cards
        $cards = $orgIds->map(function ($orgId) use ($organizations, $b1, $b2, $b3, $b5, $syId) {
            $org = $organizations->get($orgId);

            $statuses = [
                'B1' => optional($b1->get($orgId))->status,
                'B2' => optional($b2->get($orgId))->status,
                'B3' => optional($b3->get($orgId))->status,
                'B5' => optional($b5->get($orgId))->status,
            ];

            // pending forms count (SACDEV actionable)
            $pendingForms = collect($statuses)->filter(fn ($s) => in_array($s, self::SACDEV_ACTIONABLE, true));
            $pendingCount = $pendingForms->count();

            // ready for activation (all required approved)
            $allApproved = collect($statuses)->every(fn ($s) => $s === 'approved_by_sacdev');

            $state = $allApproved
                ? 'ready'
                : ($pendingCount > 0 ? 'needs_review' : 'waiting');

            return [
                'organization_id' => $orgId,
                'org_name'        => $org?->name ?? 'Unknown Org',
                'sy_id'           => $syId,

                'statuses'        => $statuses,
                'pending_count'   => $pendingCount,
                'pending_labels'  => $pendingForms->keys()->values()->all(), // e.g. ['B1','B3']
                'all_approved'    => $allApproved,
                'state'           => $state,
            ];
        });

        // Badge value = number of orgs needing SACDEV action (among started orgs)
        $badgeCount = $cards->where('state', 'needs_review')->count();

        return view('sacdev.rereg.overview', compact(
            'schoolYears',
            'syId',
            'badgeCount',
            'cards'
        ));
    }

    public function setSy(Request $request)
    {
        $syId = (int) $request->input('target_school_year_id');
        SacdevReregContext::setSyId($request, $syId);

        return redirect()->route('sacdev.rereg.overview');
    }
}
