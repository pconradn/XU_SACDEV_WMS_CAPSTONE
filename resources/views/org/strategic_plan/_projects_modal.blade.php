{{-- Project Modal (Create + Edit) --}}
<div x-show="detailsOpen" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="closeDetails()">

    <div class="absolute inset-0 bg-slate-900/50" @click="closeDetails()"></div>

    <div class="relative w-full max-w-4xl rounded-2xl bg-white shadow-xl border border-slate-200">
        <div class="flex items-start justify-between p-5 border-b border-slate-200">
            <div>
                <h3 class="text-lg font-semibold text-slate-900"
                    x-text="draftMode === 'edit' ? 'Edit Project' : 'Add Project'"></h3>
                <p class="text-sm text-slate-500 mt-1">
                    Fill in the project info, then add objectives, beneficiaries, deliverables, and partners.
                </p>
            </div>
            <button type="button"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    @click="closeDetails()">
                Close
            </button>
        </div>

        <div class="p-5 space-y-5" x-show="draftProject">
            {{-- Core info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Target Date</label>
                    <input type="date"
                           class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                           x-model="draftProject.target_date">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Budget (₱)</label>
                    <input type="number" step="0.01" min="0"
                           class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                           x-model="draftProject.budget"
                           placeholder="0.00">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Project / Initiative Title</label>
                    <input type="text"
                           class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                           x-model="draftProject.title"
                           placeholder="e.g., Leadership Training Seminar">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Implementing Body (optional)</label>
                    <input type="text"
                           class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                           x-model="draftProject.implementing_body"
                           placeholder="(optional)">
                </div>
            </div>



            {{-- inline validation hint --}}
            <div x-show="draftProject && !isDraftComplete()"
                 class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                Complete Target Date, Title, Budget, and at least one Objective, Beneficiary, and Deliverable before saving.
            </div>

            {{-- Details lists --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Objectives --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800">Objectives (at least 1)</p>
                        <button type="button" class="text-xs font-medium text-blue-700 hover:underline"
                                @click="addTextItem(draftProject,'objectives')">+ Add</button>
                    </div>

                    <template x-for="(t, j) in draftProject.objectives" :key="j">
                        <div class="mt-2 flex gap-2">
                            <input type="text"
                                   class="flex-1 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2"
                                   x-model="draftProject.objectives[j]">
                            <button type="button"
                                    class="rounded-lg border border-rose-200 bg-rose-50 px-2 text-rose-700 hover:bg-rose-100"
                                    @click="removeTextItem(draftProject,'objectives',j)">✕</button>
                        </div>
                    </template>
                </div>

                {{-- Beneficiaries --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800">Beneficiaries (at least 1)</p>
                        <button type="button" class="text-xs font-medium text-blue-700 hover:underline"
                                @click="addTextItem(draftProject,'beneficiaries')">+ Add</button>
                    </div>

                    <template x-for="(t, j) in draftProject.beneficiaries" :key="j">
                        <div class="mt-2 flex gap-2">
                            <input type="text"
                                   class="flex-1 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2"
                                   x-model="draftProject.beneficiaries[j]">
                            <button type="button"
                                    class="rounded-lg border border-rose-200 bg-rose-50 px-2 text-rose-700 hover:bg-rose-100"
                                    @click="removeTextItem(draftProject,'beneficiaries',j)">✕</button>
                        </div>
                    </template>
                </div>

                {{-- Deliverables --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800">Deliverables (at least 1)</p>
                        <button type="button" class="text-xs font-medium text-blue-700 hover:underline"
                                @click="addTextItem(draftProject,'deliverables')">+ Add</button>
                    </div>

                    <template x-for="(t, j) in draftProject.deliverables" :key="j">
                        <div class="mt-2 flex gap-2">
                            <input type="text"
                                   class="flex-1 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2"
                                   x-model="draftProject.deliverables[j]">
                            <button type="button"
                                    class="rounded-lg border border-rose-200 bg-rose-50 px-2 text-rose-700 hover:bg-rose-100"
                                    @click="removeTextItem(draftProject,'deliverables',j)">✕</button>
                        </div>
                    </template>
                </div>

                {{-- Partners --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800">Partners / Stakeholders (optional)</p>
                        <button type="button" class="text-xs font-medium text-blue-700 hover:underline"
                                @click="addTextItem(draftProject,'partners')">+ Add</button>
                    </div>

                    <template x-for="(t, j) in draftProject.partners" :key="j">
                        <div class="mt-2 flex gap-2">
                            <input type="text"
                                   class="flex-1 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2"
                                   x-model="draftProject.partners[j]">
                            <button type="button"
                                    class="rounded-lg border border-rose-200 bg-rose-50 px-2 text-rose-700 hover:bg-rose-100"
                                    @click="removeTextItem(draftProject,'partners',j)">✕</button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="p-5 border-t border-slate-200 flex items-center justify-between">
            <p class="text-sm text-slate-500">Tip: press <span class="font-medium">Esc</span> to close</p>

            <button type="button"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="!isDraftComplete()"
                    @click="saveDraftProject()">
                Save Project
            </button>
        </div>
    </div>
</div>
