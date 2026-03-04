<?php

use App\Http\Controllers\Org\BudgetProposalController;
use App\Http\Controllers\Org\ProjectController;
use App\Http\Controllers\Org\ProjectDocumentHubController;
use App\Http\Controllers\Org\ProjectProposalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Documents\BaseProjectDocumentController;

/*
|--------------------------------------------------------------------------
| Project list (shared)
|--------------------------------------------------------------------------
*/
Route::get('projects', [ProjectController::class, 'index'])
    ->name('org.projects.index');


/*
|--------------------------------------------------------------------------
| Project document hub
|--------------------------------------------------------------------------
*/
Route::get(
    'projects/{project}/documents',
    [ProjectDocumentHubController::class, 'show']
)->middleware('project.access')
 ->name('org.projects.documents.hub');


/*
|--------------------------------------------------------------------------
| Project Proposal 
|--------------------------------------------------------------------------
*/
Route::get(
    'projects/{project}/documents/project-proposal/create',
    [ProjectProposalController::class, 'create']
)->middleware([
    'project.access',
    'org.role:project_head,treasurer,president,moderator'
])->name('org.projects.project-proposal.create');


Route::post(
    'projects/{project}/documents/project-proposal',
    [ProjectProposalController::class, 'store']
)->middleware([
    'project.access',
    'project.role:project_head'
])->name('org.projects.project-proposal.store');




Route::post(
    'projects/{project}/documents/project-proposal/approve',
    [ProjectProposalController::class, 'approve']
)->middleware([
    'project.access',
    'project.role:treasurer,president,moderator'
])->name('org.projects.project-proposal.approve');


Route::post(
    'projects/{project}/documents/project-proposal/return',
    [ProjectProposalController::class, 'return']
)->middleware([
    'project.access',
    'project.role:treasurer,president,moderator'
])->name('org.projects.project-proposal.return');


Route::get(
    'projects/{project}/documents/budget-proposal/create',
    [BudgetProposalController::class, 'create']
)->middleware([
    'project.access',
    'org.role:project_head,treasurer,president,moderator'
])->name('org.projects.budget-proposal.create');


Route::post(
    'projects/{project}/documents/budget-proposal',
    [BudgetProposalController::class, 'store']
)->middleware([
    'project.access',
    'project.role:project_head'
])->name('org.projects.budget-proposal.store');


Route::post(
    'projects/{project}/documents/budget-proposal/approve',
    [BudgetProposalController::class, 'approve']
)->middleware([
    'project.access',
    'project.role:treasurer,president,moderator'
])->name('org.projects.budget-proposal.approve');


Route::post(
    'projects/{project}/documents/budget-proposal/return',
    [BudgetProposalController::class, 'return']
)->middleware([
    'project.access',
    'project.role:treasurer,president,moderator'
])->name('org.projects.budget-proposal.return');