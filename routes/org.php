<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Org\OrgDashboardController;
use App\Http\Controllers\Org\EncodeSchoolYearController;
use App\Http\Controllers\Org\ActivationStatusController;

use App\Http\Controllers\Org\OfficerEntryController;
use App\Http\Controllers\Org\OfficerInviteController;

use App\Http\Controllers\Org\ProjectController;
use App\Http\Controllers\Org\OrgRoleAssignmentController;
use App\Http\Controllers\Org\ProjectHeadAssignmentController;

use App\Http\Controllers\Org\OrgReregDashboardController;
use App\Http\Controllers\Org\OrgReregAssignmentsController;
use App\Http\Controllers\Org\StrategicPlanController;
use App\Http\Controllers\Org\PresidentRegistrationController;
use App\Http\Controllers\Org\B3OfficerListController;
use App\Http\Controllers\Org\B4MembersListController;

use App\Http\Controllers\Org\ModeratorStrategicPlanController;

// Moderator
use App\Http\Controllers\Moderator\ModeratorReregDashboardController;
use App\Http\Controllers\Moderator\B5ModeratorSubmissionController;

Route::prefix('org')
    ->middleware(['auth', 'must_change_password', 'require.context'])
    ->group(function () {

        Route::get('/', [OrgDashboardController::class, 'index'])->name('org.home');
        Route::post('/switch-org', [OrgDashboardController::class, 'switchOrg'])->name('org.switch');

        Route::get('/encode-school-year', [EncodeSchoolYearController::class, 'show'])->name('org.encode-sy.show');
        Route::post('/encode-school-year', [EncodeSchoolYearController::class, 'update'])->name('org.encode-sy.update');

        /*
        |----------------------------------------------------------------------
        | PROVISIONING (ACTIVE SY PRESIDENT ONLY)
        |----------------------------------------------------------------------
        */
        Route::prefix('provision')
            ->middleware(['require_president_active_sy', 'require.context'])
            ->name('org.provision.')
            ->group(function () {
                Route::get('/next-president', [OrgReregAssignmentsController::class, 'editNextPresident'])
                    ->name('next_president.edit');
                Route::post('/next-president', [OrgReregAssignmentsController::class, 'storeNextPresident'])
                    ->name('next_president.store');
            });

        /*
        |----------------------------------------------------------------------
        | OPERATIONAL MODULES (ACTIVE SY ACCESS)
        |----------------------------------------------------------------------
        */
        Route::middleware(['operational_access', 'president_encode', 'require.context'])->group(function () {

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
        });

        /*
        |----------------------------------------------------------------------
        | RE-REGISTRATION HUB (PRESIDENT ONLY)
        |----------------------------------------------------------------------
        | Protect the hub itself so moderators cannot access /org/rereg.
        */
        Route::prefix('rereg')
            ->middleware(['org.ctx', 'org.role:president', 'require.context'])
            ->name('org.rereg.')
            ->group(function () {

                Route::get('/', [OrgReregDashboardController::class, 'index'])->name('index');
                Route::post('/set-sy', [OrgReregDashboardController::class, 'setSy'])->name('setSy');

                Route::prefix('b1')->name('b1.')->group(function () {
                    Route::get('/edit', [StrategicPlanController::class, 'edit'])->name('edit');
                    Route::post('/draft', [StrategicPlanController::class, 'saveDraft'])->name('draft');
                    Route::post('/submit', [StrategicPlanController::class, 'submitToModerator'])->name('submit');
                });

                Route::prefix('b2')->name('b2.')->group(function () {
                    Route::get('/president', [PresidentRegistrationController::class, 'index'])->name('president.index');
                    Route::get('/president/edit', [PresidentRegistrationController::class, 'edit'])->name('president.edit');
                    Route::post('/president/save-draft', [PresidentRegistrationController::class, 'saveDraft'])->name('president.saveDraft');
                    Route::post('/president/submit', [PresidentRegistrationController::class, 'submit'])->name('president.submit');
                    Route::post('/president/unsubmit', [PresidentRegistrationController::class, 'unsubmit'])->name('president.unsubmit');
                });

                Route::prefix('b3/officers-list')->name('b3.officers-list.')->group(function () {
                    Route::get('/', [B3OfficerListController::class, 'index'])->name('index');
                    Route::get('/edit', [B3OfficerListController::class, 'edit'])->name('edit');
                    Route::post('/save-draft', [B3OfficerListController::class, 'saveDraft'])->name('saveDraft');
                    Route::post('/submit', [B3OfficerListController::class, 'submit'])->name('submit');
                    Route::post('/unsubmit', [B3OfficerListController::class, 'unsubmit'])->name('unsubmit');
                    Route::post('/request-edit', [B3OfficerListController::class, 'requestEdit'])->name('requestEdit');
                });

                Route::prefix('b4/members-list')->name('b4.members-list.')->group(function () {
                    Route::get('/', [B4MembersListController::class, 'index'])->name('index');
                    Route::get('/edit', [B4MembersListController::class, 'edit'])->name('edit');
                    Route::post('/save', [B4MembersListController::class, 'save'])->name('save');
                });

                Route::prefix('assign')->name('assign.')->group(function () {
                    Route::get('/moderator', [OrgReregAssignmentsController::class, 'editModerator'])->name('moderator.edit');
                    Route::post('/moderator', [OrgReregAssignmentsController::class, 'storeModerator'])->name('moderator.store');
                });
            });

        /*
        |----------------------------------------------------------------------
        | MODERATOR PORTAL
        |----------------------------------------------------------------------
        */
        Route::prefix('moderator')
            ->middleware(['org.moderator', 'require.context'])
            ->name('org.moderator.')
            ->group(function () {

                Route::get('/rereg', [ModeratorReregDashboardController::class, 'index'])
                    ->name('rereg.dashboard');

                Route::get('/strategic-plans', [ModeratorStrategicPlanController::class, 'index'])
                    ->name('strategic_plans.index');

                Route::get('/strategic-plans/{submission}', [ModeratorStrategicPlanController::class, 'show'])
                    ->name('strategic_plans.show');

                Route::post('/strategic-plans/{submission}/return', [ModeratorStrategicPlanController::class, 'returnToOrg'])
                    ->name('strategic_plans.return');

                Route::post('/strategic-plans/{submission}/forward', [ModeratorStrategicPlanController::class, 'forwardToSacdev'])
                    ->name('strategic_plans.forward');

                Route::prefix('rereg/b5')->name('rereg.b5.')->group(function () {
                    Route::get('/', [B5ModeratorSubmissionController::class, 'index'])->name('index');
                    Route::get('/edit', [B5ModeratorSubmissionController::class, 'edit'])->name('edit');

                    Route::post('/save-draft', [B5ModeratorSubmissionController::class, 'saveDraft'])->name('saveDraft');
                    Route::post('/submit', [B5ModeratorSubmissionController::class, 'submit'])->name('submit');
                    Route::post('/unsubmit', [B5ModeratorSubmissionController::class, 'unsubmit'])->name('unsubmit');
                    Route::post('/request-edit', [B5ModeratorSubmissionController::class, 'requestEdit'])->name('requestEdit');

                    Route::post('/use-previous', [B5ModeratorSubmissionController::class, 'usePrevious'])->name('usePrevious');
                });
            });
    });
