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

class AdminDashboardController extends Controller
{
    public function index()
    {
        $activeSy = SchoolYear::activeYear();

        $actionable = ['submitted_to_sacdev', 'forwarded_to_sacdev'];

        // Pending case count (org|sy pairs across forms)
        $pendingCaseCount = collect()
            ->merge(
                StrategicPlanSubmission::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn ($r) => (int)$r->organization_id . '|' . (int)$r->target_school_year_id)
                    ->toBase()
            )
            ->merge(
                PresidentRegistration::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn ($r) => (int)$r->organization_id . '|' . (int)$r->target_school_year_id)
                    ->toBase()
            )
            ->merge(
                OfficerSubmission::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn ($r) => (int)$r->organization_id . '|' . (int)$r->target_school_year_id)
                    ->toBase()
            )
            ->merge(
                ModeratorSubmission::whereIn('status', $actionable)
                    ->get(['organization_id', 'target_school_year_id'])
                    ->map(fn ($r) => (int)$r->organization_id . '|' . (int)$r->target_school_year_id)
                    ->toBase()
            )
            ->unique()
            ->count();

        // Ready for activation count (across ALL SY)
        $approved = 'approved_by_sacdev';

        $b1Approved = StrategicPlanSubmission::where('status', $approved)
            ->get(['organization_id', 'target_school_year_id'])
            ->map(fn ($r) => (int)$r->organization_id . '|' . (int)$r->target_school_year_id)
            ->unique()
            ->toBase();

        $b2Approved = PresidentRegistration::where('status', $approved)
            ->get(['organization_id', 'target_school_year_id'])
            ->map(fn ($r) => (int)$r->organization_id . '|' . (int)$r->target_school_year_id)
            ->unique()
            ->toBase();

        $b3Approved = OfficerSubmission::where('status', $approved)
            ->get(['organization_id', 'target_school_year_id'])
            ->map(fn ($r) => (int)$r->organization_id . '|' . (int)$r->target_school_year_id)
            ->unique()
            ->toBase();

        $b5Approved = ModeratorSubmission::where('status', $approved)
            ->get(['organization_id', 'target_school_year_id'])
            ->map(fn ($r) => (int)$r->organization_id . '|' . (int)$r->target_school_year_id)
            ->unique()
            ->toBase();

        // intersection = cases where ALL forms are approved
        $readyKeys = $b1Approved
            ->intersect($b2Approved)
            ->intersect($b3Approved)
            ->intersect($b5Approved)
            ->values()
            ->toBase();

        // activated keys from pivot table (already base collection)
        $activatedKeys = DB::table('organization_school_years')
            ->get(['organization_id', 'school_year_id'])
            ->map(fn ($r) => (int)$r->organization_id . '|' . (int)$r->school_year_id)
            ->unique()
            ->toBase();

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
