        {{-- REMARKS BLOCKS --}}
        @if(!empty($submission->moderator_remarks))
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                <div class="text-sm font-semibold text-amber-800">Moderator Remarks</div>
                <div class="mt-1 text-sm text-amber-900 whitespace-pre-line">{{ $submission->moderator_remarks }}</div>

                <div class="mt-2 text-xs text-amber-700">
                    @if($submission->moderator_reviewed_at)
                        Reviewed on {{ $submission->moderator_reviewed_at->format('F j, Y g:i A') }}
                    @else
                        Reviewed on —
                    @endif

                    @if($submission->moderator_reviewed_by)
                        • Reviewed by ID: {{ $submission->moderator_reviewed_by }}
                    @endif
                </div>
            </div>
        @endif

        @if(!empty($submission->sacdev_remarks))
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
                <div class="text-sm font-semibold text-blue-800">SACDEV Remarks</div>
                <div class="mt-1 text-sm text-blue-900 whitespace-pre-line">{{ $submission->sacdev_remarks }}</div>

                <div class="mt-2 text-xs text-blue-700">
                    @if($submission->sacdev_reviewed_at)
                        Reviewed on {{ $submission->sacdev_reviewed_at->format('F j, Y g:i A') }}
                    @else
                        Reviewed on —
                    @endif

                    @if($submission->sacdev_reviewed_by)
                        • Reviewed by ID: {{ $submission->sacdev_reviewed_by }}
                    @endif
                </div>
            </div>
        @endif