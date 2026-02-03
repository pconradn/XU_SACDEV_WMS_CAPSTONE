@php
    $status = $submission->status ?? 'draft';

    $map = [
        'draft' => ['Draft', 'bg-slate-100 text-slate-800 border-slate-200'],
        'submitted_to_sacdev' => ['Submitted to SACDEV', 'bg-amber-100 text-amber-900 border-amber-200'],
        'returned_by_sacdev' => ['Returned by SACDEV', 'bg-red-100 text-red-900 border-red-200'],
        'approved_by_sacdev' => ['Approved by SACDEV', 'bg-emerald-100 text-emerald-900 border-emerald-200'],
    ];

    [$label, $cls] = $map[$status] ?? [$status, 'bg-slate-100 text-slate-800 border-slate-200'];
@endphp

<div class="mb-4 rounded-xl border p-4 {{ $cls }}">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="text-xs uppercase tracking-wide opacity-80">Status</div>
            <div class="text-sm font-semibold">{{ $label }}</div>
        </div>

        <div class="text-xs opacity-80">
            @if($submission->submitted_at)
                Submitted: {{ $submission->submitted_at->format('M d, Y h:i A') }}
            @endif
            @if($submission->approved_at)
                <span class="ml-2">Approved: {{ $submission->approved_at->format('M d, Y h:i A') }}</span>
            @endif
        </div>
    </div>

    @if($status === 'returned_by_sacdev' && $submission->sacdev_remarks)
        <div class="mt-3 rounded-lg border border-red-200 bg-white/60 p-3 text-sm whitespace-pre-line">
            <div class="font-semibold mb-1">SACDEV Remarks</div>
            {{ $submission->sacdev_remarks }}
        </div>
    @endif
</div>
