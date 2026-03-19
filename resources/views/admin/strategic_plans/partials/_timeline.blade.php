{{-- TIMELINE --}}
<div>

    <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">
        Timeline
    </h2>

    <div class="relative">

        <div class="absolute left-2 top-0 bottom-0 w-px bg-slate-300"></div>

        <div class="space-y-5">

            {{-- STEP --}}
            <div class="relative pl-7">
                <div class="absolute left-0 top-1 w-4 h-4 rounded-full border flex items-center justify-center
                    {{ $submission->submitted_to_moderator_at ? 'bg-emerald-500 border-emerald-500' : 'bg-white border-slate-300' }}">
                </div>

                <div class="text-xs font-medium text-slate-800">
                    Submitted
                </div>

                <div class="text-[11px] text-slate-500">
                    {{ optional($submission->submitted_to_moderator_at)->format('M d, Y h:i A') ?? 'Pending' }}
                </div>
            </div>

            {{-- STEP --}}
            <div class="relative pl-7">
                <div class="absolute left-0 top-1 w-4 h-4 rounded-full border flex items-center justify-center
                    {{ $submission->forwarded_to_sacdev_at ? 'bg-blue-500 border-blue-500' : 'bg-white border-slate-300' }}">
                </div>

                <div class="text-xs font-medium text-slate-800">
                    Forwarded
                </div>

                <div class="text-[11px] text-slate-500">
                    {{ optional($submission->forwarded_to_sacdev_at)->format('M d, Y h:i A') ?? 'Waiting' }}
                </div>
            </div>

            {{-- STEP --}}
            <div class="relative pl-7">
                <div class="absolute left-0 top-1 w-4 h-4 rounded-full border flex items-center justify-center
                    {{ $submission->approved_at ? 'bg-emerald-600 border-emerald-600' : 'bg-white border-slate-300' }}">
                </div>

                <div class="text-xs font-medium text-slate-800">
                    Approved
                </div>

                <div class="text-[11px] text-slate-500">
                    {{ optional($submission->approved_at)->format('M d, Y h:i A') ?? 'Pending' }}
                </div>
            </div>

        </div>

    </div>

</div>