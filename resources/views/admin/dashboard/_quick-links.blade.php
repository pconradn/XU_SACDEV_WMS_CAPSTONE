<div class="hidden">
    bg-blue-50 text-blue-700 border-blue-200
    bg-emerald-50 text-emerald-700 border-emerald-200
    bg-blue-100 text-blue-700
    bg-emerald-100 text-emerald-700
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">

    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-slate-900">
            Quick Access
        </h3>

        <span class="text-xs text-slate-400">
            Shortcuts
        </span>
    </div>

    <div class="flex flex-col gap-3">

        <a href="{{ route('admin.rereg.index') }}"
           class="group flex items-center justify-between px-4 py-3 rounded-xl border border-blue-200 bg-blue-50 hover:bg-blue-100 transition-all">

            <div>
                <div class="text-sm font-semibold text-blue-900">
                    Re-Registration Hub
                </div>
                <div class="text-xs text-blue-700">
                    Review org submissions
                </div>
            </div>

            <span class="text-[10px] px-2 py-1 rounded-full bg-white text-blue-700 border border-blue-200 font-semibold group-hover:bg-blue-100">
                Open
            </span>

        </a>

        <a href="{{ route('admin.orgs_by_sy.index') }}"
           class="group flex items-center justify-between px-4 py-3 rounded-xl border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 transition-all">

            <div>
                <div class="text-sm font-semibold text-emerald-900">
                    Organizations by SY
                </div>
                <div class="text-xs text-emerald-700">
                    Manage activations
                </div>
            </div>

            <span class="text-[10px] px-2 py-1 rounded-full bg-white text-emerald-700 border border-emerald-200 font-semibold group-hover:bg-emerald-100">
                Open
            </span>

        </a>

    </div>

</div>