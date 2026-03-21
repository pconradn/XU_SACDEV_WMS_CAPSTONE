
    <div class="flex justify-end">
        <button
            type="button"
            @click="openTimeline = true"
            class="inline-flex items-center gap-2 rounded-lg 
                border border-slate-300 bg-white 
                px-3.5 py-1.5 text-xs font-medium text-slate-700
                hover:bg-slate-50 hover:border-slate-400
                transition focus:outline-none focus:ring-2 focus:ring-slate-200">

            {{-- ICON --}}
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 7V3m8 4V3m-9 8h10m-11 8h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>

            <span>View Timeline</span>
        </button>
    </div>




