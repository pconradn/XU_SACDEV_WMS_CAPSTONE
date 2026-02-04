<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Org\ProjectController;

// Admin
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\ForcedPasswordController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Org\OfficerEntryController;
use App\Http\Controllers\Org\OrgDashboardController;
use App\Http\Controllers\Org\B3OfficerListController;

// Org
use App\Http\Controllers\Org\B4MembersListController;
use App\Http\Controllers\Org\OfficerInviteController;
use App\Http\Controllers\Org\StrategicPlanController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrgReviewController;
use App\Http\Controllers\Org\ActivationStatusController;
use App\Http\Controllers\Org\EncodeSchoolYearController;
use App\Http\Controllers\Org\OrgReregDashboardController;
use App\Http\Controllers\Org\OrgRoleAssignmentController;
use App\Http\Controllers\Org\OrgReregAssignmentsController;
use App\Http\Controllers\Admin\SacdevB4MemberListController;
use App\Http\Controllers\Admin\SacdevStrategicPlanController;
use App\Http\Controllers\Org\PresidentRegistrationController;
use App\Http\Controllers\Org\ProjectHeadAssignmentController;
use App\Http\Controllers\Org\ModeratorStrategicPlanController;
use App\Http\Controllers\Admin\OrganizationPresidentController;
use App\Http\Controllers\Admin\SacdevB3OfficerSubmissionController;
use App\Http\Controllers\Moderator\B5ModeratorSubmissionController;
use App\Http\Controllers\Admin\SacdevB5ModeratorSubmissionController;
use App\Http\Controllers\SACDEV\SacdevB2PresidentRegistrationController;

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

        Route::prefix('strategic-plans')->name('admin.strategic_plans.')->group(function () {
            Route::get('/', [SacdevStrategicPlanController::class, 'index'])->name('index');
            Route::get('/{submission}', [SacdevStrategicPlanController::class, 'show'])->name('show');

            Route::post('/{submission}/return', [SacdevStrategicPlanController::class, 'returnToOrg'])->name('return');
            Route::post('/{submission}/approve', [SacdevStrategicPlanController::class, 'approve'])->name('approve');

            // only for reverting approval (requires remarks)
            Route::post('/{submission}/revert-approval', [SacdevStrategicPlanController::class, 'revertApproval'])
                ->name('revert_approval');
        });


        Route::prefix('president-registrations')->name('admin.b2.president.')->group(function () {

            // List all B-2 President Registrations
            Route::get('/', [SacdevB2PresidentRegistrationController::class, 'index'])
                ->name('index');

            // View a specific B-2 registration
            Route::get('/{registration}', [SacdevB2PresidentRegistrationController::class, 'show'])
                ->name('show');

            // Return to organization with required remarks
            Route::post('/{registration}/return', [SacdevB2PresidentRegistrationController::class, 'returnToOrg'])
                ->name('return');

            // Approve registration
            Route::post('/{registration}/approve', [SacdevB2PresidentRegistrationController::class, 'approve'])
                ->name('approve');
        });


        Route::prefix('officer-submissions')->name('admin.officer_submissions.')->group(function () {
            Route::get('/', [SacdevB3OfficerSubmissionController::class, 'index'])->name('index');
            Route::get('/{submission}', [SacdevB3OfficerSubmissionController::class, 'show'])->name('show');

            Route::post('/{submission}/return', [SacdevB3OfficerSubmissionController::class, 'returnToOrg'])->name('return');
            Route::post('/{submission}/approve', [SacdevB3OfficerSubmissionController::class, 'approve'])->name('approve');

            Route::post('/{submission}/allow-edit', [SacdevB3OfficerSubmissionController::class, 'allowEdit'])
                ->name('allow_edit');
        });

        Route::prefix('member-lists')->name('admin.member_lists.')->group(function () {
            Route::get('/', [SacdevB4MemberListController::class, 'index'])->name('index');
            Route::get('/{list}', [SacdevB4MemberListController::class, 'show'])->name('show');
        });


        Route::prefix('moderator-submissions')->name('admin.moderator_submissions.')->group(function () {
            Route::get('/', [SacdevB5ModeratorSubmissionController::class, 'index'])->name('index');
            Route::get('/{submission}', [SacdevB5ModeratorSubmissionController::class, 'show'])->name('show');

            Route::post('/{submission}/return', [SacdevB5ModeratorSubmissionController::class, 'returnToModerator'])->name('return');
            Route::post('/{submission}/approve', [SacdevB5ModeratorSubmissionController::class, 'approve'])->name('approve');

            Route::post('/{submission}/allow-edit', [SacdevB5ModeratorSubmissionController::class, 'allowEdit'])->name('allow_edit');

            Route::post('/{submission}/revert-approval', [SacdevB5ModeratorSubmissionController::class, 'revertApproval'])->name('revert_approval');
        });


    });

/*
|--------------------------------------------------------------------------
| Org Portal
|--------------------------------------------------------------------------
*/
Route::prefix('org')
    ->middleware(['auth', 'must_change_password'])  
    ->group(function () {

        Route::get('/', [OrgDashboardController::class, 'index'])->name('org.home');
        Route::post('/switch-org', [OrgDashboardController::class, 'switchOrg'])->name('org.switch');

        // Encode SY selector (we'll restrict which SYs can appear in the controller)
        Route::get('/encode-school-year', [EncodeSchoolYearController::class, 'show'])->name('org.encode-sy.show');
        Route::post('/encode-school-year', [EncodeSchoolYearController::class, 'update'])->name('org.encode-sy.update');

        /*
        |----------------------------------------------------------------------
        | PROVISIONING (ACTIVE SY PRESIDENT ONLY)
        |----------------------------------------------------------------------
        */
        Route::prefix('provision')
            ->middleware(['require_president_active_sy'])
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
        | These should remain blocked for SY2-only users.
        */
        Route::middleware(['operational_access', 'president_encode'])->group(function () {

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
        | RE-REGISTRATION HUB (FUTURE SY OK)
        |----------------------------------------------------------------------
        */
        Route::prefix('rereg')
            ->middleware(['org.ctx']) // requires active_org_id + encode_sy_id
            ->name('org.rereg.')
            ->group(function () {

                Route::get('/', [OrgReregDashboardController::class, 'index'])->name('index');
                Route::post('/set-sy', [OrgReregDashboardController::class, 'setSy'])->name('setSy');

                Route::middleware(['org.role:president'])->group(function () {

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
                        Route::get('/moderator', [OrgReregAssignmentsController::class, 'editModerator'])
                            ->name('moderator.edit');
                        Route::post('/moderator', [OrgReregAssignmentsController::class, 'storeModerator'])
                            ->name('moderator.store');
                    });
                });
            });

        /*
        |----------------------------------------------------------------------
        | MODERATOR PORTAL (should also NOT require active_sy_access)
        |----------------------------------------------------------------------
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

                Route::prefix('rereg/b5')->name('rereg.b5.')->group(function () {
                    Route::get('/', [B5ModeratorSubmissionController::class, 'index'])->name('index');
                    Route::get('/edit', [B5ModeratorSubmissionController::class, 'edit'])->name('edit');
                    Route::post('/save-draft', [B5ModeratorSubmissionController::class, 'saveDraft'])->name('saveDraft');
                    Route::post('/submit', [B5ModeratorSubmissionController::class, 'submit'])->name('submit');
                    Route::post('/unsubmit', [B5ModeratorSubmissionController::class, 'unsubmit'])->name('unsubmit');
                    Route::post('/request-edit', [B5ModeratorSubmissionController::class, 'requestEdit'])->name('requestEdit');
                });
            });
    });

    require __DIR__.'/auth.php';
