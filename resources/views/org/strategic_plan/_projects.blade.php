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
                        @click="openProjectCreate(cat.key)">
                    + Add Project
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-200">
                <table class="min-w-full w-full text-sm">
                    <thead class="bg-slate-50">
                    <tr class="text-left text-slate-600">
                        <th class="px-3 py-2 w-44">Target Date</th>
                        <th class="px-3 py-2">Project Title</th>
                        <th class="px-3 py-2 w-52">Actions</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                    <template x-for="(p, i) in projectsByCategory(cat.key)" :key="p._idx ?? i">
                        <tr class="align-top">
                            <td class="px-3 py-2">
                                <span class="text-slate-800" x-text="p.target_date || '—'"></span>
                            </td>

                            <td class="px-3 py-2">
                                <div class="font-medium text-slate-900" x-text="p.title || 'Untitled project'"></div>

                                {{-- optional: show budget quick --}}
                                <div class="mt-0.5 text-xs text-slate-500" x-show="p.budget !== ''">
                                    Budget: ₱<span x-text="formatMoney(p.budget)"></span>
                                </div>

                            </td>

                            <td class="px-3 py-2">
                                <div class="flex gap-2">
                                    <button type="button"
                                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50"
                                            @click="openProjectEdit(p._idx)">
                                        More Details
                                    </button>

                                    <button type="button"
                                            class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 hover:bg-rose-100"
                                            @click="removeProject(p._idx)">
                                        Remove
                                    </button>
                                </div>

                                {{-- Hidden inputs so everything still submits --}}
                                <template x-if="p">
                                    <div class="hidden">
                                        <input type="hidden" :name="'projects['+p._idx+'][category]'" :value="p.category">

                                        <input type="hidden" :name="'projects['+p._idx+'][target_date]'" :value="p.target_date">
                                        <input type="hidden" :name="'projects['+p._idx+'][title]'" :value="p.title">
                                        <input type="hidden" :name="'projects['+p._idx+'][implementing_body]'" :value="p.implementing_body">
                                        <input type="hidden" :name="'projects['+p._idx+'][budget]'" :value="p.budget">

                                        <template x-for="(t, j) in (p.objectives ?? [])" :key="'obj-'+p._idx+'-'+j">
                                            <input type="hidden" :name="'projects['+p._idx+'][objectives]['+j+']'" :value="t">
                                        </template>
                                        <template x-for="(t, j) in (p.beneficiaries ?? [])" :key="'ben-'+p._idx+'-'+j">
                                            <input type="hidden" :name="'projects['+p._idx+'][beneficiaries]['+j+']'" :value="t">
                                        </template>
                                        <template x-for="(t, j) in (p.deliverables ?? [])" :key="'del-'+p._idx+'-'+j">
                                            <input type="hidden" :name="'projects['+p._idx+'][deliverables]['+j+']'" :value="t">
                                        </template>
                                        <template x-for="(t, j) in (p.partners ?? [])" :key="'par-'+p._idx+'-'+j">
                                            <input type="hidden" :name="'projects['+p._idx+'][partners]['+j+']'" :value="t">
                                        </template>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="projectsByCategory(cat.key).length === 0">
                        <td colspan="3" class="px-3 py-4 text-slate-500">No projects added yet.</td>
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
