<x-app-layout style="border: 1px solid transparent;">

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


    {{--  USE ADMIN LOGIC --}}
    <script>
        window.spAdminReview = function (projects) {
            return {
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
                    return v.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                },
                countFilled(arr) {
                    if (!Array.isArray(arr)) return 0;
                    return arr.filter(x => String(x ?? '').trim().length > 0).length;
                },
            };
        }
    </script>


    <div
        
        x-data='window.spAdminReview(@json($projectsJson))' 
    >

        
        @include('org.moderator.strategic_plans.partials._header', ['submission' => $submission])

       
        @include('admin.strategic_plans.partials._identity', ['submission' => $submission])

      
        @include('admin.strategic_plans.partials._projects', ['submission' => $submission])

    
        @include('admin.strategic_plans.partials._funds', ['submission' => $submission])

       
        @include('org.moderator.strategic_plans.partials._actions', ['submission' => $submission])

        
        @include('admin.strategic_plans.partials._project_modal')

        {{-- BACK --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('org.moderator.rereg.dashboard') }}"
               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Back
            </a>
        </div>

    </div>

</x-app-layout>