@php

$status = $b2->status ?? null;

/*
|--------------------------------------------------------------------------
| Status Circle Color
|--------------------------------------------------------------------------
*/

$circle =
    !$status ? 'bg-slate-400' :
    ($status === 'approved_by_sacdev' ? 'bg-emerald-500' :
    (str_contains($status,'returned') ? 'bg-rose-500' :
    ($status === 'draft' ? 'bg-slate-400' :
    'bg-amber-500')));


/*
|--------------------------------------------------------------------------
| Status Label
|--------------------------------------------------------------------------
*/

$statusText = $status
    ? ucwords(str_replace('_',' ',$status))
    : 'No submission';

@endphp



<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">

        <div>

            <div class="font-semibold text-slate-900">
                B2 — President Registration
            </div>


            {{-- Status --}}
            <div class="mt-1 flex items-center gap-2 text-sm text-slate-700">

                <span class="h-2.5 w-2.5 rounded-full {{ $circle }}"></span>

                <span>
                    {{ $statusText }}
                </span>

            </div>



            {{-- Submission Timeline --}}
            @if($b2?->submitted_at)
                <div class="text-xs text-slate-500 mt-1">
                    Submitted:
                    {{ $b2->submitted_at->format('M d, Y — h:i A') }}
                </div>
            @endif


            @if($b2?->moderator_reviewed_at)
                <div class="text-xs text-slate-500">
                    Reviewed:
                    {{ $b2->moderator_reviewed_at->format('M d, Y — h:i A') }}
                </div>
            @endif


            @if($b2?->approved_at)
                <div class="text-xs text-slate-500">
                    Approved:
                    {{ $b2->approved_at->format('M d, Y — h:i A') }}
                </div>
            @endif


            @if($b2?->returned_at)
                <div class="text-xs text-slate-500">
                    Returned:
                    {{ $b2->returned_at->format('M d, Y — h:i A') }}
                </div>
            @endif


        </div>


        {{-- View button (optional, add if you create moderator view page) --}}
        {{-- 
        @if($b2)
            <a href="{{ route('org.moderator.rereg.b2.show', $b2) }}"
               class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                Open
            </a>
        @endif
        --}}


    </div>



    {{-- Current President Account --}}
    @if($presidentMembership?->user)

        <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-4">

            <div class="text-sm font-semibold text-slate-900">
                Current President Account
            </div>

            <div class="mt-2 space-y-1">

                <div class="text-sm text-slate-700">
                    {{ $presidentMembership->user->name }}
                </div>

                <div class="text-xs text-slate-500">
                    {{ $presidentMembership->user->email }}
                </div>

            </div>

        </div>

    @else

        <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-4">

            <div class="text-sm text-slate-600">
                No president account provisioned yet.
            </div>

        </div>

    @endif



</div>