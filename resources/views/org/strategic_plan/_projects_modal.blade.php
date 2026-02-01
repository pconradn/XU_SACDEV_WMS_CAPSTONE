{{-- Details Modal (keep OUTSIDE the table markup) --}}
<div x-show="detailsOpen" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="closeDetails()">

    {{-- overlay --}}
    <div class="absolute inset-0 bg-slate-900/50" @click="closeDetails()"></div>

    {{-- modal panel --}}
    <div class="relative w-full max-w-4xl rounded-2xl bg-white shadow-xl border border-slate-200">
        <div class="flex items-start justify-between p-5 border-b border-slate-200">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Project Details</h3>
                <p class="text-sm text-slate-500 mt-1">Add objectives, beneficiaries, deliverables, and partners.</p>
            </div>
            <button type="button"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    @click="closeDetails()">
                Close
            </button>
        </div>

        <div class="p-5 space-y-5" x-show="detailsProject">
            {{-- debug: confirms modal is inside the draft form --}}
            <input type="hidden" name="debug_details_idx" :value="detailsProject?._idx ?? ''">

            {{-- quick status --}}
            <div class="flex flex-wrap gap-2 text-xs">
                <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 border"
                      :class="projectStatus(detailsProject).objectivesOk ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700'">
                    Objectives: <span x-text="projectStatus(detailsProject).objectivesOk ? '✔' : '✖'"></span>
                </span>
                <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 border"
                      :class="projectStatus(detailsProject).beneficiariesOk ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700'">
                    Beneficiaries: <span x-text="projectStatus(detailsProject).beneficiariesOk ? '✔' : '✖'"></span>
                </span>
                <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 border"
                      :class="projectStatus(detailsProject).deliverablesOk ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700'">
                    Deliverables: <span x-text="projectStatus(detailsProject).deliverablesOk ? '✔' : '✖'"></span>
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Objectives --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800">Objectives (at least 1)</p>
                        <button type="button" class="text-xs font-medium text-blue-700 hover:underline"
                                @click="addTextItem(detailsProject,'objectives')">+ Add</button>
                    </div>

                    <template x-for="(t, j) in detailsProject.objectives" :key="j">
                        <div class="mt-2 flex gap-2">
                            <input type="text"
                                   class="flex-1 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2"
                                   x-model="detailsProject.objectives[j]"
                                   :name="'projects['+detailsProject._idx+'][objectives]['+j+']'">
                            <button type="button"
                                    class="rounded-lg border border-rose-200 bg-rose-50 px-2 text-rose-700 hover:bg-rose-100"
                                    @click="removeTextItem(detailsProject,'objectives',j)">✕</button>
                        </div>
                    </template>
                </div>

                {{-- Beneficiaries --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800">Beneficiaries (at least 1)</p>
                        <button type="button" class="text-xs font-medium text-blue-700 hover:underline"
                                @click="addTextItem(detailsProject,'beneficiaries')">+ Add</button>
                    </div>

                    <template x-for="(t, j) in detailsProject.beneficiaries" :key="j">
                        <div class="mt-2 flex gap-2">
                            <input type="text"
                                   class="flex-1 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2"
                                   x-model="detailsProject.beneficiaries[j]"
                                   :name="'projects['+detailsProject._idx+'][beneficiaries]['+j+']'">
                            <button type="button"
                                    class="rounded-lg border border-rose-200 bg-rose-50 px-2 text-rose-700 hover:bg-rose-100"
                                    @click="removeTextItem(detailsProject,'beneficiaries',j)">✕</button>
                        </div>
                    </template>
                </div>

                {{-- Deliverables --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800">Deliverables (at least 1)</p>
                        <button type="button" class="text-xs font-medium text-blue-700 hover:underline"
                                @click="addTextItem(detailsProject,'deliverables')">+ Add</button>
                    </div>

                    <template x-for="(t, j) in detailsProject.deliverables" :key="j">
                        <div class="mt-2 flex gap-2">
                            <input type="text"
                                   class="flex-1 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2"
                                   x-model="detailsProject.deliverables[j]"
                                   :name="'projects['+detailsProject._idx+'][deliverables]['+j+']'">
                            <button type="button"
                                    class="rounded-lg border border-rose-200 bg-rose-50 px-2 text-rose-700 hover:bg-rose-100"
                                    @click="removeTextItem(detailsProject,'deliverables',j)">✕</button>
                        </div>
                    </template>
                </div>

                {{-- Partners --}}
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800">Partners / Stakeholders (optional)</p>
                        <button type="button" class="text-xs font-medium text-blue-700 hover:underline"
                                @click="addTextItem(detailsProject,'partners')">+ Add</button>
                    </div>

                    <template x-for="(t, j) in detailsProject.partners" :key="j">
                        <div class="mt-2 flex gap-2">
                            <input type="text"
                                   class="flex-1 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2"
                                   x-model="detailsProject.partners[j]"
                                   :name="'projects['+detailsProject._idx+'][partners]['+j+']'">
                            <button type="button"
                                    class="rounded-lg border border-rose-200 bg-rose-50 px-2 text-rose-700 hover:bg-rose-100"
                                    @click="removeTextItem(detailsProject,'partners',j)">✕</button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="p-5 border-t border-slate-200 flex items-center justify-between">
            <p class="text-sm text-slate-500">
                Tip: You can press <span class="font-medium">Esc</span> to close.
            </p>

            <button type="button"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                    @click="closeDetails()">
                Done
            </button>
        </div>
    </div>
</div>
