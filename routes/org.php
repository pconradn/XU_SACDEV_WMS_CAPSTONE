<?php

use App\Http\Controllers\Org\OrganizationInfoController;
use App\Http\Controllers\Org\OrganizationMemberRecordController;
use App\Http\Controllers\Org\OrgDashboardController;
use App\Http\Controllers\Org\OrgReregDashboardController;
use App\Http\Controllers\Org\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('org')
    ->middleware(['auth', 'must_change_password', 'require.context','nocache'])
    ->group(function () {

        require __DIR__.'/org/context.php';




        /*
        |--------------------------------------------------------------------------
        | Operational modules
        |--------------------------------------------------------------------------
        */
        Route::middleware(['operational_access'])->group(function () {

            require __DIR__.'/org/projects.php';

            /*
            |--------------------------------------------------------------------------
            | President-only operational controls
            |--------------------------------------------------------------------------
            */
            Route::middleware(['president_encode', 'org.role:president'])->group(function () {

                require __DIR__.'/org/president.php';

            });

        });

        require __DIR__.'/org/rereg.php';

        require __DIR__.'/org/moderator.php';




        /*
        |--------------------------------------------------------------------------
        | Organization Info Hub
        |--------------------------------------------------------------------------
        */
        Route::get('/organization-info', [OrganizationInfoController::class, 'show'])
            ->name('org.organization-info.show');


        Route::prefix('profile')
            ->name('org.profile.')
            ->group(function () {

                Route::get('/{user?}', [ProfileController::class, 'edit'])
                    ->name('edit');

                Route::post('/', [ProfileController::class, 'update'])
                    ->name('update');

            });


        /*
        |--------------------------------------------------------------------------
        | Organization Members (General Members)
        |--------------------------------------------------------------------------
        */

        // VIEW (any org member with context)
        Route::get('/organization-members', [OrganizationMemberRecordController::class, 'index'])
            ->name('org.organization-members.index');

        // CREATE
        Route::post('/organization-members', [OrganizationMemberRecordController::class, 'store'])
            ->middleware(['org.role:president'])
            ->name('org.organization-members.store');

        // UPDATE
        Route::put('/organization-members/{id}', [OrganizationMemberRecordController::class, 'update'])
            ->middleware(['org.role:president'])
            ->name('org.organization-members.update');

        // DELETE (soft archive)
        Route::delete('/organization-members/{id}', [OrganizationMemberRecordController::class, 'destroy'])
            ->middleware(['org.role:president'])
            ->name('org.organization-members.destroy');

        Route::post('/rereg/constitution/upload', [OrgReregDashboardController::class, 'uploadConstitution'])
            ->name('org.rereg.constitution.upload');

        Route::get('/rereg/constitution/{id}/download', [OrgReregDashboardController::class, 'downloadConstitution'])
            ->name('org.rereg.constitution.download');

        Route::get('/dashboard/pending-tasks-partial', [OrgDashboardController::class, 'pendingTasksPartial'])
            ->name('org.dashboard.pending-tasks.partial');


    });