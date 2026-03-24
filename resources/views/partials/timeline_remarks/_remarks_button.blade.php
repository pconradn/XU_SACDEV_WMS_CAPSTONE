{{-- ========================= --}}
{{-- REMARKS BUTTON --}}
{{-- ========================= --}}
@if($submission->moderator_remarks || $submission->sacdev_remarks)

<div class="flex justify-end">

    <button type="button"
            @click="openRemarks = true"
            class="inline-flex items-center gap-2 text-xs px-3 py-1.5 rounded-lg 
                   border border-slate-300 bg-white text-slate-700 
                   hover:bg-slate-50 hover:border-slate-400 transition">

        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8 10h8M8 14h5M21 15a4 4 0 01-4 4H7l-4 4V5a4 4 0 014-4h10a4 4 0 014 4z"/>
        </svg>

        View Remarks
    </button>

</div>

@endif


