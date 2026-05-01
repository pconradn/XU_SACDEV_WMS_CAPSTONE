<div class="space-y-2">

    <a href="{{ route('admin.rereg.index') }}"
       class="group flex items-center justify-between gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 transition hover:bg-blue-100">

        <div class="flex items-start gap-3 min-w-0">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-blue-600 border border-blue-200">
                <i data-lucide="clipboard-list" class="w-4 h-4"></i>
            </div>

            <div class="min-w-0">
                <div class="text-xs font-semibold text-slate-900">
                    Re-Registration Submissions
                </div>

                <div class="mt-0.5 text-[11px] leading-4 text-slate-600">
                    Review submitted organization registration requirements.
                </div>
            </div>
        </div>

        <div class="shrink-0 text-blue-700">
            <i data-lucide="arrow-right" class="w-4 h-4 transition group-hover:translate-x-0.5"></i>
        </div>
    </a>

    <a href="{{ route('admin.orgs_by_sy.index') }}"
       class="group flex items-center justify-between gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 transition hover:bg-emerald-100">

        <div class="flex items-start gap-3 min-w-0">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-emerald-600 border border-emerald-200">
                <i data-lucide="building-2" class="w-4 h-4"></i>
            </div>

            <div class="min-w-0">
                <div class="text-xs font-semibold text-slate-900">
                    Manage Organizations
                </div>

                <div class="mt-0.5 text-[11px] leading-4 text-slate-600">
                    View active school year records, organization profiles, officers, members, and projects.
                </div>
            </div>
        </div>

        <div class="shrink-0 text-emerald-700">
            <i data-lucide="arrow-right" class="w-4 h-4 transition group-hover:translate-x-0.5"></i>
        </div>
    </a>

</div>