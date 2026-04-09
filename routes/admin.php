<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminExternalPacketController;
use App\Http\Controllers\Admin\AdminMajorOfficerController;
use App\Http\Controllers\Admin\AdminMemberController;
use App\Http\Controllers\Admin\AdminOfficerController;
use App\Http\Controllers\Admin\AdminOrgBySyController;
use App\Http\Controllers\Admin\AdminOrgReviewController;
use App\Http\Controllers\Admin\AdminPacketController;
use App\Http\Controllers\Admin\AdminProjectClearanceController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminProjectDocumentController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CoaAssignmentController;
use App\Http\Controllers\Admin\OrgActivationController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\OrganizationPresidentController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SacdevB3OfficerSubmissionController;
use App\Http\Controllers\Admin\SacdevB4MemberListController;
use App\Http\Controllers\Admin\SacdevB5ModeratorSubmissionController;
use App\Http\Controllers\Admin\SacdevStrategicPlanController;
use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\OrgConstitutionSubmissionController;
use App\Http\Controllers\SACDEV\SacdevB2PresidentRegistrationController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'sacdev_admin', 'must_change_password','nocache'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::middleware('permission:users.manage')->group(function () {
            Route::resource('users', UserController::class);
        });

        Route::middleware('permission:roles.manage')->group(function () {
            Route::resource('roles', RoleController::class);
        });

        Route::middleware('permission:context.manage')->group(function () {
            Route::resource('organizations', OrganizationController::class);
            Route::resource('school-years', SchoolYearController::class);
        });

    });

Route::prefix('admin')
    ->middleware(['auth','sacdev_admin','must_change_password'])
    ->group(function () {

        Route::prefix('projects/{project}/external-packets')
            ->middleware(['permission:projects.view'])
            ->group(function () {

                Route::get('/', [AdminExternalPacketController::class, 'index'])
                    ->name('admin.external-packets.index');

                Route::get('/create', [AdminExternalPacketController::class, 'create'])
                    ->name('admin.external-packets.create');

                Route::post('/', [AdminExternalPacketController::class, 'store'])
                    ->name('admin.external-packets.store');

                Route::get('/{packet}', [AdminExternalPacketController::class, 'show'])
                    ->name('admin.external-packets.show');

                Route::get('/{packet}/print', [AdminExternalPacketController::class, 'print'])
                    ->name('admin.external-packets.print');

                Route::post('/{packet}/submit', [AdminExternalPacketController::class, 'submit'])
                    ->name('admin.external-packets.submit');

                Route::delete('/{packet}', [AdminExternalPacketController::class, 'archive'])
                    ->name('admin.external-packets.archive');
            });

        Route::prefix('external-packets')
            ->middleware(['permission:projects.view'])
            ->group(function () {

                Route::get('/receive', [AdminExternalPacketController::class, 'receivePage'])
                    ->name('admin.external-packets.receive');

                Route::post('/receive/lookup', [AdminExternalPacketController::class, 'lookup'])
                    ->name('admin.external-packets.lookup');

                Route::post('/{packet}/process', [AdminExternalPacketController::class, 'processReceive'])
                    ->name('admin.external-packets.process');
            });

    });



Route::get('/admin/organizations/{organization}/open', [OrganizationController::class, 'open'])
    ->middleware(['auth', 'sacdev_admin', 'must_change_password', 'permission:projects.view'])
    ->name('organizations.open');

Route::get('/search', [SearchController::class, 'index'])
    ->middleware(['auth', 'sacdev_admin', 'must_change_password'])
    ->name('search.index');

Route::prefix('admin')
    ->middleware(['auth', 'sacdev_admin', 'must_change_password'])
    ->group(function () {


        Route::middleware('permission:projects.view')->group(function () {

            Route::get('/', [AdminDashboardController::class, 'index'])
                ->name('admin.home');

            Route::get('/rereg', [\App\Http\Controllers\Admin\ReregHubController::class, 'index'])
                ->name('admin.rereg.index');

            Route::post('/rereg/set-sy', [\App\Http\Controllers\Admin\ReregHubController::class, 'setSy'])
                ->name('rereg.setSy');

            Route::get('/rereg/{organization}/hub', [\App\Http\Controllers\Admin\ReregHubController::class, 'hub'])
                ->name('rereg.hub');

            Route::get('review', [AdminOrgReviewController::class, 'index'])
                ->name('admin.review.index');

            Route::get('review/show', [AdminOrgReviewController::class, 'show'])
                ->name('admin.review.show');

            Route::get('audit-logs', [AuditLogController::class, 'index'])
                ->name('admin.audit-logs.index');

        });



        Route::middleware('permission:context.manage')->group(function () {

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

            Route::post('/admin/rereg/{organization}/activate', [OrgActivationController::class, 'activate'])
                ->name('admin.rereg.activate');



            Route::get('/coa', [CoaAssignmentController::class, 'index'])
                ->middleware('permission:users.manage')
                ->name('admin.coa.index');

            Route::post('/coa/bulk-update', [CoaAssignmentController::class, 'bulkUpdate'])
                ->middleware('permission:users.manage')
                ->name('admin.coa.bulk-update');;

        });

            

        });


        Route::middleware('permission:context.manage')->group(function () {
            Route::get('president-assignments', [OrganizationPresidentController::class, 'index'])
                ->name('admin.president_assignments.index');

            Route::post('president-assignments/assign', [OrganizationPresidentController::class, 'assign'])
                ->name('admin.president_assignments.assign');
        });



        Route::prefix('strategic-plans')->name('admin.strategic_plans.')->group(function () {

            Route::get('/', [SacdevStrategicPlanController::class, 'index'])
                ->middleware('permission:projects.view')->name('index');

            Route::get('/{submission}', [SacdevStrategicPlanController::class, 'show'])
                ->middleware('permission:projects.view')->name('show');

            Route::post('/{submission}/return', [SacdevStrategicPlanController::class, 'returnToOrg'])
                ->middleware('permission:projects.return')->name('return');

            Route::post('/{submission}/approve', [SacdevStrategicPlanController::class, 'approve'])
                ->middleware('permission:projects.approve')->name('approve');

            Route::post('/{submission}/revert-approval', [SacdevStrategicPlanController::class, 'revertApproval'])
                ->middleware('permission:projects.approve')->name('revert_approval');


                
        });

        Route::get('/constitution/{submission}/download',[OrgConstitutionSubmissionController::class, 'download'])
            ->middleware('permission:projects.view')->name('admin.constitution.download');

        Route::post('/constitution/{submission}/approve', [OrgConstitutionSubmissionController::class, 'approve'])
            ->middleware('permission:projects.approve')->name('admin.constitution.approve');



        Route::prefix('sacdev')->name('sacdev.')->group(function () {

            Route::get('/rereg', [\App\Http\Controllers\Sacdev\SacdevReregOverviewController::class, 'index'])
                ->middleware('permission:projects.view')
                ->name('rereg.overview');

            Route::post('/rereg/set-sy', [\App\Http\Controllers\Sacdev\SacdevReregOverviewController::class, 'setSy'])
                ->middleware('permission:projects.view')
                ->name('rereg.setSy');

            Route::get('/officers', [AdminOfficerController::class, 'index'])
                ->middleware('permission:projects.view')
                ->name('officers.index');

            Route::put('/officers/{officer}/override-suspension',
                [AdminOfficerController::class, 'overrideSuspension'])->middleware('permission:projects.approve')->name('officers.override-suspension');

            Route::put('/officers/{officer}/suspend',
                [AdminOfficerController::class, 'suspend'])->middleware('permission:projects.approve')->name('officers.suspend');                

            Route::get('/members', [AdminMemberController::class, 'index'])
                ->middleware('permission:projects.view')->name('members.index');               
        });


        Route::prefix('president-registrations')->name('admin.b2.president.')->group(function () {

            Route::get('/', [SacdevB2PresidentRegistrationController::class, 'index'])
                ->middleware('permission:projects.view')->name('index');

            Route::get('/{registration}', [SacdevB2PresidentRegistrationController::class, 'show'])
                ->middleware('permission:projects.view')->name('show');

            Route::post('/{registration}/return', [SacdevB2PresidentRegistrationController::class, 'returnToOrg'])
                ->middleware('permission:projects.return')->name('return');

            Route::post('/{registration}/approve', [SacdevB2PresidentRegistrationController::class, 'approve'])
                ->middleware('permission:projects.approve')->name('approve');
        });


        Route::prefix('officer-submissions')->name('admin.officer_submissions.')->group(function () {

            Route::get('/', [SacdevB3OfficerSubmissionController::class, 'index'])
                ->middleware('permission:projects.view')->name('index');

            Route::get('/{submission}', [SacdevB3OfficerSubmissionController::class, 'show'])
                ->middleware('permission:projects.view')->name('show');

            Route::post('/{submission}/return', [SacdevB3OfficerSubmissionController::class, 'returnToOrg'])
                ->middleware('permission:projects.return')->name('return');

            Route::post('/{submission}/approve', [SacdevB3OfficerSubmissionController::class, 'approve'])
                ->middleware('permission:projects.approve')->name('approve');

            Route::post('/{submission}/allow-edit', [SacdevB3OfficerSubmissionController::class, 'allowEdit'])
                ->middleware('permission:documents.manage')
                ->name('allow_edit');
        });



        Route::prefix('member-lists')->name('admin.member_lists.')->group(function () {

            Route::get('/', [SacdevB4MemberListController::class, 'index'])
                ->middleware('permission:projects.view')->name('index');

            Route::get('/{list}', [SacdevB4MemberListController::class, 'show'])
                ->middleware('permission:projects.view')->name('show');
        });


        Route::prefix('moderator-submissions')->name('admin.moderator_submissions.')->group(function () {

            Route::get('/', [SacdevB5ModeratorSubmissionController::class, 'index'])
                ->middleware('permission:projects.view')->name('index');

            Route::get('/{submission}', [SacdevB5ModeratorSubmissionController::class, 'show'])
                ->middleware('permission:projects.view')->name('show');

            Route::post('/{submission}/return', [SacdevB5ModeratorSubmissionController::class, 'returnToModerator'])
                ->middleware('permission:projects.return')->name('return');

            Route::post('/{submission}/approve', [SacdevB5ModeratorSubmissionController::class, 'approve'])
                ->middleware('permission:projects.approve')->name('approve');

            Route::post('/{submission}/allow-edit', [SacdevB5ModeratorSubmissionController::class, 'allowEdit'])
                ->middleware('permission:documents.manage')->name('allow_edit');

            Route::post('/{submission}/revert-approval', [SacdevB5ModeratorSubmissionController::class, 'revertApproval'])
                ->middleware('permission:projects.approve')->name('revert_approval');
        });




        Route::prefix('admin')->middleware('permission:projects.view')->group(function () {


            Route::get(
                '/projects/{project}/documents/combined-proposal',
                [AdminProjectDocumentController::class, 'openCombined']
            )->name('admin.projects.documents.combined-proposal.open');

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

            Route::get(
                '/projects/{project}/documents/{form}/print/{document?}',
                [AdminProjectDocumentController::class, 'showPrint']
            )->name('admin.projects.documents.print');

            Route::get(
                '/projects/{project}/packets',
                [AdminPacketController::class, 'projectPackets']
            )->name('admin.projects.packets.index');



        });


        Route::middleware('permission:projects.view')->group(function () {
            Route::prefix('orgs-by-sy')->name('admin.orgs_by_sy.')->group(function () {
                Route::get('/', [AdminOrgBySyController::class, 'index'])->name('index');
                Route::post('/set-sy', [AdminOrgBySyController::class, 'setSy'])->name('set_sy');

                Route::get('/{organization}', [AdminOrgBySyController::class, 'show'])->name('show');

                Route::get('/{organization}/major-officers',
                    [AdminMajorOfficerController::class, 'index'])
                    ->name('major_officers');

                Route::post('/{organization}/major-officers',
                    [AdminMajorOfficerController::class, 'update'])
                    ->middleware('permission:projects.approve')
                    ->name('major_officers.update');
            });

            Route::post('/orgs-by-sy/{organization}/major-officers/{role}',
                [AdminMajorOfficerController::class, 'updateRole'])
                ->middleware('permission:projects.approve')
                ->name('admin.orgs_by_sy.major_officers.update_role');
        });



        Route::middleware('permission:documents.manage')->group(function () {


            

            Route::prefix('/projects/{project}/documents')->group(function () {


                Route::prefix('combined-proposal')->name('admin.projects.documents.combined-proposal.')->group(function () {

                    Route::post('approve',
                        [AdminProjectDocumentController::class, 'combinedApprove']
                    )->middleware('permission:projects.approve')
                    ->name('approve');

                    Route::post('return',
                        [AdminProjectDocumentController::class, 'combinedReturn']
                    )->middleware('permission:projects.return')
                    ->name('return');

                    Route::post('retract',
                        [AdminProjectDocumentController::class, 'combinedRetract']
                    )->name('retract');

                    Route::post('allow-edit',
                        [AdminProjectDocumentController::class, 'combinedAllowEdit']
                    )->name('allow-edit');

                });

                Route::post('{formCode}/approve',
                    [AdminProjectDocumentController::class, 'approve']
                )->middleware('permission:projects.approve')
                 ->name('admin.projects.documents.approve');

                Route::post('{formCode}/return',
                    [AdminProjectDocumentController::class, 'return']
                )->middleware('permission:projects.return')
                 ->name('admin.projects.documents.return');

                Route::post('{formCode}/allow-edit',
                    [AdminProjectDocumentController::class, 'allowEdit']
                )->name('admin.projects.documents.allow-edit');

                Route::post('{formCode}/retract',
                    [AdminProjectDocumentController::class, 'retract']
                )->name('admin.projects.documents.retract');
            });

   

        });


        Route::middleware('permission:projects.approve')->group(function () {

            Route::post(
                '/projects/{project}/mark-complete',
                [AdminProjectDocumentController::class, 'markComplete']
            )->name('admin.projects.mark-complete');
            Route::post(
                '/projects/{project}/require-clearance',
                [AdminProjectController::class, 'requireClearance']
            )->name('admin.projects.require-clearance');

            Route::post(
                '/projects/{project}/clearance/verify',
                [AdminProjectClearanceController::class, 'verify']
            )->name('admin.projects.clearance.verify');

            Route::post('/projects/{project}/retract-complete', [AdminProjectDocumentController::class, 'retractComplete'])
                ->name('admin.projects.retract-complete');

            Route::post('/projects/{project}/retract-clearance', [AdminProjectController::class, 'retractClearance'])
                ->name('admin.projects.retract-clearance');

        });

        Route::middleware('permission:projects.return')->group(function () {

            Route::post(
                '/projects/{project}/clearance/return',
                [AdminProjectClearanceController::class, 'return']
            )->name('admin.projects.clearance.return');
        });


        Route::prefix('packets')->name('admin.packets.')->middleware('permission:documents.manage')->group(function () {

            Route::get('/receive', [AdminPacketController::class, 'receivePage'])->name('receive');

            Route::post('/{packet}/mark-received', [AdminPacketController::class, 'markReceived'])->name('mark_received');

            Route::get('/packets/lookup', [AdminPacketController::class, 'lookup'])->name('admin.packets.lookup');
        });


        Route::middleware('permission:projects.view')->group(function () {

            Route::get(
                '/projects/{project}/packets',[AdminPacketController::class, 'projectPackets'])->name('admin.projects.packets.index');
        });

        Route::middleware('permission:projects.approve')->group(function () {

            Route::post('/packets/{packet}/approve', [AdminPacketController::class, 'approve'])
                ->name('admin.packets.approve');

            Route::post('/packets/{packet}/forward-to-finance', [AdminPacketController::class, 'forwardToFinance'])
                ->name('admin.packets.forward_finance');

            Route::post('/packets/{packet}/verify', [AdminPacketController::class, 'verify'])
                ->name('admin.packets.verify');
        });

        Route::middleware('permission:projects.return')->group(function () {

            Route::post('/packets/{packet}/return', [AdminPacketController::class, 'return'])
                ->name('admin.packets.return');

            Route::post('/packets/{packet}/revert-to-received', [AdminPacketController::class, 'revertToReceived'])
                ->name('admin.packets.revert_received');

            Route::post('/packets/{packet}/revert-from-finance', [AdminPacketController::class, 'revertFromFinance'])
                ->name('admin.packets.revert_finance');
        });

