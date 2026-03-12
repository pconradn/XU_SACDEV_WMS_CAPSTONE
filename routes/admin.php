<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMajorOfficerController;
use App\Http\Controllers\Admin\AdminOrgBySyController;
use App\Http\Controllers\Admin\AdminOrgReviewController;
use App\Http\Controllers\Admin\AdminPacketController;
use App\Http\Controllers\Admin\AdminProjectClearanceController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminProjectDocumentController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\OrgActivationController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\OrganizationPresidentController;
use App\Http\Controllers\Admin\SacdevB3OfficerSubmissionController;
use App\Http\Controllers\Admin\SacdevB4MemberListController;
use App\Http\Controllers\Admin\SacdevB5ModeratorSubmissionController;
use App\Http\Controllers\Admin\SacdevStrategicPlanController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\OrgConstitutionSubmissionController;
use App\Http\Controllers\SACDEV\SacdevB2PresidentRegistrationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->middleware(['auth', 'sacdev_admin', 'must_change_password'])
    ->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('admin.home');

        Route::get('/rereg', [\App\Http\Controllers\Admin\ReregHubController::class, 'index'])
            ->name('admin.rereg.index');

        Route::post('/rereg/set-sy', [\App\Http\Controllers\Admin\ReregHubController::class, 'setSy'])
            ->name('rereg.setSy');

        Route::get('/rereg/{organization}/hub', [\App\Http\Controllers\Admin\ReregHubController::class, 'hub'])
            ->name('rereg.hub');


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

        Route::post('/admin/rereg/{organization}/activate', [OrgActivationController::class, 'activate'])
            ->name('admin.rereg.activate');

        Route::prefix('strategic-plans')->name('admin.strategic_plans.')->group(function () {
            Route::get('/', [SacdevStrategicPlanController::class, 'index'])->name('index');
            Route::get('/{submission}', [SacdevStrategicPlanController::class, 'show'])->name('show');

            Route::post('/{submission}/return', [SacdevStrategicPlanController::class, 'returnToOrg'])->name('return');
            Route::post('/{submission}/approve', [SacdevStrategicPlanController::class, 'approve'])->name('approve');

            Route::post('/{submission}/revert-approval', [SacdevStrategicPlanController::class, 'revertApproval'])
                ->name('revert_approval');
        });

        Route::prefix('sacdev')->name('sacdev.')->group(function () {
            Route::get('/rereg', [\App\Http\Controllers\Sacdev\SacdevReregOverviewController::class, 'index'])
                ->name('rereg.overview');

            Route::post('/rereg/set-sy', [\App\Http\Controllers\Sacdev\SacdevReregOverviewController::class, 'setSy'])
                ->name('rereg.setSy');
        });



        Route::prefix('president-registrations')->name('admin.b2.president.')->group(function () {
            Route::get('/', [SacdevB2PresidentRegistrationController::class, 'index'])->name('index');
            Route::get('/{registration}', [SacdevB2PresidentRegistrationController::class, 'show'])->name('show');
            Route::post('/{registration}/return', [SacdevB2PresidentRegistrationController::class, 'returnToOrg'])->name('return');
            Route::post('/{registration}/approve', [SacdevB2PresidentRegistrationController::class, 'approve'])->name('approve');
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




        Route::get('president-assignments', [OrganizationPresidentController::class, 'index'])
            ->name('admin.president_assignments.index');

        Route::post('president-assignments/assign', [OrganizationPresidentController::class, 'assign'])
            ->name('admin.president_assignments.assign');


        Route::prefix('orgs-by-sy')->name('admin.orgs_by_sy.')->group(function () {
            Route::get('/', [AdminOrgBySyController::class, 'index'])->name('index');
            Route::post('/set-sy', [AdminOrgBySyController::class, 'setSy'])->name('set_sy');

            Route::get('/{organization}', [AdminOrgBySyController::class, 'show'])->name('show');

            Route::get('/{organization}/major-officers',
                [AdminMajorOfficerController::class, 'index'])
                ->name('major_officers');

            Route::post('/{organization}/major-officers',
                [AdminMajorOfficerController::class, 'update'])
                ->name('major_officers.update');
        });

        Route::post('/orgs-by-sy/{organization}/major-officers/{role}',
            [AdminMajorOfficerController::class, 'updateRole'])
            ->name('admin.orgs_by_sy.major_officers.update_role');




        Route::get(
            '/constitution/{submission}/download',
            [OrgConstitutionSubmissionController::class, 'download']
        )->name('admin.constitution.download');

        Route::post(
            '/constitution/{submission}/approve',
            [OrgConstitutionSubmissionController::class, 'approve']
        )->name('admin.constitution.approve');


        Route::get(
            '/orgs/{organization}/sy/{sy}/projects',
            [AdminProjectController::class, 'index']
        )->name('admin.org.projects.index');

        Route::get(
            '/projects/{project}/documents',
            [AdminProjectDocumentController::class, 'hub']
        )->name('admin.projects.documents.hub');

        Route::get(
            '/projects/{project}/documents/{formType}',
            [AdminProjectDocumentController::class, 'open']
        )->name('admin.projects.documents.open');


        Route::post(
            '/projects/{project}/documents/{formCode}/approve',
            [AdminProjectDocumentController::class, 'approve']
        )->name('admin.projects.documents.approve');

        Route::post(
            '/projects/{project}/documents/{formCode}/return',
            [AdminProjectDocumentController::class, 'return']
        )->name('admin.projects.documents.return');


        Route::post(
            '/projects/{project}/require-clearance',
            [AdminProjectController::class, 'requireClearance']
        )->name('admin.projects.require-clearance');


        Route::post(
            '/projects/{project}/clearance/verify',
            [AdminProjectClearanceController::class, 'verify']
        )->name('admin.projects.clearance.verify');

        Route::post(
            '/projects/{project}/clearance/return',
            [AdminProjectClearanceController::class, 'return']
        )->name('admin.projects.clearance.return');

        Route::post(
            '/projects/{project}/documents/{form}/retract',
            [AdminProjectDocumentController::class,'retract']
        )->name('admin.projects.documents.retract');


        /*
        |--------------------------------------------------------------------------
        | Packet Receiving
        |--------------------------------------------------------------------------
        */

        Route::prefix('packets')->name('admin.packets.')->group(function () {

            Route::get(
                '/receive',
                [AdminPacketController::class, 'receivePage']
            )->name('receive');

            Route::post(
                '/{packet}/mark-received',
                [AdminPacketController::class, 'markReceived']
            )->name('mark_received');


            Route::get(
                '/packets/lookup',
                [AdminPacketController::class, 'lookup']
            )->name('admin.packets.lookup');


        });

        /*
        |--------------------------------------------------------------------------
        | Project Submission Packets
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/projects/{project}/packets',
            [AdminPacketController::class, 'projectPackets']
        )->name('admin.projects.packets.index');

        Route::post(
            '/packets/{packet}/approve',
            [AdminPacketController::class, 'approve']
        )->name('admin.packets.approve');

        Route::post(
            '/packets/{packet}/return',
            [AdminPacketController::class, 'return']
        )->name('admin.packets.return');

        Route::post(
            '/packets/{packet}/revert-to-received',
            [AdminPacketController::class, 'revertToReceived']
        )->name('admin.packets.revert_received');

        Route::post(
            '/packets/{packet}/forward-to-finance',
            [AdminPacketController::class, 'forwardToFinance']
        )->name('admin.packets.forward_finance');

        Route::post(
            '/packets/{packet}/revert-from-finance',
            [AdminPacketController::class, 'revertFromFinance']
        )->name('admin.packets.revert_finance');

        Route::post(
            '/packets/{packet}/verify',
            [AdminPacketController::class, 'verify']
        )->name('admin.packets.verify');



    });
