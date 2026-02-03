<?php

namespace App\Providers;

use App\Models\OrgMembership;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\View;
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
    }
}
