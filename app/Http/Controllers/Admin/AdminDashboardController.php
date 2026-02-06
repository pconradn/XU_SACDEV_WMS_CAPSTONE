<?php

namespace App\Http\Controllers\Admin;

use App\Models\SchoolYear;
use App\Models\Organization;
use App\Models\OfficerSubmission;
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

        return view('admin.dashboard', [
            'activeSy' => $activeSy,
            'orgCount' => Organization::count(),
            'syCount' => SchoolYear::count(),
            'pendingCaseCount' => $pendingCaseCount,
        ]);
    }
}
