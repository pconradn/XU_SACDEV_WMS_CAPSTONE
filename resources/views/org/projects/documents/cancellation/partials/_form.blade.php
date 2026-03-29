<div>

    {{-- SECTION HEADER --}}
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900 tracking-wide">
            Cancellation Details
        </h3>
        <p class="text-xs text-slate-500 mt-1">
            Provide a clear explanation of why the activity is being cancelled. This will be reviewed by SACDEV for documentation and approval.
        </p>
    </div>

    @php
        $reason = old('reason', $data->reason ?? '');
    @endphp

    <div class="grid grid-cols-1 gap-5">

        {{-- REASON --}}
        <div>
            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                Reason for Cancellation
            </label>

            <textarea
                name="reason"
                rows="5"
                placeholder="Explain the reason for cancelling the activity (e.g., low participation, weather conditions, conflicts, etc.)"
                class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                @if($isReadOnly) disabled @endif
            >{{ $reason }}</textarea>

            <p class="text-[11px] text-slate-400 mt-1">
                Be specific and factual. This helps SACDEV evaluate the validity of the cancellation request.
            </p>
        </div>

    </div>

</div>