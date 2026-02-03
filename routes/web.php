<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ForcedPasswordController;

// Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\AdminOrgReviewController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\OrganizationPresidentController;

// Org
use App\Http\Controllers\Org\OrgDashboardController;
use App\Http\Controllers\Org\EncodeSchoolYearController;
use App\Http\Controllers\Org\OfficerEntryController;
use App\Http\Controllers\Org\OfficerInviteController;
use App\Http\Controllers\Org\ProjectController;
use App\Http\Controllers\Org\OrgRoleAssignmentController;
use App\Http\Controllers\Org\ProjectHeadAssignmentController;
use App\Http\Controllers\Org\ActivationStatusController;
use App\Http\Controllers\Org\StrategicPlanController;
use App\Http\Controllers\Org\ModeratorStrategicPlanController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (! $user) {
        return redirect()->route('login');
    }

    if ($user->system_role === 'sacdev_admin') {
        return redirect()->route('admin.home');
    }

    return redirect()->route('org.home');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Force Change Password
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/force-change-password', [ForcedPasswordController::class, 'show'])
        ->name('password.force.form');

    Route::post('/force-change-password', [ForcedPasswordController::class, 'update'])
        ->name('password.force.update');
});

/*
|--------------------------------------------------------------------------
| Admin Portal
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'sacdev_admin', 'must_change_password'])
    ->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('admin.home');

        Route::resource('school-years', SchoolYearController::class)
            ->names('admin.school-years');

        Route::patch('school-years/{schoolYear}/activate', [SchoolYearController::class, 'activate'])
            ->name('admin.school-years.activate');

        Route::get('organizations/assign-president', [OrganizationPresidentController::class, 'create'])
            ->name('admin.organizations.assign-president');

        Route::post('organizations/assign-president', [OrganizationPresidentController::class, 'store'])
            ->name('admin.organizations.assign-president.store');

        Route::resource('organizations', OrganizationController::class)
            ->except(['show'])
            ->names('admin.organizations');

        Route::get('review', [AdminOrgReviewController::class, 'index'])
            ->name('admin.review.index');

        Route::get('review/show', [AdminOrgReviewController::class, 'show'])
            ->name('admin.review.show');

        Route::get('audit-logs', [AuditLogController::class, 'index'])
            ->name('admin.audit-logs.index');
    });

/*
|--------------------------------------------------------------------------
| Org Portal
|--------------------------------------------------------------------------
*/
Route::prefix('org')
    ->middleware(['auth', 'active_sy_access', 'must_change_password'])
    ->group(function () {

        Route::get('/', [OrgDashboardController::class, 'index'])
            ->name('org.home');

        Route::post('/switch-org', [OrgDashboardController::class, 'switchOrg'])
            ->name('org.switch');

        Route::get('/encode-school-year', [EncodeSchoolYearController::class, 'show'])
            ->name('org.encode-sy.show');

        Route::post('/encode-school-year', [EncodeSchoolYearController::class, 'update'])
            ->name('org.encode-sy.update');

        /*
        |--------------------------------------------------------------------------
        | PRESIDENT-ONLY (Encoding / Management)
        |--------------------------------------------------------------------------
        */
        Route::middleware(['president_encode'])->group(function () {

            Route::resource('officers', OfficerEntryController::class)
                ->except(['show'])
                ->names('org.officers');

            Route::post('officers/{officer}/resend-invite', [OfficerInviteController::class, 'resend'])
                ->name('org.officers.resend-invite');

            Route::resource('projects', ProjectController::class)
                ->except(['show'])
                ->names('org.projects');

            Route::get('assign-roles', [OrgRoleAssignmentController::class, 'edit'])
                ->name('org.assign-roles.edit');

            Route::post('assign-roles', [OrgRoleAssignmentController::class, 'update'])
                ->name('org.assign-roles.update');

            Route::get('assign-project-heads', [ProjectHeadAssignmentController::class, 'index'])
                ->name('org.assign-project-heads.index');

            Route::get('assign-project-heads/{project}', [ProjectHeadAssignmentController::class, 'edit'])
                ->name('org.assign-project-heads.edit');

            Route::post('assign-project-heads/{project}', [ProjectHeadAssignmentController::class, 'update'])
                ->name('org.assign-project-heads.update');

            Route::get('activation-status', [ActivationStatusController::class, 'index'])
                ->name('org.activation-status.index');

            /*
            |--------------------------------------------------------------------------
            | Strategic Plan (B-1) – Org Side
            |--------------------------------------------------------------------------
            */
            Route::get('strategic-plan', [StrategicPlanController::class, 'edit'])
                ->name('org.strategic_plan.edit');

            Route::post('strategic-plan/draft', [StrategicPlanController::class, 'saveDraft'])
                ->name('org.strategic_plan.draft');

            Route::post('strategic-plan/submit', [StrategicPlanController::class, 'submitToModerator'])
                ->name('org.strategic_plan.submit');

            Route::get('strategic-plan/select-sy', [StrategicPlanController::class, 'selectSy'])
                ->name('org.strategic_plan.select_sy');

            Route::post('strategic-plan/select-sy', [StrategicPlanController::class, 'storeSelectedSy'])
                ->name('org.strategic_plan.select_sy.store');
        });

        /*
        |--------------------------------------------------------------------------
        | MODERATOR (Review Only)
        |--------------------------------------------------------------------------
        */
        Route::middleware(['org.moderator'])
            ->prefix('moderator')
            ->name('org.moderator.')
            ->group(function () {

                Route::get('strategic-plans', [ModeratorStrategicPlanController::class, 'index'])
                    ->name('strategic_plans.index');

                Route::get('strategic-plans/{submission}', [ModeratorStrategicPlanController::class, 'show'])
                    ->name('strategic_plans.show');

                Route::post('strategic-plans/{submission}/return', [ModeratorStrategicPlanController::class, 'returnToOrg'])
                    ->name('strategic_plans.return');

                Route::post('strategic-plans/{submission}/forward', [ModeratorStrategicPlanController::class, 'forwardToSacdev'])
                    ->name('strategic_plans.forward');
            });
    });

require __DIR__.'/auth.php';
