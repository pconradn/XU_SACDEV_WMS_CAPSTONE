<?php

use App\Http\Controllers\Org\OfficerEntryController;
use App\Http\Controllers\Org\OfficerInviteController;
use App\Http\Controllers\Org\ProjectController;
use App\Http\Controllers\Org\OrgRoleAssignmentController;
use App\Http\Controllers\Org\ProjectHeadAssignmentController;
use App\Http\Controllers\Org\ActivationStatusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Officers
|--------------------------------------------------------------------------
*/
Route::resource('officers', OfficerEntryController::class)
    ->except(['show'])
    ->names('org.officers');
Route::post(
    'officers/{officer}/resend-invite',
    [OfficerInviteController::class, 'resend']
)->name('org.officers.resend-invite');


/*
|--------------------------------------------------------------------------
| Projects CRUD
|--------------------------------------------------------------------------
*/
Route::resource('projects', ProjectController::class)
    ->except(['show', 'index'])
    ->names('org.projects');


/*
|--------------------------------------------------------------------------
| Assign org roles
|--------------------------------------------------------------------------
*/
Route::get(
    'assign-roles',
    [OrgRoleAssignmentController::class, 'edit']
)->name('org.assign-roles.edit');

Route::post(
    'assign-roles',
    [OrgRoleAssignmentController::class, 'update']
)->name('org.assign-roles.update');


/*
|--------------------------------------------------------------------------
| Assign project heads
|--------------------------------------------------------------------------
*/
Route::get(
    'assign-project-heads',
    [ProjectHeadAssignmentController::class, 'index']
)->name('org.assign-project-heads.index');

Route::get(
    'assign-project-heads/{project}',
    [ProjectHeadAssignmentController::class, 'edit']
)->middleware('project.access')
 ->name('org.assign-project-heads.edit');

Route::post(
    'assign-project-heads/{project}',
    [ProjectHeadAssignmentController::class, 'update']
)->middleware('project.access')
 ->name('org.assign-project-heads.update');


/*
|--------------------------------------------------------------------------
| Activation status
|--------------------------------------------------------------------------
*/
Route::get(
    'activation-status',
    [ActivationStatusController::class, 'index']
)->name('org.activation-status.index');