<div
    x-data="{
        canEdit: @js($canEdit),
        status: @js($submission->status),
        editing: @js($submission->status === 'draft' ? true : false),
    }"
    class="rounded-2xl border border-slate-200 bg-gradient-to-b from-white to-slate-50 shadow-sm overflow-hidden"
>

    {{-- HEADER --}}
    <div class="px-5 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div class="space-y-1">
                <div class="flex items-center gap-2 text-[11px] uppercase tracking-wide text-slate-500 font-semibold">
                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                    Strategic Plan
                </div>
                <h2 class="text-base font-semibold text-slate-900">
                    Projects
                </h2>
                <p class="text-xs text-slate-500">
                    Organize and manage all planned projects under each category
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">

                @php
                    $projectCount = $submission->projects->count();
                @endphp

                <span class="text-[10px] px-2.5 py-1 rounded-md font-semibold
                    {{ $projectCount > 0
                        ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200'
                        : 'bg-amber-100 text-amber-700 ring-1 ring-amber-200' }}">
                    {{ $projectCount > 0 ? $projectCount . ' Project' . ($projectCount > 1 ? 's' : '') : 'No Projects Yet' }}
                </span>

                @if($canEdit && $submission->status !== 'draft')

                    <button
                        type="button"
                        x-show="!editing"
                        @click="editing = true"
                        class="inline-flex items-center gap-1 text-[11px] px-3 py-1.5 rounded-lg font-semibold
                               bg-blue-100 text-blue-700 hover:bg-blue-200 transition">
                        <i data-lucide="pencil" class="w-3 h-3"></i>
                        Enable Editing
                    </button>

                    <button
                        type="button"
                        x-show="canEdit && (editing || status === 'draft')"
                        @click="editing = false"
                        class="inline-flex items-center gap-1 text-[11px] px-3 py-1.5 rounded-lg font-semibold
                               bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                        <i data-lucide="x" class="w-3 h-3"></i>
                        Cancel
                    </button>

                @endif

                @if($isApproved)
                    <span class="text-[10px] px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-700 font-semibold ring-1 ring-emerald-200">
                        <i data-lucide="lock" class="w-3 h-3"></i>
                        Locked
                    </span>
                @endif

            </div>

        </div>
    </div>

    {{-- WARNING --}}
    @if($canEdit && !$isApproved && $submission->status !== 'draft')
        <div
            x-show="canEdit && editing"
            x-transition
            class="px-5 py-3 bg-amber-50 border-b border-amber-200 text-[11px] text-amber-700 flex items-center gap-2">
            <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
            Editing projects will reset approval and require resubmission.
        </div>
    @endif

    {{-- CONTENT --}}
    <div class="px-5 py-6 space-y-6">

        @foreach([
            'org_dev' => 'Organization Development',
            'student_services' => 'Student Services',
            'community_involvement' => 'Community Involvement'
        ] as $key => $label)

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                {{-- CATEGORY HEADER --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                    <h3 class="text-sm font-semibold text-slate-900">{{ $label }}</h3>

                    <button
                        type="button"
                        x-show="canEdit && editing"
                        @click="window.Livewire.dispatch('openCreate', { category: '{{ $key }}' })"
                        class="inline-flex items-center gap-1 text-[11px] px-3 py-1.5 rounded-lg font-semibold
                               bg-slate-900 text-white hover:bg-slate-800 transition">
                        + Add Project
                    </button>
                </div>

                {{-- TABLE --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">

                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px]">
                            <tr>
                                <th class="text-left px-4 py-2 font-semibold">Target Date</th>
                                <th class="text-left px-4 py-2 font-semibold">Project Title</th>
                                <th class="text-left px-4 py-2 font-semibold">Details</th>
                                <th class="text-left px-4 py-2 font-semibold">Budget</th>
                                <th class="text-center px-4 py-2 font-semibold">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            @forelse($submission->projects->where('category', $key) as $project)

                                <tr class="hover:bg-slate-50 transition">

                                    {{-- DATE --}}
                                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                                        {{ $project->target_date 
                                            ? \Carbon\Carbon::parse($project->target_date)->format('M d, Y') 
                                            : '—' }}
                                    </td>

                                    {{-- TITLE --}}
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">
                                            {{ $project->title }}
                                        </div>

                                        @if(!empty($project->implementing_body))
                                            <div class="text-[11px] text-slate-500 mt-1">
                                                {{ $project->implementing_body }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- DETAILS --}}
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1 text-[10px] font-medium">

                                            @if($project->objectives->count())
                                                <span class="px-2 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-100">
                                                    {{ $project->objectives->count() }} Objectives
                                                </span>
                                            @endif

                                            @if($project->beneficiaries->count())
                                                <span class="px-2 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    {{ $project->beneficiaries->count() }} Beneficiaries
                                                </span>
                                            @endif

                                            @if($project->deliverables->count())
                                                <span class="px-2 py-1 rounded-md bg-violet-50 text-violet-700 border border-violet-100">
                                                    {{ $project->deliverables->count() }} Deliverables
                                                </span>
                                            @endif

                                            @if($project->partners->count())
                                                <span class="px-2 py-1 rounded-md bg-amber-50 text-amber-700 border border-amber-100">
                                                    {{ $project->partners->count() }} Partners
                                                </span>
                                            @endif

                                            @if(
                                                !$project->objectives->count() &&
                                                !$project->beneficiaries->count() &&
                                                !$project->deliverables->count() &&
                                                !$project->partners->count()
                                            )
                                                <span class="text-slate-400 text-[10px]">
                                                    No details
                                                </span>
                                            @endif

                                        </div>
                                    </td>

                                    {{-- BUDGET --}}
                                    <td class="px-4 py-3 font-medium text-slate-700 whitespace-nowrap">
                                        ₱{{ number_format($project->budget, 0) }}
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="px-4 py-3 text-center whitespace-nowrap">

                                        <div class="flex items-center justify-center gap-2">

                                            <button
                                                type="button"
                                                x-show="!canEdit"
                                                @click="window.Livewire.dispatch('openView', { id: {{ $project->id }} })"
                                                class="border border-slate-300 bg-white px-3 py-1.5 text-xs rounded-lg hover:bg-slate-50 transition">
                                                View
                                            </button>

                                            <div x-show="canEdit && (editing || status === 'draft')" class="flex gap-2">

                                                <button
                                                    type="button"
                                                    @click="window.Livewire.dispatch('openEdit', { id: {{ $project->id }} })"
                                                    class="border border-slate-300 bg-white px-3 py-1.5 text-xs rounded-lg hover:bg-slate-50 transition">
                                                    Edit
                                                </button>

                                                <form
                                                    method="POST"
                                                    action="{{ route('org.rereg.b1.projects.delete', $project->id) }}"
                                                    onsubmit="return confirm('Delete this project?');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="border border-rose-300 text-rose-600 bg-white px-3 py-1.5 text-xs rounded-lg hover:bg-rose-50 transition">
                                                        Delete
                                                    </button>
                                                </form>

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="text-center py-6 text-[11px] text-slate-400">
                                        No projects yet
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

            </section>

        @endforeach

    </div>

    <livewire:rereg.projects-modal :submission-id="$submission->id" :can-edit="$canEdit" :is-approved="$isApproved" />

</div>