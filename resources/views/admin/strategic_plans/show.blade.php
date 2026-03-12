<x-app-layout>

    @php
        // Safe JSON for modal viewing (same UX as moderator)
        $projectsJson = $submission->projects->map(function ($p) {
            return [
                'id' => $p->id,
                'category' => $p->category,
                'target_date' => optional($p->target_date)->format('Y-m-d'),
                'title' => $p->title,
                'implementing_body' => $p->implementing_body,
                'budget' => (float) $p->budget,

                'objectives' => $p->objectives->pluck('text')->values()->all(),
                'beneficiaries' => $p->beneficiaries->pluck('text')->values()->all(),
                'deliverables' => $p->deliverables->pluck('text')->values()->all(),
                'partners' => $p->partners->pluck('text')->values()->all(),
            ];
        })->values()->all();
    @endphp

    {{-- Alpine helpers (define BEFORE x-data) --}}
    <script>
        window.spAdminReview = function (projects) {
            return {
                // existing modals
                openReturn: false,
                openRevert: false,
                openApprove: false,

                // project modal
                projects: Array.isArray(projects) ? projects : [],
                projModalOpen: false,
                activeProjectId: null,

                openProject(id) {
                    this.activeProjectId = id;
                    this.projModalOpen = true;
                },
                closeProject() {
                    this.projModalOpen = false;
                    this.activeProjectId = null;
                },
                get activeProject() {
                    return this.projects.find(p => String(p.id) === String(this.activeProjectId)) || null;
                },

                niceCategory(cat) {
                    const map = {
                        org_dev: 'Organizational Development',
                        student_services: 'Student Services',
                        community_involvement: 'Community Involvement',
                    };
                    return map[cat] ?? (cat ?? '—');
                },
                formatMoney(n) {
                    const x = parseFloat(n);
                    const v = isNaN(x) ? 0 : x;
                    return v.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },
                countFilled(arr) {
                    if (!Array.isArray(arr)) return 0;
                    return arr.filter(x => String(x ?? '').trim().length > 0).length;
                },
            };
        }
    </script>

    <div class="mx-auto max-w-6xl px-4 py-6 space-y-6"
         x-data='window.spAdminReview(@json($projectsJson))'>

        {{-- Header --}}
        @include('admin.strategic_plans._header', ['submission' => $submission])

        {{-- Alerts (optional, if you have it) --}}
        @includeWhen(View::exists('admin.strategic_plans._alerts'), 'admin.strategic_plans._alerts')

        {{-- Remarks --}}
        @include('admin.strategic_plans._remarks', ['submission' => $submission])



        {{-- Timeline --}}
        @include('admin.strategic_plans._timeline', ['submission' => $submission])

        {{-- Identity (mission/vision/logo + org name/acronym) --}}
        @include('admin.strategic_plans._identity', ['submission' => $submission])

        {{-- Projects table (make sure buttons call @click="openProject(p.id)" inside this partial) --}}
        @include('admin.strategic_plans._projects', ['submission' => $submission])
        
        {{-- Funds --}}
        @include('admin.strategic_plans._funds', ['submission' => $submission])

        {{-- Actions --}}
        @include('admin.strategic_plans._actions', ['submission' => $submission])

        {{-- PROJECT MODAL (ADMIN) --}}
        <div x-show="projModalOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             @keydown.escape.window="closeProject()">

            <div class="absolute inset-0 bg-slate-900/50" @click="closeProject()"></div>

            <div class="relative w-full max-w-4xl rounded-2xl bg-white shadow-xl border border-slate-200">
                <div class="flex items-start justify-between p-5 border-b border-slate-200">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Project Details</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            Full project information (read-only).
                        </p>
                    </div>
                    <button type="button"
                            class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                            @click="closeProject()">
                        Close
                    </button>
                </div>

                <div class="p-5 space-y-5" x-show="activeProject">
                    {{-- Core info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-semibold text-slate-500">Category</div>
                            <div class="mt-1 text-sm font-semibold text-slate-900" x-text="niceCategory(activeProject.category)"></div>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-semibold text-slate-500">Target Date</div>
                            <div class="mt-1 text-sm font-semibold text-slate-900" x-text="activeProject.target_date || '—'"></div>
                        </div>

                        <div class="md:col-span-2 rounded-xl border border-slate-200 p-4">
                            <div class="text-xs font-semibold text-slate-500">Project / Initiative Title</div>
                            <div class="mt-1 text-sm font-semibold text-slate-900" x-text="activeProject.title || '—'"></div>
                        </div>

                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="text-xs font-semibold text-slate-500">Implementing Body</div>
                            <div class="mt-1 text-sm text-slate-800" x-text="activeProject.implementing_body || '—'"></div>
                        </div>

                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="text-xs font-semibold text-slate-500">Budget</div>
                            <div class="mt-1 text-sm font-semibold text-slate-900">
                                ₱<span x-text="formatMoney(activeProject.budget)"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Lists --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-800">Objectives</p>
                                <span class="text-xs text-slate-500" x-text="countFilled(activeProject.objectives) + ' item(s)'"></span>
                            </div>
                            <ul class="mt-3 space-y-2 text-sm text-slate-800">
                                <template x-for="(t, i) in (activeProject.objectives ?? [])" :key="'obj-'+i">
                                    <li class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2" x-text="t"></li>
                                </template>
                                <li x-show="countFilled(activeProject.objectives ?? []) === 0" class="text-slate-500">—</li>
                            </ul>
                        </div>

                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-800">Beneficiaries</p>
                                <span class="text-xs text-slate-500" x-text="countFilled(activeProject.beneficiaries) + ' item(s)'"></span>
                            </div>
                            <ul class="mt-3 space-y-2 text-sm text-slate-800">
                                <template x-for="(t, i) in (activeProject.beneficiaries ?? [])" :key="'ben-'+i">
                                    <li class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2" x-text="t"></li>
                                </template>
                                <li x-show="countFilled(activeProject.beneficiaries ?? []) === 0" class="text-slate-500">—</li>
                            </ul>
                        </div>

                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-800">Deliverables</p>
                                <span class="text-xs text-slate-500" x-text="countFilled(activeProject.deliverables) + ' item(s)'"></span>
                            </div>
                            <ul class="mt-3 space-y-2 text-sm text-slate-800">
                                <template x-for="(t, i) in (activeProject.deliverables ?? [])" :key="'del-'+i">
                                    <li class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2" x-text="t"></li>
                                </template>
                                <li x-show="countFilled(activeProject.deliverables ?? []) === 0" class="text-slate-500">—</li>
                            </ul>
                        </div>

                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-800">Partners / Stakeholders</p>
                                <span class="text-xs text-slate-500" x-text="countFilled(activeProject.partners) + ' item(s)'"></span>
                            </div>
                            <ul class="mt-3 space-y-2 text-sm text-slate-800">
                                <template x-for="(t, i) in (activeProject.partners ?? [])" :key="'par-'+i">
                                    <li class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2" x-text="t"></li>
                                </template>
                                <li x-show="countFilled(activeProject.partners ?? []) === 0" class="text-slate-500">—</li>
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

        
        {{-- APPROVE MODAL --}}
        <div x-show="openApprove" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <div class="absolute inset-0 bg-slate-900/50" @click="openApprove=false"></div>

            <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl">

                {{-- Header --}}
                <div class="px-5 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Approve Strategic Plan
                    </h3>

                    <p class="text-sm text-slate-600 mt-1">
                        Please review the effects of approval below.
                    </p>
                </div>


                {{-- Effects --}}
                <div class="p-5 space-y-4">

                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">

                        <div class="font-semibold mb-2">
                            This approval will automatically:
                        </div>

                        <ul class="list-disc pl-5 space-y-1">

                            <li>
                                Create official projects for this organization and school year
                            </li>

                            <li>
                                Allow the president to assign project heads immediately
                            </li>

                            <li>
                                Enable project tracking and workflow in the system
                            </li>

                            <li>
                                Lock the strategic plan as an approved institutional reference
                            </li>

                        </ul>

                    </div>


                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">

                        <div class="font-semibold mb-1">
                            Important:
                        </div>

                        <ul class="list-disc pl-5 space-y-1">

                            <li>
                                Project heads can be assigned after approval
                            </li>

                            <li>
                                Project submissions remain restricted until organization registration is completed
                            </li>

                            <li>
                                This action cannot be undone
                            </li>

                        </ul>

                    </div>


                    <form method="POST"
                        action="{{ route('admin.strategic_plans.approve', $submission) }}"
                        class="space-y-3">

                        @csrf

                        <textarea name="remarks"
                                rows="3"
                                class="w-full rounded-lg border-slate-300 text-sm"
                                placeholder="Optional approval note..."></textarea>


                        <div class="flex justify-end gap-2">

                            <button type="button"
                                    @click="openApprove=false"
                                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700">
                                Cancel
                            </button>


                            <button type="submit"
                                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                Confirm Approval
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        {{-- RETURN MODAL --}}
        <div x-show="openReturn" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/50" @click="openReturn=false"></div>
            <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl p-5">
                <h3 class="text-lg font-semibold text-slate-900">Return to Organization</h3>
                <p class="text-sm text-slate-600 mt-1">
                    This will allow the president to edit again. Remarks are required.
                </p>

                <form class="mt-4 space-y-3"
                      method="POST"
                      action="{{ route('admin.strategic_plans.return', $submission) }}">
                    @csrf

                    <textarea name="remarks" rows="4"
                              class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Enter required changes..." required></textarea>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                                class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700"
                                @click="openReturn=false">
                            Cancel
                        </button>
                        <button type="submit"
                                class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                            Return
                        </button>
                    </div>
                </form>
            </div>
        </div>



    </div>
</x-app-layout>
