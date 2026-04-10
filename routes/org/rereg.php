<?php

use App\Http\Controllers\Org\B3OfficerListController;
use App\Http\Controllers\Org\B4MembersListController;
use App\Http\Controllers\Org\ModeratorSubmissionController;
use App\Http\Controllers\Org\OrgReregAssignmentsController;
use App\Http\Controllers\Org\OrgReregDashboardController;
use App\Http\Controllers\Org\PresidentProfileController;
use App\Http\Controllers\Org\PresidentRegistrationController;
use App\Http\Controllers\Org\StrategicPlanController;
use App\Http\Controllers\OrgConstitutionSubmissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Re-Registration Hub (President only)
|--------------------------------------------------------------------------
*/

Route::prefix('rereg')
    ->middleware(['require.context', 'org.role:president,moderator'])
    ->name('org.rereg.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/', [OrgReregDashboardController::class, 'index'])
            ->name('index');

        Route::post('/set-sy', [OrgReregDashboardController::class, 'setSy'])
            ->name('setSy');


        /*
        |--------------------------------------------------------------------------
        | B1 Strategic Plan
        |--------------------------------------------------------------------------
        */

        Route::prefix('b1')
            ->name('b1.')
            ->group(function () {

                Route::get('/edit', [StrategicPlanController::class, 'edit'])
                    ->name('edit');

                Route::post('/profile', [StrategicPlanController::class, 'saveProfile'])
                    ->name('profile.save');

                Route::post('/projects', [StrategicPlanController::class, 'storeProject'])
                    ->name('projects.store');

                Route::put('/projects/{project}', [StrategicPlanController::class, 'updateProject'])
                    ->name('projects.update');

                Route::delete('/projects/{project}', [StrategicPlanController::class, 'deleteProject'])
                    ->name('projects.delete');

                Route::post('/fund-sources', [StrategicPlanController::class, 'saveFundSources'])
                    ->name('funds.save');

                Route::post('/submit', [StrategicPlanController::class, 'submitToModerator'])
                    ->name('submit');

            });


        /*
        |--------------------------------------------------------------------------
        | B2 President Registration
        |--------------------------------------------------------------------------
        */

        Route::prefix('b2')
            ->name('b2.')
            ->group(function () {

                Route::get('/president', [PresidentRegistrationController::class, 'index'])
                    ->name('president.index');

                Route::get('/president/edit', [PresidentRegistrationController::class, 'edit'])
                    ->name('president.edit');

                Route::post('/president/save-draft', [PresidentRegistrationController::class, 'saveDraft'])
                    ->name('president.saveDraft');

                Route::post('/president/submit', [PresidentRegistrationController::class, 'submit'])
                    ->name('president.submit');

                Route::post('/president/unsubmit', [PresidentRegistrationController::class, 'unsubmit'])
                    ->name('president.unsubmit');

            });


        /*
        |--------------------------------------------------------------------------
        | B3 Officers List
        |--------------------------------------------------------------------------
        */

        Route::prefix('b3/officers-list')
            ->name('b3.officers-list.')
            ->group(function () {

                Route::get('/', [B3OfficerListController::class, 'index'])
                    ->name('index');

                Route::get('/edit', [B3OfficerListController::class, 'edit'])
                    ->name('edit');

                Route::post('/save-draft', [B3OfficerListController::class, 'saveDraft'])
                    ->name('saveDraft');

                Route::post('/submit', [B3OfficerListController::class, 'submit'])
                    ->name('submit');

                Route::post('/unsubmit', [B3OfficerListController::class, 'unsubmit'])
                    ->name('unsubmit');

                Route::post('/request-edit', [B3OfficerListController::class, 'requestEdit'])
                    ->name('requestEdit');

            });


        /*
        |--------------------------------------------------------------------------
        | B4 Members List
        |--------------------------------------------------------------------------
        */

        Route::prefix('b4/members-list')
            ->name('b4.members-list.')
            ->group(function () {

                Route::get('/', [B4MembersListController::class, 'index'])
                    ->name('index');

                Route::get('/edit', [B4MembersListController::class, 'edit'])
                    ->name('edit');

                Route::post('/save', [B4MembersListController::class, 'save'])
                    ->name('save');

            });


        /*
        |--------------------------------------------------------------------------
        | Assign Moderator
        |--------------------------------------------------------------------------
        */

        Route::prefix('assign')
            ->name('assign.')
            ->group(function () {

                Route::get('/moderator', [OrgReregAssignmentsController::class, 'editModerator'])
                    ->name('moderator.edit');

                Route::post('/moderator', [OrgReregAssignmentsController::class, 'storeModerator'])
                    ->name('moderator.store');

            });


        Route::prefix('moderator-submission')
            ->name('moderator.')
            ->group(function () {

                Route::get('/', [ModeratorSubmissionController::class, 'edit'])
                    ->name('edit');

                Route::post('/', [ModeratorSubmissionController::class, 'update'])
                    ->name('update');

            });




        /*
        |--------------------------------------------------------------------------
        | Constitution Upload
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/constitution/upload',
            [OrgConstitutionSubmissionController::class, 'upload']
        )->name('constitution.upload');


        Route::get(
            '/constitution/{submission}/download',
            [OrgConstitutionSubmissionController::class, 'download']
        )->name('constitution.download');

    });