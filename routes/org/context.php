<?php

use App\Http\Controllers\Org\OrgDashboardController;
use App\Http\Controllers\Org\EncodeSchoolYearController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OrgDashboardController::class, 'index'])
    ->name('org.home');

Route::post('/switch-org', [OrgDashboardController::class, 'switchOrg'])
    ->name('org.switch');

Route::get('/encode-school-year', [EncodeSchoolYearController::class, 'show'])
    ->name('org.encode-sy.show');

Route::post('/encode-school-year', [EncodeSchoolYearController::class, 'update'])
    ->name('org.encode-sy.update');