<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\BudgetItem;
use App\Models\FormType;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectDocument;
use Illuminate\Http\Request;

class DisbursementVoucherController extends Controller
{

    public function create(Project $project)
    {
        $activeOrgId = (int) session('active_org_id');
        $encodeSyId  = (int) session('encode_sy_id');

        if (
            $project->organization_id !== $activeOrgId ||
            $project->school_year_id !== $encodeSyId
        ) {
            abort(403);
        }

        $user = auth()->user();

        $projectHeadAssignment = ProjectAssignment::with('user')
            ->where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->first();

        $projectHead = $projectHeadAssignment?->user;



        $budgetDocument = ProjectDocument::with('budgetProposalData.items')
            ->where('project_id', $project->id)
            ->whereHas('formType', function ($q) {
                $q->where('code', 'BUDGET_PROPOSAL');
            })
            ->first();

        $budgetData = $budgetDocument?->budgetProposalData;

        $budgetItems = $budgetData?->items ?? collect();

  

        return view('org.projects.documents.disbursement-voucher.create', [

            'project' => $project,
            'projectHead' => $projectHead,

            'budgetDocument' => $budgetDocument,
            'budgetData' => $budgetData,
            'budgetItems' => $budgetItems,

        ]);
    }

    public function generate(Request $request, Project $project)
    {
        $data = $request->validate([

            'dv_date' => ['required','date'],

            'payment_type' => ['required','string'],
            'payment_mode' => ['required','string'],

            'items' => ['required','array','min:1'],
            'items.*' => ['integer','exists:budget_items,id'],

            'tax_amount' => ['nullable','numeric','min:0'],

        ]);


        $items = BudgetItem::whereIn('id', $data['items'])
            ->whereHas('budgetProposalData.document', function ($q) use ($project) {

                $q->where('project_id', $project->id);

            })
            ->get();
        
        if ($items->count() !== count($data['items'])) {

            abort(403, 'Invalid budget items selected.');

        }


        $subtotal = $items->sum('amount');

        $tax = (float) ($data['tax_amount'] ?? 0);

        $total = $subtotal - $tax;


        $projectHead = \App\Models\ProjectAssignment::with('user')
            ->where('project_id', $project->id)
            ->where('assignment_role', 'project_head')
            ->whereNull('archived_at')
            ->first()?->user;


        return view(
            'org.projects.documents.disbursement-voucher.print',
            [

                'project' => $project,
                'projectHead' => $projectHead,

                'items' => $items,

                'chargeAccounts' => $request->input('charge_account', []),

                'dvDate' => $data['dv_date'],
                'paymentType' => $data['payment_type'],
                'paymentMode' => $data['payment_mode'],

                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,

            ]
        );
    }




}