@if(!empty($submission->sacdev_remarks))
    <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-5">
        <div class="flex items-start gap-3">
            <div class="mt-0.5 text-indigo-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5a8.5 8.5 0 100-17 8.5 8.5 0 000 17z"/>
                </svg>
            </div>

            <div class="flex-1">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                    <div class="text-sm font-semibold text-indigo-900">SACDEV Remarks</div>

                    @if($submission->sacdev_reviewed_at)
                        <div class="text-xs text-indigo-800">
                            Reviewed on {{ $submission->sacdev_reviewed_at->format('M d, Y h:i A') }}
                        </div>
                    @endif
                </div>

                <div class="mt-2 text-sm text-indigo-950 whitespace-pre-line">
                    {{ $submission->sacdev_remarks }}
                </div>
            </div>
        </div>
    </div>
@endif
