<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

<table class="min-w-full text-sm">

<thead class="bg-slate-50 border-b border-slate-200">

<tr class="text-left text-slate-700 font-semibold">

<th class="px-6 py-4">
Document
</th>

<th class="px-6 py-4 w-[160px]">
Status
</th>

<th class="px-6 py-4 w-[220px] text-right">
Action
</th>

</tr>

</thead>

<tbody class="divide-y divide-slate-200">

@php

$types = [

    'PROJECT_PROPOSAL'                   => 'Project Proposal',
    'BUDGET_PROPOSAL'                    => 'Budget Proposal',

    'OFF_CAMPUS_APPLICATION'             => 'Off-Campus Form',

    'SOLICITATION_APPLICATION'           => 'Solicitation / Sponsorship Application',
    'SELLING_APPLICATION'                => 'Selling Application',

    'REQUEST_TO_PURCHASE'                => 'Request to Purchase',

    'FEES_COLLECTION_REPORT'             => 'Fees Collection Report',
    'SELLING_ACTIVITY_REPORT'            => 'Selling Activity Report',
    'SOLICITATION_SPONSORSHIP_REPORT'    => 'Solicitation / Sponsorship Report',
    'TICKET_SELLING_REPORT'              => 'Ticket Selling Report',

    'DOCUMENTATION_REPORT'               => 'Documentation Report',
    'LIQUIDATION_REPORT'                 => 'Liquidation Report',

];

$formRoutes = [

    'PROJECT_PROPOSAL'                   => 'org.projects.documents.project-proposal.create',
    'BUDGET_PROPOSAL'                    => 'org.projects.documents.budget-proposal.create',

    'OFF_CAMPUS_APPLICATION'             => 'org.projects.documents.off-campus.create',

    'SOLICITATION_APPLICATION'           => 'org.projects.documents.solicitation.create',
    'SELLING_APPLICATION'                => 'org.projects.documents.selling.create',

    'REQUEST_TO_PURCHASE'                => 'org.projects.documents.request-to-purchase.create',

    'FEES_COLLECTION_REPORT'             => 'org.projects.documents.fees-collection.create',
    'SELLING_ACTIVITY_REPORT'            => 'org.projects.documents.selling-activity-report.create',
    'SOLICITATION_SPONSORSHIP_REPORT'    => 'org.projects.documents.solicitation-sponsorship-report.create',
    'TICKET_SELLING_REPORT'              => 'org.projects.documents.ticket-selling-report.create',

    'DOCUMENTATION_REPORT'               => 'org.projects.documents.documentation-report.create',
    'LIQUIDATION_REPORT'                 => 'org.projects.documents.liquidation-report.create',


    
];

@endphp


@foreach($types as $code => $label)

@include('admin.projects.documents.partials._document-row', [
    'code' => $code,
    'label' => $label,
    'doc' => $documents[$code] ?? null,
    'project' => $project
])

@endforeach

</tbody>

</table>

</div>