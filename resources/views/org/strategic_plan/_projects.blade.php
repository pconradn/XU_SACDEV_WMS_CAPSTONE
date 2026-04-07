<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
        <h2 class="text-sm font-semibold text-slate-900">
            Strategic Plan Projects
        </h2>
        <p class="text-xs text-slate-500 mt-1">
            Organize projects per category. Add details including objectives, beneficiaries, and budget.
        </p>
    </div>

    <div class="px-6 py-6 space-y-8">

        <template x-for="cat in categories" :key="cat.key">

            {{-- CATEGORY CARD --}}
            <section class="rounded-xl border border-slate-200 bg-white shadow-sm">

                {{-- CATEGORY HEADER --}}
                <div class="flex items-center justify-between px-4 py-3 border-b bg-slate-50">
                    <h3 class="text-sm font-semibold text-slate-800" x-text="cat.label"></h3>

                    <button type="button"
                        class="inline-flex items-center gap-1 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800"
                        @click="openProjectCreate(cat.key)">
                        + Add Project
                    </button>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">

                        <thead class="bg-white border-b">
                            <tr class="text-xs uppercase tracking-wide text-slate-500">
                                <th class="px-4 py-2 text-left w-40">Target Date</th>
                                <th class="px-4 py-2 text-left">Project</th>
                                <th class="px-4 py-2 text-center w-48">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            <template x-for="(p, i) in projectsByCategory(cat.key)" :key="p._idx ?? i">

                                <tr class="hover:bg-slate-50 transition">

                                    {{-- DATE --}}
                                    <td class="px-4 py-3 text-slate-700">
                                        <span x-text="p.target_date || '—'"></span>
                                    </td>

                                    {{-- PROJECT --}}
                                    <td class="px-4 py-3">

                                        <div class="flex flex-col">

                                            <span class="font-semibold text-slate-900"
                                                  x-text="p.title || 'Untitled project'">
                                            </span>

                                            <div class="mt-1 text-xs text-slate-500" x-show="p.budget !== ''">
                                                ₱<span x-text="formatMoney(p.budget)"></span>
                                            </div>

                                        </div>

                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">

                                            <button type="button"
                                                class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100"
                                                @click="openProjectEdit(p._idx)">
                                                Details
                                            </button>

                                            <button type="button"
                                                class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100"
                                                @click="removeProject(p._idx)">
                                                Remove
                                            </button>

                                        </div>

                                        {{-- HIDDEN INPUTS (UNCHANGED) --}}
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

                            {{-- EMPTY STATE --}}
                            <tr x-show="projectsByCategory(cat.key).length === 0">
                                <td colspan="3" class="px-4 py-8 text-center">

                                    <div class="text-xs text-slate-400">
                                        No projects added yet
                                    </div>

                                    <button type="button"
                                        class="mt-2 text-xs font-semibold text-blue-600 hover:underline"
                                        @click="openProjectCreate(cat.key)">
                                        Add your first project
                                    </button>

                                </td>
                            </tr>

                        </tbody>

                    </table>
                </div>

                {{-- SUBTOTAL --}}
                <div class="px-4 py-3 border-t bg-slate-50 text-right text-xs text-slate-600">
                    Subtotal:
                    <span class="font-semibold text-slate-900"
                          x-text="formatMoney(categoryTotal(cat.key))">
                    </span>
                </div>

            </section>

        </template>

        {{-- OVERALL TOTAL --}}
        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-100 px-5 py-3">
            <span class="text-sm font-medium text-slate-700">
                Overall Total Budget
            </span>
            <span class="text-sm font-semibold text-slate-900"
                  x-text="formatMoney(overallTotal())">
            </span>
        </div>

    </div>
</div>