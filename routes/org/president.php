<?php

use App\Http\Controllers\Org\OfficerEntryController;
use App\Http\Controllers\Org\OfficerInviteController;
use App\Http\Controllers\Org\ProjectController;
use App\Http\Controllers\Org\OrgRoleAssignmentController;
use App\Http\Controllers\Org\ProjectHeadAssignmentController;
use App\Http\Controllers\Org\ActivationStatusController;
use Illuminate\Support\Facades\Route;


Route::resource('officers', OfficerEntryController::class)
    ->only(['index'])
    ->names('org.officers');

Route::put(
    'officers/{officer}/qpi',
    [OfficerEntryController::class, 'updateQpi']
    )->name('org.officers.update-qpi');



Route::post(
    'officers/{officer}/resend-invite',
    [OfficerInviteController::class, 'resend']
)->name('org.officers.resend-invite');


Route::resource('projects', ProjectController::class)
    ->except(['show', 'index'])
    ->names('org.projects');



Route::get(
    'assign-roles',
    [OrgRoleAssignmentController::class, 'edit']
)->name('org.assign-roles.edit');

Route::post(
    'assign-roles',
    [OrgRoleAssignmentController::class, 'update']
)->name('org.assign-roles.update');



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


Route::get(
    'activation-status',
    [ActivationStatusController::class, 'index']
)->name('org.activation-status.index');