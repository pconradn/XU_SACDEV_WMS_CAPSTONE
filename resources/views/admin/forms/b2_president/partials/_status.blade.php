@php
    $status = $registration->status ?? null;

    $config = match($status) {

        'draft' => [
            'color' => 'slate',
            'title' => 'Draft',
            'message' => 'This registration is still in draft and has not been submitted to SACDEV.',
        ],

        'submitted',
        'submitted_to_sacdev' => [
            'color' => 'amber',
            'title' => 'Pending SACDEV Review',
            'message' => 'This registration has been submitted and is waiting for SACDEV review and action.',
        ],

        'submitted_to_moderator' => [
            'color' => 'blue',
            'title' => 'Pending Moderator Review',
            'message' => 'This registration is currently being reviewed by the moderator.',
        ],

        'returned',
        'returned_by_moderator' => [
            'color' => 'rose',
            'title' => 'Returned for Revision',
            'message' => 'This registration was returned. Please review remarks and required corrections.',
        ],

        'approved',
        'approved_by_sacdev' => [
            'color' => 'emerald',
            'title' => 'Approved',
            'message' => 'This registration has been reviewed and approved by SACDEV.',
        ],

        'forwarded_to_sacdev' => [
            'color' => 'blue',
            'title' => 'Forwarded to SACDEV',
            'message' => 'This registration has been forwarded and is awaiting SACDEV action.',
        ],

        default => [
            'color' => 'slate',
            'title' => 'Not Submitted',
            'message' => 'This registration has not yet been submitted.',
        ]
    };

    $color = $config['color'];

    $styles = [
        'slate' => 'border-slate-200 bg-slate-50 text-slate-800',
        'amber' => 'border-amber-200 bg-amber-50 text-amber-900',
        'rose' => 'border-rose-200 bg-rose-50 text-rose-900',
        'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-900',
        'blue' => 'border-blue-200 bg-blue-50 text-blue-900',
    ];

    $dotStyles = [
        'slate' => 'bg-slate-400',
        'amber' => 'bg-amber-500',
        'rose' => 'bg-rose-500',
        'emerald' => 'bg-emerald-500',
        'blue' => 'bg-blue-500',
    ];
@endphp


<div class="mb-5 rounded-xl border px-5 py-4 {{ $styles[$color] }}">

    <div class="flex items-start gap-3">

        <span class="mt-1 h-2.5 w-2.5 rounded-full {{ $dotStyles[$color] }}"></span>

        <div>

            <div class="font-semibold">
                {{ $config['title'] }}
            </div>

            <div class="text-sm mt-1">
                {{ $config['message'] }}
            </div>


            {{-- SACDEV remarks --}}
            @if(!empty($registration->sacdev_remarks))

                <div class="mt-3 rounded-lg border border-current/20 bg-white/60 px-4 py-3">

                    <div class="text-xs font-semibold uppercase tracking-wide opacity-70">
                        SACDEV Remarks
                    </div>

                    <div class="mt-1 text-sm">
                        {{ $registration->sacdev_remarks }}
                    </div>

                </div>

            @endif


            {{-- Moderator remarks --}}
            @if(!empty($registration->moderator_remarks))

                <div class="mt-3 rounded-lg border border-current/20 bg-white/60 px-4 py-3">

                    <div class="text-xs font-semibold uppercase tracking-wide opacity-70">
                        Moderator Remarks
                    </div>

                    <div class="mt-1 text-sm">
                        {{ $registration->moderator_remarks }}
                    </div>

                </div>

            @endif

        </div>

    </div>

</div>