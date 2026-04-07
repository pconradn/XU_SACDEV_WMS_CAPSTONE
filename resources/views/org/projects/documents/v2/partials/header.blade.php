<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm px-5 py-4">

    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

        {{-- LEFT --}}
        <div class="min-w-0">

            {{-- TITLE --}}
            <div class="flex items-center gap-2">
                <i data-lucide="folder" class="w-4 h-4 text-slate-500"></i>

                <h1 class="text-sm font-semibold text-slate-900 leading-tight truncate">
                    {{ $header['title'] }}
                </h1>
            </div>

            {{-- META --}}
            <div class="mt-1 text-[11px] text-slate-500">
                {{ $header['org'] ?? '—' }}
                <span class="mx-1">•</span>
                {{ $header['school_year'] ?? '' }}
            </div>

            {{-- PROJECT HEAD --}}
            <div class="mt-2 flex items-center gap-1.5 text-[11px] text-slate-600">
                <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400"></i>

                <span>Project Head:</span>

                <span class="font-medium text-slate-800">
                    {{ $header['project_head'] ?? '—' }}
                </span>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="flex flex-col items-start lg:items-end gap-2">

            {{-- STATUS --}}
            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-semibold rounded-md border shadow-sm {{ $header['status_class'] }}">
                <i data-lucide="activity" class="w-3 h-3"></i>
                {{ $header['status_label'] }}
            </span>

            {{-- HELP BUTTON --}}
            <button 
                @click="helpOpen = true"
                class="flex items-center gap-1 px-2 py-1 text-[10px] rounded-md bg-purple-100 text-purple-700 hover:bg-purple-200 transition"
            >
                <i data-lucide="help-circle" class="w-3 h-3"></i>
                Guide
            </button>

        </div>

    </div>

</div>