<div class="hidden">
    bg-blue-50 text-blue-700 border-blue-200
    bg-emerald-50 text-emerald-700 border-emerald-200
</div>

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-2">

            {{-- Lucide: layout-dashboard --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="4" rx="1.5"/>
                <rect x="14" y="10" width="7" height="11" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
            </svg>

            <div>
                <div class="text-xs font-semibold text-slate-900">
                    Quick Access
                </div>
                <div class="text-[10px] text-slate-500">
                    Common admin actions
                </div>
            </div>
        </div>

        <span class="text-[10px] text-slate-400">
            Shortcuts
        </span>
    </div>

    {{-- LINKS --}}
    <div class="flex flex-col divide-y divide-slate-100">

        {{-- REREG --}}
        <a href="{{ route('admin.rereg.index') }}"
           class="group flex items-center justify-between px-4 py-3 hover:bg-slate-50 transition">

            <div class="flex items-start gap-3">

                {{-- Lucide: clipboard-list --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="9" y="2" width="6" height="4" rx="1"/>
                    <path d="M9 4H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-2"/>
                    <path d="M9 12h6M9 16h6M9 8h6"/>
                </svg>

                <div>
                    <div class="text-xs font-semibold text-slate-900">
                        Re-Registration Hub
                    </div>
                    <div class="text-[10px] text-slate-500">
                        Review organization submissions
                    </div>
                </div>

            </div>

            <span class="text-[9px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 font-semibold group-hover:bg-blue-100">
                Open
            </span>

        </a>

        {{-- ORGS BY SY --}}
        <a href="{{ route('admin.orgs_by_sy.index') }}"
           class="group flex items-center justify-between px-4 py-3 hover:bg-slate-50 transition">

            <div class="flex items-start gap-3">

                {{-- Lucide: building-2 --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M3 21h18"/>
                    <path d="M5 21V7l7-4 7 4v14"/>
                    <path d="M9 9h.01M15 9h.01M9 13h.01M15 13h.01M9 17h.01M15 17h.01"/>
                </svg>

                <div>
                    <div class="text-xs font-semibold text-slate-900">
                        Organizations by SY
                    </div>
                    <div class="text-[10px] text-slate-500">
                        Manage activations & organization data
                    </div>
                </div>

            </div>

            <span class="text-[9px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold group-hover:bg-emerald-100">
                Open
            </span>

        </a>

    </div>

</div>