<?php

use App\Http\Controllers\Org\ActivityNoticeController;
use App\Http\Controllers\Org\BudgetProposalController;
use App\Http\Controllers\Org\ClearanceController;
use App\Http\Controllers\Org\CombinedProposalController;
use App\Http\Controllers\Org\DisbursementVoucherController;
use App\Http\Controllers\Org\DocumentationReportController;
use App\Http\Controllers\Org\FeesCollectionReportController;
use App\Http\Controllers\Org\LiquidationReportController;
use App\Http\Controllers\Org\OffCampusApplicationController;
use App\Http\Controllers\Org\ProjectController;
use App\Http\Controllers\Org\ProjectDocumentActionController;
use App\Http\Controllers\Org\ProjectDocumentHubController;
use App\Http\Controllers\Org\ProjectProposalController;
use App\Http\Controllers\Org\RequestToPurchaseController;
use App\Http\Controllers\Org\SellingActivityReportController;
use App\Http\Controllers\Org\SellingApplicationController;
use App\Http\Controllers\Org\SolicitationApplicationController;
use App\Http\Controllers\Org\SolicitationSponsorshipReportController;
use App\Http\Controllers\Org\StudentTravelFormController;
use App\Http\Controllers\Org\TicketSellingReportController;
use App\Http\Controllers\ProjectAgreementController;
use App\Http\Controllers\SubmissionPacketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Project list
|--------------------------------------------------------------------------
*/
Route::get('projects', [ProjectController::class, 'index'])
    ->name('org.projects.index');

/*
|--------------------------------------------------------------------------
| Project-scoped routes
|--------------------------------------------------------------------------
*/
Route::prefix('projects/{project}')
    ->middleware('project.access')
    ->name('org.projects.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Shared project pages
        |--------------------------------------------------------------------------
        */
        Route::get('documents', [ProjectDocumentHubController::class, 'showV2'])
            ->name('documents.hub');

        Route::post('agreement/accept', [ProjectAgreementController::class, 'accept'])
            ->name('agreement.accept');

        Route::get('clearance/print', [ClearanceController::class, 'print'])
            ->name('clearance.print');

        Route::post('clearance/upload', [ClearanceController::class, 'upload'])
            ->name('clearance.upload');

        Route::post('clearance/reissue', [ClearanceController::class, 'reissue'])
            ->name('clearance.reissue');

   
        $standardDocument = function (
            string $prefix,
            string $name,
            string $controller,
            string $createRoles,
            string $approveRoles,
            string $createMethod = 'create',
            string $storeMethod = 'store',
            string $approveMethod = 'approve',
            string $returnMethod = 'return',
            bool $createUsesCreateSuffix = true
        ) {
            Route::prefix("documents/{$prefix}")
                ->name("documents.{$name}.")
                ->group(function () use (
                    $controller,
                    $createRoles,
                    $approveRoles,
                    $createMethod,
                    $storeMethod,
                    $approveMethod,
                    $returnMethod,
                    $createUsesCreateSuffix
                ) {
                    Route::get(
                        $createUsesCreateSuffix ? 'create' : '/',
                        [$controller, $createMethod]
                    )->middleware("org.role:{$createRoles}")
                     ->name('create');

                    Route::post('/', [$controller, $storeMethod])
                        ->middleware('project.role:project_head')
                        ->name('store');

                    Route::post('approve', [$controller, $approveMethod])
                        ->middleware("project.role:{$approveRoles}")
                        ->name('approve');

                    Route::post('return', [$controller, $returnMethod])
                        ->middleware("project.role:{$approveRoles}")
                        ->name('return');
                });
        };

        /*
        |--------------------------------------------------------------------------
        | Standard documents
        |--------------------------------------------------------------------------
        */
        $standardDocument(
            prefix: 'project-proposal',
            name: 'project-proposal',
            controller: ProjectProposalController::class,
            createRoles: 'project_head,treasurer,finance_officer,president,moderator',
            approveRoles: 'treasurer,finance_officer,president,moderator'
        );

        $standardDocument(
            prefix: 'budget-proposal',
            name: 'budget-proposal',
            controller: BudgetProposalController::class,
            createRoles: 'project_head,treasurer,finance_officer,president,moderator',
            approveRoles: 'treasurer,finance_officer,president,moderator'
        );

        $standardDocument(
            prefix: 'solicitation',
            name: 'solicitation',
            controller: SolicitationApplicationController::class,
            createRoles: 'project_head,president,moderator',
            approveRoles: 'president,moderator'
        );

        $standardDocument(
            prefix: 'selling',
            name: 'selling',
            controller: SellingApplicationController::class,
            createRoles: 'project_head,treasurer,finance_officer,president,moderator',
            approveRoles: 'president,moderator'
        );

        $standardDocument(
            prefix: 'request-to-purchase',
            name: 'request-to-purchase',
            controller: RequestToPurchaseController::class,
            createRoles: 'project_head,treasurer,finance_officer,president,moderator',
            approveRoles: 'president,moderator,treasurer,finance_officer'
        );

        $standardDocument(
            prefix: 'fees-collection',
            name: 'fees-collection',
            controller: FeesCollectionReportController::class,
            createRoles: 'project_head,treasurer,finance_officer,president,moderator',
            approveRoles: 'president,moderator'
        );

        $standardDocument(
            prefix: 'selling-activity-report',
            name: 'selling-activity-report',
            controller: SellingActivityReportController::class,
            createRoles: 'project_head,treasurer,finance_officer,president,moderator',
            approveRoles: 'president,moderator',
            createUsesCreateSuffix: false
        );

        $standardDocument(
            prefix: 'solicitation-sponsorship-report',
            name: 'solicitation-sponsorship-report',
            controller: SolicitationSponsorshipReportController::class,
            createRoles: 'project_head,treasurer,finance_officer,president,moderator',
            approveRoles: 'president,moderator',
            createUsesCreateSuffix: false
        );

        $standardDocument(
            prefix: 'ticket-selling-report',
            name: 'ticket-selling-report',
            controller: TicketSellingReportController::class,
            createRoles: 'project_head,treasurer,finance_officer,president,moderator',
            approveRoles: 'sacdev_admin',
            createUsesCreateSuffix: false
        );

        $standardDocument(
            prefix: 'documentation-report',
            name: 'documentation-report',
            controller: DocumentationReportController::class,
            createRoles: 'project_head,treasurer,finance_officer,president,moderator',
            approveRoles: 'president,moderator,sacdev_admin',
            createUsesCreateSuffix: false
        );

        $standardDocument(
            prefix: 'liquidation-report',
            name: 'liquidation-report',
            controller: LiquidationReportController::class,
            createRoles: 'project_head,president,moderator,treasurer,finance_officer',
            approveRoles: 'president,moderator,sacdev_admin,treasurer,finance_officer',
            createUsesCreateSuffix: false
        );

        /*
        |--------------------------------------------------------------------------
        | Combined Pre-Implementation
        |--------------------------------------------------------------------------
        */

        Route::prefix('combined-proposal')->name('documents.combined-proposal.')->group(function () {
            Route::get('/', [CombinedProposalController::class, 'create'])
                ->middleware('org.role:project_head,treasurer,finance_officer,president,moderator')
                ->name('create');

            Route::post('/', [CombinedProposalController::class, 'store'])
                ->middleware('project.role:project_head')
                ->name('store');

            Route::post('approve', [CombinedProposalController::class, 'approve'])
                ->middleware('project.role:treasurer,finance_officer,president,moderator')
                ->name('approve');

            Route::post('return', [CombinedProposalController::class, 'return'])
                ->middleware('project.role:treasurer,finance_officer,president,moderator')
                ->name('return');

            Route::post('request-edit', [CombinedProposalController::class, 'requestEdit'])
                ->middleware('project.role:project_head')
                ->name('request-edit');

            Route::post('retract', [CombinedProposalController::class, 'retract'])
                ->middleware('project.role:treasurer,finance_officer,president,moderator')
                ->name('retract');
                        
        });


        /*
        |--------------------------------------------------------------------------
        | Off-campus application
        |--------------------------------------------------------------------------
        */
        Route::prefix('documents/off-campus')
            ->name('documents.off-campus.')
            ->group(function () {
                Route::get('guidelines', [OffCampusApplicationController::class, 'guidelines'])
                    ->name('guidelines');

                Route::post('acknowledge', [OffCampusApplicationController::class, 'acknowledgeGuidelines'])
                    ->name('acknowledge');

                Route::get('create', [OffCampusApplicationController::class, 'create'])
                    ->middleware('org.role:project_head,president,moderator')
                    ->name('create');

                Route::post('/', [OffCampusApplicationController::class, 'store'])
                    ->middleware('project.role:project_head')
                    ->name('store');

                Route::post('approve', [OffCampusApplicationController::class, 'approve'])
                    ->middleware('project.role:president,moderator')
                    ->name('approve');

                Route::post('return', [OffCampusApplicationController::class, 'return'])
                    ->middleware('project.role:president,moderator')
                    ->name('return');

                    
                Route::get('/travel-form', [StudentTravelFormController::class, 'create'])
                    ->name('travel-form.create');

                // Generate printable form
                Route::post('/travel-form/generate', [StudentTravelFormController::class, 'generate'])
                    ->name('travel-form.generate');


            });

        /*
        |--------------------------------------------------------------------------
        | Activity notices
        |--------------------------------------------------------------------------
        */
        Route::prefix('documents')
            ->name('documents.')
            ->group(function () {

                Route::prefix('postponement')
                    ->name('postponement.')
                    ->group(function () {
                        Route::get('create', [ActivityNoticeController::class, 'createPostponement'])
                            ->middleware('org.role:project_head,president,moderator')
                            ->name('create');

                        Route::get('{document}', [ActivityNoticeController::class, 'editPostponement'])
                            ->middleware('document.type:POSTPONEMENT_NOTICE')
                            ->name('edit');

                        Route::post('{document}', [ActivityNoticeController::class, 'storePostponement'])
                            ->middleware([
                                'project.role:project_head',
                                'document.type:POSTPONEMENT_NOTICE',
                            ])->name('store');

                        Route::post('{document}/approve', [ActivityNoticeController::class, 'approve'])
                            ->middleware([
                                'project.role:president,moderator',
                                'document.type:POSTPONEMENT_NOTICE',
                            ])->name('approve');

                        Route::post('{document}/return', [ActivityNoticeController::class, 'return'])
                            ->middleware([
                                'project.role:president,moderator',
                                'document.type:POSTPONEMENT_NOTICE',
                            ])->name('return');
                    });

                Route::prefix('cancellation')
                    ->name('cancellation.')
                    ->group(function () {
                        Route::get('create', [ActivityNoticeController::class, 'createCancellation'])
                            ->middleware('org.role:project_head,president,moderator')
                            ->name('create');

                        Route::get('{document}', [ActivityNoticeController::class, 'editCancellation'])
                            ->middleware('document.type:CANCELLATION_NOTICE')
                            ->name('edit');

                        Route::post('{document}', [ActivityNoticeController::class, 'storeCancellation'])
                            ->middleware([
                                'project.role:project_head',
                                'document.type:CANCELLATION_NOTICE',
                            ])->name('store');

                        Route::post('{document}/approve', [ActivityNoticeController::class, 'approve'])
                            ->middleware([
                                'project.role:president,moderator',
                                'document.type:CANCELLATION_NOTICE',
                            ])->name('approve');

                        Route::post('{document}/return', [ActivityNoticeController::class, 'return'])
                            ->middleware([
                                'project.role:president,moderator',
                                'document.type:CANCELLATION_NOTICE',
                            ])->name('return');
                    });

                Route::delete('notices/{document}', [ActivityNoticeController::class, 'archive'])
                    ->middleware('project.role:project_head')
                    ->name('notices.archive');
            });

        /*
        |--------------------------------------------------------------------------
        | Disbursement voucher
        |--------------------------------------------------------------------------
        */
        Route::prefix('documents/disbursement-voucher')
            ->name('documents.disbursement-voucher.')
            ->group(function () {
                Route::get('/', [DisbursementVoucherController::class, 'create'])
                    ->name('create');

                Route::post('generate', [DisbursementVoucherController::class, 'generate'])
                    ->name('generate');
            });

        /*
        |--------------------------------------------------------------------------
        | Submission packets
        |--------------------------------------------------------------------------
        */
        Route::prefix('packets')
            ->name('packets.')
            ->group(function () {
                Route::get('/', [SubmissionPacketController::class, 'index'])
                    ->name('index');

                Route::post('create', [SubmissionPacketController::class, 'create'])
                    ->middleware('project.role:project_head')
                    ->name('create');

                Route::get('{packet}', [SubmissionPacketController::class, 'show'])
                    ->name('show');

                Route::post('{packet}/update', [SubmissionPacketController::class, 'update'])
                    ->middleware('project.role:project_head')
                    ->name('update');

                Route::delete('{packet}', [SubmissionPacketController::class, 'destroy'])
                    ->middleware('project.role:project_head')
                    ->name('destroy');

                Route::get('{packet}/print', [SubmissionPacketController::class, 'print'])
                    ->name('print');

                Route::post('{packet}/letters', [SubmissionPacketController::class, 'addLetter'])
                    ->middleware('project.role:project_head')
                    ->name('letters.store');

                Route::delete('{packet}/letters/{letter}', [SubmissionPacketController::class, 'removeLetter'])
                    ->middleware('project.role:project_head')
                    ->name('letters.destroy');

                Route::post('{packet}/dvs', [SubmissionPacketController::class, 'addDv'])
                    ->middleware('project.role:project_head')
                    ->name('dvs.store');

                Route::delete('{packet}/dvs/{dv}', [SubmissionPacketController::class, 'removeDv'])
                    ->middleware('project.role:project_head')
                    ->name('dvs.destroy');

                Route::post('{packet}/receipts', [SubmissionPacketController::class, 'addReceipt'])
                    ->middleware('project.role:project_head')
                    ->name('receipts.store');

                Route::delete('{packet}/receipts/{receipt}', [SubmissionPacketController::class, 'removeReceipt'])
                    ->middleware('project.role:project_head')
                    ->name('receipts.destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | Generic project document actions
        |--------------------------------------------------------------------------
        */
        Route::prefix('documents/{formCode}')
            ->name('documents.')
            ->group(function () {
                Route::post('request-edit', [ProjectDocumentActionController::class, 'requestEdit'])
                    ->middleware('project.role:project_head')
                    ->name('request-edit');

                Route::post('retract', [ProjectDocumentActionController::class, 'retract'])
                    ->name('retract');
            });
    });