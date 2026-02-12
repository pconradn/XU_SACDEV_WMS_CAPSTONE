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
        window.spReview = function (projects) {
            return {
                projects: Array.isArray(projects) ? projects : [],
                modalOpen: false,
                activeProjectId: null,

                openProject(id) {
                    this.activeProjectId = id;
                    this.modalOpen = true;
                },
                closeProject() {
                    this.modalOpen = false;
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

    <div
        class="mx-auto max-w-6xl px-4 py-6 space-y-6"
        x-data='window.spReview(@json($projectsJson))'
    >

        {{-- Header --}}
        @include('org.moderator.strategic_plans.partials._header', ['submission' => $submission])

        {{-- Alerts --}}
        @include('org.moderator.strategic_plans.partials._alerts')

        {{-- SACDEV Remarks --}}
        @include('org.moderator.strategic_plans.partials._remarks_sacdev', ['submission' => $submission])

        {{-- Organization overview  --}}
        @include('org.moderator.strategic_plans.partials._org_overview', ['submission' => $submission])




        {{-- Projects Table --}}
        @include('org.moderator.strategic_plans.partials._projects_table', ['submission' => $submission])

        {{-- Project Modal --}}
        @include('org.moderator.strategic_plans.partials._project_modal')

        
        {{--  Fund sources --}}
        @include('org.moderator.strategic_plans.partials._fund_sources', ['submission' => $submission])



        {{-- Summary totals --}}
        @include('org.moderator.strategic_plans.partials._summary', ['submission' => $submission])

        {{-- Moderator Actions --}}
        @include('org.moderator.strategic_plans.partials._actions', ['submission' => $submission])

        {{-- Back --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('org.moderator.rereg.dashboard') }}"
               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Back
            </a>
        </div>

    </div>

</x-app-layout>
