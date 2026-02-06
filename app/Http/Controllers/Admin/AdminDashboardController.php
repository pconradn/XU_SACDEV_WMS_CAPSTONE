<?php

namespace App\Http\Controllers\Admin;

use App\Models\SchoolYear;
use App\Models\Organization;
use App\Models\OfficerSubmission;
use Illuminate\Support\Facades\DB;
use App\Models\ModeratorSubmission;
use App\Http\Controllers\Controller;
use App\Models\PresidentRegistration;
use App\Models\StrategicPlanSubmission;

//SHOW ADMIN DASHBOARD

class AdminDashboardController extends Controller
{
    public function index()
    {
        $activeSy = SchoolYear::activeYear();


        $actionable = ['submitted_to_sacdev', 'forwarded_to_sacdev'];


        $pendingCaseCount = collect()
            ->merge(
                StrategicPlanSubmission::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn ($r) => $r->organization_id.'|'.$r->target_school_year_id)
            )
            ->merge(
                PresidentRegistration::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn ($r) => $r->organization_id.'|'.$r->target_school_year_id)
            )
            ->merge(
                OfficerSubmission::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn ($r) => $r->organization_id.'|'.$r->target_school_year_id)
            )
            ->merge(
                ModeratorSubmission::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn ($r) => $r->organization_id.'|'.$r->target_school_year_id)
            )
            ->unique()
            ->count();


        // Ready for activation count (across ALL SY)
        $approved = 'approved_by_sacdev';

        // get org+sy pairs where each form is approved
        $b1Approved = StrategicPlanSubmission::where('status', $approved)
            ->get(['organization_id', 'target_school_year_id'])
            ->map(fn($r) => $r->organization_id.'|'.$r->target_school_year_id)
            ->unique();

        $b2Approved = PresidentRegistration::where('status', $approved)
            ->get(['organization_id', 'target_school_year_id'])
            ->map(fn($r) => $r->organization_id.'|'.$r->target_school_year_id)
            ->unique();

        $b3Approved = OfficerSubmission::where('status', $approved)
            ->get(['organization_id', 'target_school_year_id'])
            ->map(fn($r) => $r->organization_id.'|'.$r->target_school_year_id)
            ->unique();

        $b5Approved = ModeratorSubmission::where('status', $approved)
            ->get(['organization_id', 'target_school_year_id'])
            ->map(fn($r) => $r->organization_id.'|'.$r->target_school_year_id)
            ->unique();

        // intersection = cases where ALL forms are approved
        $readyKeys = $b1Approved
            ->intersect($b2Approved)
            ->intersect($b3Approved)
            ->intersect($b5Approved)
            ->values();

        // activated keys from pivot table
        $activatedKeys = DB::table('organization_school_years')
            ->get(['organization_id', 'school_year_id'])
            ->map(fn($r) => (int)$r->organization_id.'|'.(int)$r->school_year_id)
            ->unique();

        // not activated yet
        $readyNotActivated = $readyKeys->diff($activatedKeys);

        $readyForActivationCount = $readyNotActivated->count();

        return view('admin.dashboard', [
            'activeSy' => $activeSy,
            'orgCount' => Organization::count(),
            'syCount' => SchoolYear::count(),
            'pendingCaseCount' => $pendingCaseCount,
            'readyForActivationCount' => $readyForActivationCount,
        ]);
    }
}
