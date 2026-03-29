<?php

use Illuminate\Support\Facades\Route;

Route::prefix('org')
    ->middleware(['auth', 'must_change_password', 'require.context'])
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




    });