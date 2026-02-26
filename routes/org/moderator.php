<?php

use App\Http\Controllers\Moderator\B5ModeratorSubmissionController;
use App\Http\Controllers\Moderator\ModeratorReregDashboardController;
use App\Http\Controllers\Org\ModeratorStrategicPlanController;
use App\Http\Controllers\OrgConstitutionSubmissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Moderator Portal
|--------------------------------------------------------------------------
*/

Route::prefix('moderator')
    ->middleware(['require.context', 'org.moderator'])
    ->name('org.moderator.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Moderator Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/rereg', [ModeratorReregDashboardController::class, 'index'])
            ->name('rereg.dashboard');


        /*
        |--------------------------------------------------------------------------
        | Strategic Plans Review
        |--------------------------------------------------------------------------
        */

        Route::get('/strategic-plans', [ModeratorStrategicPlanController::class, 'index'])
            ->name('strategic_plans.index');

        Route::get('/strategic-plans/{submission}', [ModeratorStrategicPlanController::class, 'show'])
            ->name('strategic_plans.show');

        Route::post('/strategic-plans/{submission}/return', [ModeratorStrategicPlanController::class, 'returnToOrg'])
            ->name('strategic_plans.return');

        Route::post('/strategic-plans/{submission}/forward', [ModeratorStrategicPlanController::class, 'forwardToSacdev'])
            ->name('strategic_plans.forward');


        /*
        |--------------------------------------------------------------------------
        | B5 Moderator Submission
        |--------------------------------------------------------------------------
        */

        Route::prefix('rereg/b5')
            ->name('rereg.b5.')
            ->group(function () {

                Route::get('/', [B5ModeratorSubmissionController::class, 'index'])
                    ->name('index');

                Route::get('/edit', [B5ModeratorSubmissionController::class, 'edit'])
                    ->name('edit');

                Route::post('/save-draft', [B5ModeratorSubmissionController::class, 'saveDraft'])
                    ->name('saveDraft');

                Route::post('/submit', [B5ModeratorSubmissionController::class, 'submit'])
                    ->name('submit');

                Route::post('/unsubmit', [B5ModeratorSubmissionController::class, 'unsubmit'])
                    ->name('unsubmit');

                Route::post('/request-edit', [B5ModeratorSubmissionController::class, 'requestEdit'])
                    ->name('requestEdit');

                Route::post('/use-previous', [B5ModeratorSubmissionController::class, 'usePrevious'])
                    ->name('usePrevious');

            });


        /*
        |--------------------------------------------------------------------------
        | Constitution Download
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/constitution/{submission}/download',
            [OrgConstitutionSubmissionController::class, 'download']
        )->name('constitution.download');

    });