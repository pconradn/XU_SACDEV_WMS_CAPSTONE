<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Org\OrganizationInfoController;
use App\Http\Controllers\Org\OrganizationMemberRecordController;

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


    });