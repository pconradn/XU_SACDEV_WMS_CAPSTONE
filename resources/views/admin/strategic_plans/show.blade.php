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

   
    <script>
        window.spAdminReview = function (projects) {
            return {
                // existing modals
                openRemarks: false,
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


        @include('admin.strategic_plans.partials._header_block', ['submission' => $submission])

        @include('admin.strategic_plans.partials._identity', ['submission' => $submission])
        
        @include('admin.strategic_plans.partials._projects', ['submission' => $submission])

        @include('admin.strategic_plans.partials._funds', ['submission' => $submission])

        @include('admin.strategic_plans.partials._actions', ['submission' => $submission])

        @include('admin.strategic_plans.partials._project_modal')

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

        @include('admin.strategic_plans.partials._return_modal', ['submission' => $submission])
        @include('admin.strategic_plans.partials._revert_modal', ['submission' => $submission])

    </div>
</x-app-layout>