<div x-show="projModalOpen" x-cloak
     x-transition.opacity
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="closeProject()">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-slate-900/50" @click="closeProject()"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-5xl rounded-2xl bg-white shadow-xl border border-slate-200">

        {{-- HEADER --}}
        <div class="flex items-start justify-between px-6 py-5 border-b border-slate-200">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">
                    Project Details
                </h3>
                <p class="text-sm text-slate-500 mt-1">
                    Review full project information (read-only)
                </p>
            </div>

            <button type="button"
                    class="rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50"
                    @click="closeProject()">
                Close
            </button>
        </div>

        {{-- CONTENT --}}
        <div class="px-6 py-5 space-y-6" x-show="activeProject">


        <div class="space-y-5">

            {{-- PRIMARY: TITLE --}}
            <div>
                <div class="text-xs text-slate-500">Project Title</div>

                <h2 class="mt-1 text-xl font-semibold text-slate-900 leading-snug"
                    x-text="activeProject.title || '—'"></h2>

                {{-- CATEGORY (secondary, subtle) --}}
                <div class="mt-2 text-sm text-slate-500">
                    <span class="text-slate-400">Category:</span>
                    <span class="font-medium text-slate-700"
                        x-text="niceCategory(activeProject.category)"></span>
                </div>
            </div>


            {{-- SECONDARY INFO --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs text-slate-500">Target Date</div>
                    <div class="mt-1 text-sm font-semibold text-slate-900"
                        x-text="activeProject.target_date || '—'"></div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs text-slate-500">Budget</div>
                    <div class="mt-1 text-sm font-semibold text-slate-900">
                        ₱<span x-text="formatMoney(activeProject.budget)"></span>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs text-slate-500">Implementing Body</div>
                    <div class="mt-1 text-sm text-slate-800"
                        x-text="activeProject.implementing_body || '—'"></div>
                </div>

            </div>

        </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- LIST COMPONENT --}}
                <template x-for="section in [
                    { title: 'Objectives', data: activeProject.objectives },
                    { title: 'Beneficiaries', data: activeProject.beneficiaries },
                    { title: 'Deliverables', data: activeProject.deliverables },
                    { title: 'Partners / Stakeholders', data: activeProject.partners }
                ]" :key="section.title">

                    <div class="rounded-xl border border-slate-200 p-5">

                        {{-- TITLE --}}
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-slate-800"
                                x-text="section.title"></h4>

                            <span class="text-xs text-slate-500"
                                  x-text="countFilled(section.data) + ' item(s)'"></span>
                        </div>

                        {{-- LIST --}}
                        <ul class="space-y-2 text-sm text-slate-800 list-disc pl-5">

                            <template x-for="(item, i) in (section.data ?? [])" :key="i">
                                <li x-show="item && item.trim() !== ''"
                                    x-text="item"
                                    class="leading-relaxed"></li>
                            </template>

                            {{-- EMPTY --}}
                            <li x-show="countFilled(section.data ?? []) === 0"
                                class="text-slate-400 list-none">
                                No entries provided
                            </li>

                        </ul>

                    </div>

                </template>

            </div>

        </div>

        {{-- FOOTER --}}
        <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between">

            <p class="text-xs text-slate-500">
                Press <span class="font-medium">Esc</span> to close
            </p>

            <button type="button"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800"
                    @click="closeProject()">
                Done
            </button>

        </div>

    </div>
</div>