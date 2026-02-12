<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white shadow-sm rounded-2xl border border-slate-200 p-5">
        <p class="text-sm text-slate-600">Total Overall</p>
        <p class="text-xl font-semibold text-slate-900 mt-1">
            {{ number_format((float)$submission->total_overall, 2) }}
        </p>
    </div>

    <div class="bg-white shadow-sm rounded-2xl border border-slate-200 p-5">
        <p class="text-sm text-slate-600">Submitted to Moderator</p>
        <p class="text-sm font-semibold text-slate-900 mt-1">
            {{ optional($submission->submitted_to_moderator_at)->format('M d, Y h:i A') ?? '—' }}
        </p>
    </div>

    <div class="bg-white shadow-sm rounded-2xl border border-slate-200 p-5">
        <p class="text-sm text-slate-600">Last Moderator Review</p>
        <p class="text-sm font-semibold text-slate-900 mt-1">
            {{ optional($submission->moderator_reviewed_at)->format('M d, Y h:i A') ?? '—' }}
        </p>
    </div>
</div>
