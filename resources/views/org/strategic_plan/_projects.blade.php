{{-- Projects --}}
<div class="bg-white shadow-sm rounded-xl border border-slate-200 p-5">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Strategic Plan Projects</h2>
            <p class="text-sm text-slate-500 mt-1">
                Add projects under each category. Click “Details” to add objectives, beneficiaries, deliverables, and partners.
            </p>
        </div>
    </div>

    <div class="mt-5 space-y-8">
        <template x-for="cat in categories" :key="cat.key">
            <section class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-800" x-text="cat.label"></h3>
                    <button type="button"
                            class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                            @click="addProject(cat.key)">
                        + Add Project
                    </button>
                </div>

                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="min-w-[1200px] w-full text-sm">
                        <thead class="bg-slate-50">
                        <tr class="text-left text-slate-600">
                            <th class="px-3 py-2 w-40">Target Date</th>
                            <th class="px-3 py-2">Project / Initiative</th>
                            <th class="px-3 py-2 w-56">Implementing Body</th>
                            <th class="px-3 py-2 w-40">Budget</th>
                            <th class="px-3 py-2 w-44">Actions</th>
                        </tr>
                        </thead>

                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(p, i) in projectsByCategory(cat.key)" :key="p._idx ?? i">
                            <tr class="align-top">
                                <td class="px-3 py-2">
                                    <input type="date"
                                        class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                                        x-model="p.target_date"
                                        :name="'projects['+p._idx+'][target_date]'">
                                </td>

                                <td class="px-3 py-2">
                                    <input type="text"
                                        class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="e.g., Leadership Training Seminar"
                                        x-model="p.title"
                                        :name="'projects['+p._idx+'][title]'">

                                    <input type="hidden"
                                        :name="'projects['+p._idx+'][category]'"
                                        :value="p.category">

                                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 border"
                                            :class="projectStatus(p).objectivesOk ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700'">
                                            Objectives: <span x-text="projectStatus(p).objectivesOk ? '✔' : '✖'"></span>
                                        </span>

                                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 border"
                                            :class="projectStatus(p).beneficiariesOk ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700'">
                                            Beneficiaries: <span x-text="projectStatus(p).beneficiariesOk ? '✔' : '✖'"></span>
                                        </span>

                                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 border"
                                            :class="projectStatus(p).deliverablesOk ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-emerald-700'">
                                            Deliverables: <span x-text="projectStatus(p).deliverablesOk ? '✔' : '✖'"></span>
                                        </span>
                                    </div>


                                    <!-- ALWAYS submit details arrays (mirror hidden inputs) -->
                                    <template x-for="(t, j) in (p.objectives ?? [])" :key="'obj-'+p._idx+'-'+j">
                                        <input type="hidden"
                                            :name="'projects['+p._idx+'][objectives]['+j+']'"
                                            :value="t">
                                    </template>

                                    <template x-for="(t, j) in (p.beneficiaries ?? [])" :key="'ben-'+p._idx+'-'+j">
                                        <input type="hidden"
                                            :name="'projects['+p._idx+'][beneficiaries]['+j+']'"
                                            :value="t">
                                    </template>

                                    <template x-for="(t, j) in (p.deliverables ?? [])" :key="'del-'+p._idx+'-'+j">
                                        <input type="hidden"
                                            :name="'projects['+p._idx+'][deliverables]['+j+']'"
                                            :value="t">
                                    </template>

                                    <template x-for="(t, j) in (p.partners ?? [])" :key="'par-'+p._idx+'-'+j">
                                        <input type="hidden"
                                            :name="'projects['+p._idx+'][partners]['+j+']'"
                                            :value="t">
                                    </template>
                                    
                                </td>

                                <td class="px-3 py-2">
                                    <input type="text"
                                        class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="(optional)"
                                        x-model="p.implementing_body"
                                        :name="'projects['+p._idx+'][implementing_body]'">
                                </td>

                                <td class="px-3 py-2">
                                    <input type="number" step="0.01" min="0"
                                        class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                                        x-model="p.budget"
                                        :name="'projects['+p._idx+'][budget]'">
                                </td>

                                <td class="px-3 py-2">
                                    <div class="flex gap-2">
                                        <button type="button"
                                                class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50"
                                                @click="openDetails(p._idx)">
                                            Details
                                        </button>
                                        <button type="button"
                                                class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100"
                                                @click="removeProject(p._idx)">
                                            Remove
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="projectsByCategory(cat.key).length === 0">
                            <td colspan="5" class="px-3 py-4 text-slate-500">No projects added yet.</td>
                        </tr>
                    </tbody>



                    </table>
                </div>

                <div class="text-right text-sm text-slate-600">
                    Subtotal: <span class="font-semibold text-slate-900" x-text="formatMoney(categoryTotal(cat.key))"></span>
                </div>
            </section>
        </template>

        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
            <span class="text-sm text-slate-600">Overall Total Budget</span>
            <span class="text-sm font-semibold text-slate-900" x-text="formatMoney(overallTotal())"></span>
        </div>
    </div>
</div>
