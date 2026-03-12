        {{-- TIMELINE / META --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h2 class="text-base font-semibold text-slate-900">Submission Timeline</h2>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="rounded-lg border border-slate-200 p-4">
                    <div class="text-slate-600">Submitted to Moderator</div>
                    <div class="font-semibold text-slate-900 mt-1">
                        {{ optional($submission->submitted_to_moderator_at)->format('M d, Y h:i A') ?? '—' }}
                    </div>
                </div>
                <div class="rounded-lg border border-slate-200 p-4">
                    <div class="text-slate-600">Forwarded to SACDEV</div>
                    <div class="font-semibold text-slate-900 mt-1">
                        {{ optional($submission->forwarded_to_sacdev_at)->format('M d, Y h:i A') ?? '—' }}
                    </div>
                </div>
                <div class="rounded-lg border border-slate-200 p-4">
                    <div class="text-slate-600">Approved At</div>
                    <div class="font-semibold text-slate-900 mt-1">
                        {{ optional($submission->approved_at)->format('M d, Y h:i A') ?? '—' }}
                    </div>
                </div>
            </div>

            <div class="mt-4 text-sm text-slate-600 space-y-1">
            <div>
                Submitted By:
                <span class="font-semibold text-slate-900">
                    {{ $submission->submittedBy?->name ?? '—' }}
                </span>
            </div>

            <div>
                Moderator (Reviewed By):
                <span class="font-semibold text-slate-900">
                    {{ $submission->moderatorReviewedBy?->name ?? '—' }}
                </span>
            </div>
            </div>
        </div>