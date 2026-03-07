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
    'PROJECT_PROPOSAL'         => 'Project Proposal',
    'BUDGET_PROPOSAL'          => 'Budget Proposal',
    'OFF_CAMPUS_APPLICATION'   => 'Off-Campus Form',
    'SOLICITATION_APPLICATION' => 'Solicitation / Sponsorship Application',
];

$formRoutes = [
    'PROJECT_PROPOSAL'         => 'org.projects.project-proposal.create',
    'BUDGET_PROPOSAL'          => 'org.projects.budget-proposal.create',
    'OFF_CAMPUS_APPLICATION'   => 'org.projects.off-campus.create',
    'SOLICITATION_APPLICATION' => 'org.projects.solicitation.create',
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