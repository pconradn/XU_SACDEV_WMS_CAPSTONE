<?php

use App\Http\Controllers\Documents\BaseProjectDocumentController;
use App\Http\Controllers\Org\BudgetProposalController;
use App\Http\Controllers\Org\ClearanceController;
use App\Http\Controllers\Org\OffCampusApplicationController;
use App\Http\Controllers\Org\ProjectController;
use App\Http\Controllers\Org\ProjectDocumentHubController;
use App\Http\Controllers\Org\ProjectProposalController;
use App\Http\Controllers\Org\SellingApplicationController;
use App\Http\Controllers\Org\SolicitationApplicationController;
use Illuminate\Support\Facades\Route;

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


/*
|--------------------------------------------------------------------------
| Budget Proposal
|--------------------------------------------------------------------------
*/
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


/*
|--------------------------------------------------------------------------
| Off-Campus Activity Form
|--------------------------------------------------------------------------
*/

Route::get(
    'projects/{project}/documents/off-campus/guidelines',
    [OffCampusApplicationController::class, 'guidelines']
)->middleware([
    'project.access'
])->name('org.projects.off-campus.guidelines');


Route::post(
    'projects/{project}/documents/off-campus/acknowledge',
    [OffCampusApplicationController::class, 'acknowledgeGuidelines']
)->middleware([
    'project.access'
])->name('org.projects.off-campus.acknowledge');


Route::get(
    'projects/{project}/documents/off-campus/create',
    [OffCampusApplicationController::class, 'create']
)->middleware([
    'project.access',
    'org.role:project_head,president,moderator'
])->name('org.projects.off-campus.create');


Route::post(
    'projects/{project}/documents/off-campus',
    [OffCampusApplicationController::class, 'store']
)->middleware([
    'project.access',
    'project.role:project_head'
])->name('org.projects.off-campus.store');


Route::post(
    '/projects/{project}/documents/off-campus/approve',
    [OffCampusApplicationController::class, 'approve']
)->name('org.projects.off-campus.approve');

Route::post(
    '/projects/{project}/documents/off-campus/return',
    [OffCampusApplicationController::class, 'return']
)->name('org.projects.off-campus.return');

/*
|--------------------------------------------------------------------------
| Application for Solicitation / Sponsorship
|--------------------------------------------------------------------------
*/

Route::get(
    'projects/{project}/documents/solicitation/create',
    [SolicitationApplicationController::class, 'create']
)->middleware([
    'project.access',
    'org.role:project_head,president,moderator'
])->name('org.projects.solicitation.create');


Route::post(
    'projects/{project}/documents/solicitation',
    [SolicitationApplicationController::class, 'store']
)->middleware([
    'project.access',
    'project.role:project_head'
])->name('org.projects.solicitation.store');


Route::post(
    'projects/{project}/documents/solicitation/approve',
    [SolicitationApplicationController::class, 'approve']
)->middleware([
    'project.access',
    'project.role:president,moderator'
])->name('org.projects.solicitation.approve');


Route::post(
    'projects/{project}/documents/solicitation/return',
    [SolicitationApplicationController::class, 'return']
)->middleware([
    'project.access',
    'project.role:president,moderator'
])->name('org.projects.solicitation.return');


/*
|--------------------------------------------------------------------------
| Application for Selling
|--------------------------------------------------------------------------
*/

Route::get(
    'projects/{project}/documents/selling/create',
    [SellingApplicationController::class, 'create']
)->middleware([
    'project.access',
    'org.role:project_head,president,moderator'
])->name('org.projects.selling.create');


Route::post(
    'projects/{project}/documents/selling',
    [SellingApplicationController::class, 'store']
)->middleware([
    'project.access',
    'project.role:project_head'
])->name('org.projects.selling.store');


Route::post(
    'projects/{project}/documents/selling/approve',
    [SellingApplicationController::class, 'approve']
)->middleware([
    'project.access',
    'project.role:president,moderator'
])->name('org.projects.selling.approve');


Route::post(
    'projects/{project}/documents/selling/return',
    [SellingApplicationController::class, 'return']
)->middleware([
    'project.access',
    'project.role:president,moderator'
])->name('org.projects.selling.return');



/*
|--------------------------------------------------------------------------
| Disbursement Voucher Generator
|--------------------------------------------------------------------------
*/
Route::get(
    'projects/{project}/documents/disbursement-voucher',
    [\App\Http\Controllers\Org\DisbursementVoucherController::class, 'create']
)->middleware([
    'project.access'
])->name('org.projects.disbursement-voucher.create');


Route::post(
    'projects/{project}/documents/disbursement-voucher/generate',
    [\App\Http\Controllers\Org\DisbursementVoucherController::class, 'generate']
)->middleware([
    'project.access'
])->name('org.projects.disbursement-voucher.generate');




Route::get(
    '/projects/{project}/clearance/print',
    [ClearanceController::class, 'print']
    )->middleware([
    'project.access'
])->name('org.projects.clearance.print');

Route::post(
    '/projects/{project}/clearance/upload',
    [ClearanceController::class,'upload']
        )->middleware([
    'project.access'
]
)->name('org.projects.clearance.upload');