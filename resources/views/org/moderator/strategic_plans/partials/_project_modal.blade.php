<div x-show="modalOpen" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="closeProject()">

    <div class="absolute inset-0 bg-slate-900/50" @click="closeProject()"></div>

    <div class="relative w-full max-w-4xl rounded-2xl bg-white shadow-xl border border-slate-200">
        <div class="flex items-start justify-between p-5 border-b border-slate-200">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Project Details</h3>
                <p class="text-sm text-slate-500 mt-1">
                    Full read-only details for moderator review.
                </p>
            </div>
            <button type="button"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    @click="closeProject()">
                Close
            </button>
        </div>

        <div class="p-5 space-y-5" x-show="activeProject">
            {{-- top info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-xs font-semibold text-slate-500">Category</div>
                    <div class="mt-1 font-semibold text-slate-900" x-text="niceCategory(activeProject.category)"></div>
                </div>

                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-xs font-semibold text-slate-500">Target Date</div>
                    <div class="mt-1 font-semibold text-slate-900" x-text="activeProject.target_date || '—'"></div>
                </div>

                <div class="md:col-span-2 rounded-xl border border-slate-200 p-4">
                    <div class="text-xs font-semibold text-slate-500">Title</div>
                    <div class="mt-1 font-semibold text-slate-900" x-text="activeProject.title || 'Untitled project'"></div>
                </div>

                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-xs font-semibold text-slate-500">Budget</div>
                    <div class="mt-1 font-semibold text-slate-900">
                        ₱<span x-text="formatMoney(activeProject.budget)"></span>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-xs font-semibold text-slate-500">Implementing Body</div>
                    <div class="mt-1 font-semibold text-slate-900" x-text="activeProject.implementing_body || '—'"></div>
                </div>
            </div>

            {{-- lists --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-semibold text-slate-900">
                            Objectives
                            <span class="text-xs text-slate-500" x-text="'(' + countFilled(activeProject.objectives) + ')'"></span>
                        </div>
                    </div>
                    <ul class="mt-2 space-y-2 text-sm text-slate-700">
                        <template x-for="(t, i) in (activeProject.objectives || [])" :key="'obj'+i">
                            <li class="rounded-lg bg-slate-50 px-3 py-2 border border-slate-200" x-text="t"></li>
                        </template>
                        <template x-if="countFilled(activeProject.objectives) === 0">
                            <li class="text-slate-500">—</li>
                        </template>
                    </ul>
                </div>

                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-sm font-semibold text-slate-900">
                        Beneficiaries
                        <span class="text-xs text-slate-500" x-text="'(' + countFilled(activeProject.beneficiaries) + ')'"></span>
                    </div>
                    <ul class="mt-2 space-y-2 text-sm text-slate-700">
                        <template x-for="(t, i) in (activeProject.beneficiaries || [])" :key="'ben'+i">
                            <li class="rounded-lg bg-slate-50 px-3 py-2 border border-slate-200" x-text="t"></li>
                        </template>
                        <template x-if="countFilled(activeProject.beneficiaries) === 0">
                            <li class="text-slate-500">—</li>
                        </template>
                    </ul>
                </div>

                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-sm font-semibold text-slate-900">
                        Deliverables
                        <span class="text-xs text-slate-500" x-text="'(' + countFilled(activeProject.deliverables) + ')'"></span>
                    </div>
                    <ul class="mt-2 space-y-2 text-sm text-slate-700">
                        <template x-for="(t, i) in (activeProject.deliverables || [])" :key="'del'+i">
                            <li class="rounded-lg bg-slate-50 px-3 py-2 border border-slate-200" x-text="t"></li>
                        </template>
                        <template x-if="countFilled(activeProject.deliverables) === 0">
                            <li class="text-slate-500">—</li>
                        </template>
                    </ul>
                </div>

                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="text-sm font-semibold text-slate-900">
                        Partners / Stakeholders
                        <span class="text-xs text-slate-500" x-text="'(' + countFilled(activeProject.partners) + ')'"></span>
                    </div>
                    <ul class="mt-2 space-y-2 text-sm text-slate-700">
                        <template x-for="(t, i) in (activeProject.partners || [])" :key="'par'+i">
                            <li class="rounded-lg bg-slate-50 px-3 py-2 border border-slate-200" x-text="t"></li>
                        </template>
                        <template x-if="countFilled(activeProject.partners) === 0">
                            <li class="text-slate-500">—</li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>

        <div class="p-5 border-t border-slate-200 flex items-center justify-between">
            <p class="text-sm text-slate-500">Tip: press <span class="font-medium">Esc</span> to close</p>
            <button type="button"
                    class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800"
                    @click="closeProject()">
                Done
            </button>
        </div>
    </div>
</div>
