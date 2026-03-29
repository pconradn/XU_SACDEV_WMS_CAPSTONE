<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Org\ProjectDocumentActionController;

class CombinedProposalController extends Controller
{
    protected ProjectProposalController $proposalController;
    protected BudgetProposalController $budgetController;
    protected ProjectDocumentActionController $actionController;

    public function __construct(
        ProjectProposalController $proposalController,
        BudgetProposalController $budgetController,
        ProjectDocumentActionController $actionController
    ) {
        $this->proposalController = $proposalController;
        $this->budgetController = $budgetController;
        $this->actionController = $actionController;
    }

    public function create(Request $request, Project $project)
    {
        $proposalView = $this->proposalController->create($request, $project);
        $budgetView   = $this->budgetController->create($project);

        return view('org.projects.documents.combined.create', [
            'project'      => $project,
            'proposalData' => $proposalView->getData(),
            'budgetData'   => $budgetView->getData(),
        ]);
    }

    public function store(Request $request, Project $project)
    {
        $action = $request->input('action', 'draft');

        DB::transaction(function () use ($request, $project) {

            //dd($request);
            $request->merge([
                'from_combined' => true,
            ]);
            $this->proposalController->store($request, $project);
            $this->budgetController->store($request, $project);

        });

        //dd($action);

        if ($action === 'submit') {
            return $this->submit($project);
        }

        return redirect()
            ->route('org.projects.documents.combined-proposal.create', $project)
            ->with('success', 'Saved as draft.');
    }

    public function submit(Project $project)
    {
        DB::transaction(function () use ($project) {

            $this->proposalController->submit($project);

            $this->budgetController->submit($project);

        });

        return redirect()
            ->route('org.projects.documents.combined-proposal.create', $project)
            ->with('success', 'Proposal and Budget submitted together.');
    }

    public function approve(Project $project)
    {


        $proposalDocument = $this->getDocumentByCode($project, 'PROJECT_PROPOSAL');
        $budgetDocument   = $this->getDocumentByCode($project, 'BUDGET_PROPOSAL');

        if (!$proposalDocument || !$budgetDocument) {
            return back()->with('error', 'Both proposal and budget documents must exist first.');
        }

        if ($proposalDocument->status !== 'submitted' || $budgetDocument->status !== 'submitted') {
            return back()->with('error', 'Both documents must be submitted before approval.');
        }

        $this->proposalController->approve($project);


        $current = $budgetDocument?->signatures()
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($current) {
            $this->budgetController->approve($project);
        }

        return back()->with('success', 'Combined proposal and budget approved.');
    }

    public function return(Request $request, Project $project)
    {
        $request->validate([
            'remarks' => ['required', 'string'],
        ]);

        $proposalDocument = $this->getDocumentByCode($project, 'PROJECT_PROPOSAL');
        $budgetDocument   = $this->getDocumentByCode($project, 'BUDGET_PROPOSAL');

        if (!$proposalDocument || !$budgetDocument) {
            return back()->with('error', 'Both proposal and budget documents must exist first.');
        }

        if ($proposalDocument->status !== 'submitted' || $budgetDocument->status !== 'submitted') {
            return back()->with('error', 'Both documents must be submitted before return.');
        }

        $this->proposalController->return($request, $project);
        $this->budgetController->return($request, $project);

        return back()->with('success', 'Combined proposal and budget returned for revision.');
    }

    protected function getDocumentByCode(Project $project, string $code): ?ProjectDocument
    {
        $formType = FormType::where('code', $code)->first();

        if (!$formType) {
            return null;
        }

        return ProjectDocument::where('project_id', $project->id)
            ->where('form_type_id', $formType->id)
            ->first();
    }

    public function requestEdit(Request $request, Project $project)
    {
        DB::transaction(function () use ($request, $project) {
            $this->actionController->requestEdit($project, 'PROJECT_PROPOSAL', $request);
            $this->actionController->requestEdit($project, 'BUDGET_PROPOSAL', $request);
        });

        return back()->with('success', 'Edit request submitted for both documents.');
    }

    public function retract(Project $project)
    {
        DB::transaction(function () use ($project) {
            $this->actionController->retract($project, 'PROJECT_PROPOSAL');
            $this->actionController->retract($project, 'BUDGET_PROPOSAL');
        });

        return back()->with('success', 'Approval retracted for both documents.');
    }

}