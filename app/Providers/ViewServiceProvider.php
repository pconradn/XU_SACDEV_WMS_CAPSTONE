<?php

namespace App\Providers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Models\OrgMembership;
use App\Models\OfficerSubmission;
use App\Models\ModeratorSubmission;
use App\Support\SacdevReregContext;
use Illuminate\Support\Facades\View;
use App\Models\PresidentRegistration;
use App\Models\StrategicPlanSubmission;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (!auth()->check()) {
                return;
            }

            $userId = auth()->id();
            $orgId  = session('active_org_id');

            if (!$orgId) {
                $view->with('isModerator', false);
                return;
            }

            $activeSyId = SchoolYear::query()
                ->where('is_active', true)
                ->value('id');

            $isModerator = false;

            if ($activeSyId) {
                $isModerator = OrgMembership::query()
                    ->where('organization_id', $orgId)
                    ->where('school_year_id', $activeSyId)
                    ->where('user_id', $userId)
                    ->where('role', 'moderator')
                    ->whereNull('archived_at')
                    ->exists();
            }

            $view->with('isModerator', $isModerator);
        });


        View::composer('layouts.navigation', function ($view) {
            $user = auth()->user();
            if (!$user || $user->system_role !== 'sacdev_admin') {
                $view->with('adminReregBadgeCount', 0);
                return;
            }

            $actionable = ['submitted_to_sacdev', 'forwarded_to_sacdev'];

            // Pull ONLY actionable rows from each form table (across ALL SY)
            $b1 = \App\Models\StrategicPlanSubmission::whereIn('status', $actionable)
                ->get(['organization_id', 'target_school_year_id']);

            $b2 = \App\Models\PresidentRegistration::whereIn('status', $actionable)
                ->get(['organization_id', 'target_school_year_id']);

            $b3 = \App\Models\OfficerSubmission::whereIn('status', $actionable)
                ->get(['organization_id', 'target_school_year_id']);

            $b5 = \App\Models\ModeratorSubmission::whereIn('status', $actionable)
                ->get(['organization_id', 'target_school_year_id']);

            // Make unique "case keys" org_id|sy_id
            $caseKeys = collect()
                ->merge($b1->map(fn($r) => $r->organization_id . '|' . $r->target_school_year_id))
                ->merge($b2->map(fn($r) => $r->organization_id . '|' . $r->target_school_year_id))
                ->merge($b3->map(fn($r) => $r->organization_id . '|' . $r->target_school_year_id))
                ->merge($b5->map(fn($r) => $r->organization_id . '|' . $r->target_school_year_id))
                ->unique()
                ->values();

            $view->with('adminReregBadgeCount', $caseKeys->count());
        });





        
    }
}
