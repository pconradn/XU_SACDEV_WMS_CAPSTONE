@php
    $status = $registration->status;

    $badge = match ($status) {
        'draft' => 'bg-slate-100 text-slate-800 ring-slate-200',
        'submitted_to_sacdev' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'returned_by_sacdev' => 'bg-amber-50 text-amber-800 ring-amber-200',
        'approved_by_sacdev' => 'bg-emerald-50 text-emerald-800 ring-emerald-200',
        default => 'bg-slate-100 text-slate-800 ring-slate-200',
    };

    $label = match ($status) {
        'draft' => 'Draft',
        'submitted_to_sacdev' => 'Submitted to SACDEV',
        'returned_by_sacdev' => 'Returned by SACDEV',
        'approved_by_sacdev' => 'Approved by SACDEV',
        default => $status,
    };
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm mb-4">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $badge }}">
                {{ $label }}
            </span>

            <div class="text-sm text-slate-600">
                <span class="font-medium text-slate-700">Target SY:</span>
                {{ $registration->target_school_year_id ?? '' }}
            </div>
        </div>

        <div class="text-xs text-slate-500 space-x-3">
            @if($registration->submitted_at)
                <span>Submitted: {{ $registration->submitted_at->format('M d, Y h:i A') }}</span>
            @endif
            @if($registration->approved_at)
                <span>Approved: {{ $registration->approved_at->format('M d, Y h:i A') }}</span>
            @endif
        </div>
    </div>

    @if($status === 'returned_by_sacdev' && $registration->sacdev_remarks)
        <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 p-3">
            <div class="text-sm font-semibold text-amber-900">SACDEV Remarks</div>
            <div class="mt-1 text-sm text-amber-900 whitespace-pre-line">{{ $registration->sacdev_remarks }}</div>

            @if($registration->sacdev_reviewed_at)
                <div class="mt-2 text-xs text-amber-800">
                    Reviewed: {{ $registration->sacdev_reviewed_at->format('M d, Y h:i A') }}
                </div>
            @endif
        </div>
    @endif
</div>
