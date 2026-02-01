<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Org\ProjectController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\ForcedPasswordController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Org\OfficerEntryController;
use App\Http\Controllers\Org\OrgDashboardController;
use App\Http\Controllers\Org\OfficerInviteController;
use App\Http\Controllers\Org\StrategicPlanController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrgReviewController;
use App\Http\Controllers\Org\ActivationStatusController;
use App\Http\Controllers\Org\EncodeSchoolYearController;
use App\Http\Controllers\Org\OrgRoleAssignmentController;
use App\Http\Controllers\Org\ProjectHeadAssignmentController;
use App\Http\Controllers\Admin\OrganizationPresidentController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->system_role === 'sacdev_admin') {
        return redirect()->route('admin.home');
    }

    return redirect()->route('org.home');
})->middleware(['auth'])->name('dashboard');


// force-change password
Route::middleware(['auth'])->group(function () {
    Route::get('/force-change-password', [ForcedPasswordController::class, 'show'])
        ->name('password.force.form');

    Route::post('/force-change-password', [ForcedPasswordController::class, 'update'])
        ->name('password.force.update');
});


// Admin portal
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


        Route::get('review', [AdminOrgReviewController::class, 'index'])->name('admin.review.index');

        Route::get('review/show', [AdminOrgReviewController::class, 'show'])->name('admin.review.show');
        
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('admin.audit-logs.index');
    });



//Org Portal
Route::prefix('org')
    ->middleware(['auth', 'active_sy_access', 'must_change_password'])
    ->group(function () {
        Route::get('/', [OrgDashboardController::class, 'index'])->name('org.home');
        Route::post('/switch-org', [OrgDashboardController::class, 'switchOrg'])->name('org.switch');
        Route::get('/encode-school-year', [EncodeSchoolYearController::class, 'show'])->name('org.encode-sy.show');
        Route::post('/encode-school-year', [EncodeSchoolYearController::class, 'update'])->name('org.encode-sy.update');

        // President encoding pages
        Route::middleware(['president_encode'])->group(function () {
            Route::resource('officers', OfficerEntryController::class)
                ->except(['show'])
                ->names('org.officers');

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

            Route::post('officers/{officer}/resend-invite', [OfficerInviteController::class, 'resend'])->name('org.officers.resend-invite');    

            // Strategic Plan (B-1)
            Route::get('strategic-plan', [StrategicPlanController::class, 'edit'])
                ->name('strategic_plan.edit');

            Route::post('strategic-plan/draft', [StrategicPlanController::class, 'saveDraft'])
                ->name('strategic_plan.draft');

            Route::post('strategic-plan/submit', [StrategicPlanController::class, 'submitToModerator'])
                ->name('strategic_plan.submit');

        });


    });

require __DIR__.'/auth.php';
