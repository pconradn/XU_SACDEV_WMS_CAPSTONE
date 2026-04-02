<x-app-layout>

    @php
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

        <div x-show="openApprove" x-cloak
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center p-4">

            {{-- BACKDROP --}}
            <div class="absolute inset-0 bg-slate-900/50" @click="openApprove=false"></div>

            {{-- MODAL --}}
            <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl">

                {{-- HEADER --}}
                <div class="px-5 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Approve Strategic Plan
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        This action will finalize the strategic plan and generate project records.
                    </p>
                </div>

                {{-- BODY --}}
                <div class="p-5 space-y-5">

                    {{-- ICON + MESSAGE --}}
                    <div class="flex items-start gap-3">

                        <div class="mt-1 text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm text-slate-700">
                                Are you sure you want to approve this Strategic Plan?
                            </p>

                            <p class="text-sm text-slate-500 mt-2">
                                This will create official project records and enable workflow tracking in the system.
                            </p>
                        </div>

                    </div>


                    {{-- EFFECTS --}}
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">

                        <div class="font-semibold text-slate-800 mb-2">
                            What happens after approval
                        </div>

                        <ul class="list-disc pl-5 space-y-1">
                            <li>Projects will be created for this organization and school year</li>
                            <li>The President can assign Project Heads immediately</li>
                            <li>Project workflows will be enabled</li>
                            <li>The Strategic Plan becomes a locked reference document</li>
                        </ul>

                    </div>


                    {{-- IMPORTANT --}}
                    <div class="rounded-lg border border-amber-200 bg-amber-50/70 p-4 text-sm text-amber-900">

                        <div class="font-semibold mb-1">
                            Important
                        </div>

                        <ul class="list-disc pl-5 space-y-1">
                            <li>Project submissions remain restricted until organization registration is completed</li>
                            <li>This action cannot be undone</li>
                        </ul>

                    </div>


                    {{-- ACTIONS --}}
                    <div class="flex justify-end gap-2 pt-2">

                        <button type="button"
                                @click="openApprove=false"
                                class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                            Cancel
                        </button>

                        <form method="POST"
                            action="{{ route('admin.strategic_plans.approve', $submission) }}">
                            @csrf

                            <button type="submit"
                                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                Approve Strategic Plan
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        </div>

        @include('admin.strategic_plans.partials._return_modal', ['submission' => $submission])
        @include('admin.strategic_plans.partials._revert_modal', ['submission' => $submission])

    </div>
</x-app-layout>