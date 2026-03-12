@php
    $status = (string) ($submission->status ?? '—');

    $statusClass = 'bg-slate-50 border-slate-200 text-slate-700';
    if ($status === 'approved_by_sacdev') $statusClass = 'bg-emerald-50 border-emerald-200 text-emerald-700';
    elseif (str_contains($status, 'returned')) $statusClass = 'bg-rose-50 border-rose-200 text-rose-700';
    elseif (in_array($status, ['submitted_to_moderator','forwarded_to_sacdev','submitted_to_sacdev'], true)) $statusClass = 'bg-amber-50 border-amber-200 text-amber-800';
@endphp

<div class="bg-white shadow-sm rounded-2xl border border-slate-200 p-5">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Strategic Plan Review</h1>

            <div class="mt-2 text-sm text-slate-700">
                Target SY:
                <span class="font-semibold">{{ $submission->targetSchoolYear?->name ?? '—' }}</span>
            </div>

            <div class="text-sm text-slate-700">
                Organization:
                <span class="font-semibold">{{ $submission->org_name ?? '—' }}</span>
                @if(!empty($submission->org_acronym))
                    <span class="text-slate-500">({{ $submission->org_acronym }})</span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs px-2.5 py-1 rounded-full border {{ $statusClass }}">
                Status: {{ $status }}
            </span>
        </div>
    </div>
</div>
