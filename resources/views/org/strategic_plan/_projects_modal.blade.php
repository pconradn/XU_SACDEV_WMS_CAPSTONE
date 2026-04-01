<div x-show="detailsOpen" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="closeDetails()">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeDetails()"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-4xl rounded-2xl bg-white shadow-xl border border-slate-200 max-h-[90vh] flex flex-col overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-5 border-b bg-slate-50 flex items-start justify-between">

            <div>
                <h3 class="text-base font-semibold text-slate-900"
                    x-text="draftMode === 'edit' ? 'Edit Project' : 'Add Project'">
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    Define the project details and supporting information.
                </p>
            </div>

            <button type="button"
                    class="text-xs font-medium text-slate-500 hover:text-slate-700"
                    @click="closeDetails()">
                ✕
            </button>

        </div>

        {{-- BODY --}}
        <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6" x-show="draftProject">

            {{-- CORE INFO --}}
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">

                <div class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-3">
                    Core Information
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="text-xs font-semibold text-slate-600">Target Date</label>
                        <input type="date"
                               class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                               x-model="draftProject.target_date">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600">Budget (₱)</label>

                        <div class="relative mt-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm">₱</span>

                            <input type="text"
                                inputmode="decimal"
                                class="w-full pl-7 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm text-right"
                                :value="formatMoney(draftProject.budget)"
                                @input="
                                    let raw = $event.target.value.replace(/,/g,'');
                                    draftProject.budget = raw === '' ? '' : parseFloat(raw);
                                "
                                placeholder="0.00">
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-slate-600">Project Title</label>
                        <input type="text"
                               class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                               x-model="draftProject.title">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-slate-600">Implementing Body (optional)</label>
                        <input type="text"
                               class="mt-1 w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                               x-model="draftProject.implementing_body">
                    </div>

                </div>

            </div>

            {{-- VALIDATION --}}
            <div x-show="draftProject && !isDraftComplete()"
                 class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-xs text-rose-700">
                Complete all required fields (date, title, budget, objectives, beneficiaries, deliverables).
            </div>

            {{-- LISTS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- FUNCTIONAL BLOCK --}}
                <template x-for="section in [
                    { key: 'objectives', label: 'Objectives', required: true },
                    { key: 'beneficiaries', label: 'Beneficiaries', required: true },
                    { key: 'deliverables', label: 'Deliverables', required: true },
                    { key: 'partners', label: 'Partners / Stakeholders', required: false }
                ]" :key="section.key">

                    <div class="rounded-xl border border-slate-200 p-4 bg-white">

                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-slate-700"
                               x-text="section.label + (section.required ? ' (required)' : '')"></p>

                            <button type="button"
                                class="text-xs font-semibold text-blue-600 hover:underline"
                                @click="addTextItem(draftProject, section.key)">
                                + Add
                            </button>
                        </div>

                        <template x-for="(t, j) in draftProject[section.key]" :key="j">
                            <div class="mt-2 flex gap-2">

                                <input type="text"
                                    class="flex-1 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2"
                                    x-model="draftProject[section.key][j]">

                                <button type="button"
                                    class="rounded-lg border border-rose-200 bg-rose-50 px-2 text-rose-700 hover:bg-rose-100"
                                    @click="removeTextItem(draftProject, section.key, j)">
                                    ✕
                                </button>

                            </div>
                        </template>

                    </div>

                </template>

            </div>

        </div>

        {{-- FOOTER --}}
        <div class="px-6 py-4 border-t bg-white flex items-center justify-between">

            <span class="text-xs text-slate-400">
                Press Esc to close
            </span>

            <div class="flex items-center gap-2">

                <button type="button"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                        @click="closeDetails()">
                    Cancel
                </button>

                <button type="button"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                        :disabled="!isDraftComplete()"
                        @click="saveDraftProject()">
                    Save Project
                </button>

            </div>

        </div>

    </div>
</div>