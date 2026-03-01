@php

$formType = $form->formType;
$document = $form->document;
$required = $form->required;

$statusText = 'Not created';
$statusColor = 'bg-slate-400';

if ($document) {

    if ($document->status === 'draft') {
        $statusText = 'Draft';
        $statusColor = 'bg-amber-400';

    } elseif ($document->status === 'submitted') {
        $statusText = 'Submitted';
        $statusColor = 'bg-blue-400';

    } elseif ($document->status === 'approved_by_sacdev') {
        $statusText = 'Approved';
        $statusColor = 'bg-emerald-500';

    } elseif ($document->status === 'returned_by_sacdev') {
        $statusText = 'Returned';
        $statusColor = 'bg-rose-500';
    }

}

@endphp


<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

    <div class="flex items-start justify-between gap-4">


        {{-- Label --}}
        <div>

            <div class="text-base font-semibold text-slate-900">
                {{ $formType->name }}
            </div>


            {{-- Status --}}
            <div class="mt-2 flex items-center gap-2 text-sm text-slate-700">

                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">
                    <span class="h-2.5 w-2.5 rounded-full {{ $statusColor }}"></span>
                </span>

                <span>
                    {{ $statusText }}
                </span>

            </div>


            {{-- Requirement --}}
            <div class="mt-1 text-xs">

                @if($required)

                    <span class="text-rose-600 font-medium">
                        Required
                    </span>

                @else

                    <span class="text-slate-500">
                        Optional
                    </span>

                @endif

            </div>

        </div>


        
        <div>

            @if($isProjectHead)

                {{-- CREATE --}}
                @if(!$document)

                    <a href="{{ route('org.projects.project-proposal.create', $project) }}"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Create
                    </a>

                {{-- CONTINUE EDITING --}}
                @elseif($document->status === 'draft')

                    <a href="{{ route('org.projects.project-proposal.create', [$project, $document]) }}"
                    class="inline-flex items-center rounded-lg bg-amber-500 px-3 py-2 text-sm font-semibold text-white hover:bg-amber-600">
                        Continue
                    </a>

                {{-- VIEW ONLY --}}
                @else

                    <a href="{{ route('org.projects.project-proposal.create', [$project, $document]) }}"
                    class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                        View
                    </a>

                @endif

            @else

                {{-- NON PROJECT HEAD USERS --}}

                @if($document)

                    <a href="{{ route('org.projects.project-proposal.create', [$project, $document]) }}"
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