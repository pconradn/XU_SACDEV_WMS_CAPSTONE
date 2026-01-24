<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Org\ProjectController;
use App\Http\Controllers\ForcedPasswordController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Org\OfficerEntryController;
use App\Http\Controllers\Org\OrgDashboardController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Org\EncodeSchoolYearController;
use App\Http\Controllers\Org\OrgRoleAssignmentController;
use App\Http\Controllers\Org\ProjectHeadAssignmentController;
use App\Http\Controllers\Admin\OrganizationPresidentController;

Route::get('/', function () {
    return view('welcome');
});

// Breeze default "dashboard" — make it redirect to the correct portal
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


// Force-change password (must be reachable even when must_change_password is true)
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

        Route::get('/', function () {
            return view('portals.admin-home');
        })->name('admin.home');

        // School Years
        Route::resource('school-years', SchoolYearController::class)
            ->names('admin.school-years');

        Route::patch('school-years/{schoolYear}/activate', [SchoolYearController::class, 'activate'])
            ->name('admin.school-years.activate');

        // Assign/Provision President (put BEFORE organizations resource)
        Route::get('organizations/assign-president', [OrganizationPresidentController::class, 'create'])
            ->name('admin.organizations.assign-president');

        Route::post('organizations/assign-president', [OrganizationPresidentController::class, 'store'])
            ->name('admin.organizations.assign-president.store');

        // Organizations CRUD (no show)
        Route::resource('organizations', OrganizationController::class)
            ->except(['show'])
            ->names('admin.organizations');
    });



// Org portal
Route::prefix('org')
    ->middleware(['auth', 'active_sy_access', 'must_change_password'])
    ->group(function () {
        Route::get('/', [OrgDashboardController::class, 'index'])->name('org.home');
        Route::post('/switch-org', [OrgDashboardController::class, 'switchOrg'])->name('org.switch');
        Route::get('/encode-school-year', [EncodeSchoolYearController::class, 'show'])->name('org.encode-sy.show');
        Route::post('/encode-school-year', [EncodeSchoolYearController::class, 'update'])->name('org.encode-sy.update');

        // President encoding pages (require org selected + president + encode_sy session)
        Route::middleware(['president_encode'])->group(function () {
            Route::resource('officers', OfficerEntryController::class)
                ->except(['show'])
                ->names('org.officers');

            Route::resource('projects', ProjectController::class)
                ->except(['show'])
                ->names('org.projects');
                // Assign Treasurer/Moderator
            Route::get('assign-roles', [OrgRoleAssignmentController::class, 'edit'])
                ->name('org.assign-roles.edit');
            Route::post('assign-roles', [OrgRoleAssignmentController::class, 'update'])
                ->name('org.assign-roles.update');

            // Assign Project Head per Project
            Route::get('assign-project-heads', [ProjectHeadAssignmentController::class, 'index'])
                ->name('org.assign-project-heads.index');
            Route::get('assign-project-heads/{project}', [ProjectHeadAssignmentController::class, 'edit'])
                ->name('org.assign-project-heads.edit');
            Route::post('assign-project-heads/{project}', [ProjectHeadAssignmentController::class, 'update'])
                ->name('org.assign-project-heads.update');
        });


    });

require __DIR__.'/auth.php';
