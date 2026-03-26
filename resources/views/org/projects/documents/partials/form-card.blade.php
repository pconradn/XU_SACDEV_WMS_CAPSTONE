@php

$formType = $form->formType;
$document = $form->document;
$required = $form->required ?? false;
$allowed = $form->allowed ?? true;


$formRoutes = [
    'PROJECT_PROPOSAL' => 'org.projects.documents.project-proposal.create',
    'BUDGET_PROPOSAL'  => 'org.projects.documents.budget-proposal.create',
    'OFF_CAMPUS_APPLICATION' => 'org.projects.documents.off-campus.guidelines',
    'SOLICITATION_APPLICATION' => 'org.projects.documents.solicitation.create',
    'SELLING_APPLICATION' => 'org.projects.documents.selling.create',
    'REQUEST_TO_PURCHASE' => 'org.projects.documents.request-to-purchase.create',

    
    'FEES_COLLECTION_REPORT' => 'org.projects.documents.fees-collection.create',
    'SELLING_ACTIVITY_REPORT' => 'org.projects.documents.selling-activity-report.create',
    'SOLICITATION_SPONSORSHIP_REPORT' => 'org.projects.documents.solicitation-sponsorship-report.create',
    'TICKET_SELLING_REPORT' => 'org.projects.documents.ticket-selling-report.create',


    'DOCUMENTATION_REPORT' => 'org.projects.documents.documentation-report.create',
    'LIQUIDATION_REPORT' => 'org.projects.documents.liquidation-report.create',
];

$routeName = $formRoutes[$formType->code] ?? null;


/*
|--------------------------------------------------------------------------
| Status Logic
|--------------------------------------------------------------------------
*/

$statusText = 'Not created';
$statusColor = 'bg-slate-400';
$awaitingText = null;

if ($document) {

    if ($document->status === 'draft') {

        if ($document->remarks) {
            $statusText = 'Returned for Revision';
            $statusColor = 'bg-rose-500';
        } else {
            $statusText = 'Draft';
            $statusColor = 'bg-amber-400';
        }

    }

    elseif ($document->status === 'submitted') {

        $statusText = 'Submitted';
        $statusColor = 'bg-blue-500';

        if(method_exists($document,'nextPendingRole')){
            $awaitingText = $document->nextPendingRole2();
        }

    }

    elseif ($document->status === 'approved_by_sacdev') {

        $statusText = 'Approved';
        $statusColor = 'bg-emerald-500';

    }

}


/*
|--------------------------------------------------------------------------
| Awaiting Role Color
|--------------------------------------------------------------------------
*/

$awaitingColor = 'bg-slate-500 text-white';

if($awaitingText){

    switch($awaitingText){

        case 'project_head':
            $awaitingColor = 'bg-blue-500 text-white';
            break;

        case 'treasurer':
            $awaitingColor = 'bg-purple-500 text-white';
            break;

        case 'president':
            $awaitingColor = 'bg-indigo-500 text-white';
            break;

        case 'moderator':
            $awaitingColor = 'bg-orange-500 text-white';
            break;

        case 'sacdev_admin':
            $awaitingColor = 'bg-emerald-600 text-white';
            break;

    }

}

@endphp



<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

<div class="flex items-start justify-between gap-4">

{{-- LEFT SIDE --}}
<div>

<div class="text-base font-semibold text-slate-900">
{{ $formType->name }}
</div>


{{-- STATUS --}}
<div class="mt-2 flex items-center gap-2 text-sm text-slate-700">

<span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">
<span class="h-2.5 w-2.5 rounded-full {{ $statusColor }}"></span>
</span>

<span>
{{ $statusText }}
</span>

</div>



{{-- AWAITING APPROVAL --}}
@if($document && $document->status === 'submitted' && $awaitingText)

<div class="mt-2">

<span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold {{ $awaitingColor }}">

Awaiting {{ ucfirst(str_replace('_',' ',$awaitingText)) }}

</span>

</div>

@endif



{{-- APPROVED DATE --}}
@if($document && $document->status === 'approved_by_sacdev')

<div class="mt-2 text-xs text-emerald-600 font-medium">
Approved {{ $document->updated_at->format('M d, Y') }}
</div>

@endif

</div>



{{-- RIGHT SIDE ACTION --}}
<div>

@if($isProjectHead)

{{-- CREATE --}}
@if(!$document && $routeName && $allowed)

<a href="{{ route($routeName,$project) }}"
class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
Create
</a>


{{-- REQUIREMENT NOT MET --}}
@elseif(!$document && !$allowed)

<span class="text-xs text-slate-400">
Requirement not met
</span>


{{-- CONTINUE --}}
@elseif($document && $document->status === 'draft' && $routeName)

<a href="{{ route($routeName,$project) }}"
class="inline-flex items-center rounded-lg bg-amber-500 px-3 py-2 text-sm font-semibold text-white hover:bg-amber-600">
Continue
</a>


{{-- VIEW --}}
@elseif($document && $routeName)

<a href="{{ route($routeName,$project) }}"
class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
View
</a>

@endif

@else

{{-- NON PROJECT HEAD --}}
@if($document && $routeName)

<a href="{{ route($routeName,$project) }}"
class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
View
</a>

@else

<span class="text-xs text-slate-400">
Waiting for Project Head
</span>

@endif

@endif

</div>

</div>

</div>