<x-app-layout>

<div class="max-w-6xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-6">
            <h1 class="text-2xl font-bold text-slate-900">
                COA Assignment
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Assign COA officers to projects by organization and school year.
            </p>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <form method="GET"
          class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- COA --}}
            <div>
                <label class="text-xs font-semibold text-slate-600">COA Officer</label>
                <select name="user_id"
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Select COA</option>
                    @foreach($coaUsers as $u)
                        <option value="{{ $u->id }}"
                            {{ $selectedUser == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                            @if($u->is_default_coa) (Default) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ORG --}}
            <div>
                <label class="text-xs font-semibold text-slate-600">Organization</label>
                <select name="organization_id"
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Select Organization</option>
                    @foreach($orgs as $org)
                        <option value="{{ $org->id }}"
                            {{ $selectedOrg == $org->id ? 'selected' : '' }}>
                            {{ $org->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- SY --}}
            <div>
                <label class="text-xs font-semibold text-slate-600">School Year</label>
                <select name="school_year_id"
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Select School Year</option>
                    @foreach($schoolYears as $sy)
                        <option value="{{ $sy->id }}"
                            {{ $selectedSy == $sy->id ? 'selected' : '' }}>
                            {{ $sy->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="px-6 pb-6 flex justify-end">
            <button class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                Load Projects
            </button>
        </div>

    </form>

    {{-- PROJECT LIST --}}
    @if($projects->count())

    <form method="POST" action="{{ route('admin.coa.bulk-update') }}"
          class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        @csrf

        <input type="hidden" name="user_id" value="{{ $selectedUser }}">
        <input type="hidden" name="organization_id" value="{{ $selectedOrg }}">
        <input type="hidden" name="school_year_id" value="{{ $selectedSy }}">

        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <div class="text-sm font-semibold text-slate-800">
                Projects ({{ $projects->count() }})
            </div>
        </div>

        <div class="divide-y divide-slate-100">

            @foreach($projects as $project)

                @php
                    $assignedId = $project->coaAssignment?->user_id;
                    $isChecked = $selectedUser && $assignedId == $selectedUser;
                @endphp

                <label class="flex items-center justify-between px-6 py-4 hover:bg-slate-50">

                    <div class="min-w-0">

                        <div class="text-sm font-medium text-slate-900">
                            {{ $project->title }}
                        </div>

                        <div class="text-xs text-slate-500 mt-1 flex items-center gap-2">

                            {{-- CURRENT ASSIGNED --}}
                            @if($project->coaAssignment && $project->coaAssignment->coaOfficer)
                                <span class="px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 border border-purple-100 text-[10px]">
                                    Assigned: {{ $project->coaAssignment->coaOfficer->name }}
                                </span>
                            @else
                                <span class="text-slate-400">
                                    No COA assigned
                                </span>
                            @endif

                        </div>

                    </div>

                    <input type="checkbox"
                           name="project_ids[]"
                           value="{{ $project->id }}"
                           {{ $isChecked ? 'checked' : '' }}
                           class="rounded border-slate-300 text-purple-600 focus:ring-purple-500">

                </label>

            @endforeach

        </div>

        <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-2 bg-slate-50">

            <button type="submit"
                    class="rounded-xl bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">
                Save Assignments
            </button>

        </div>

    </form>

    @elseif(request('organization_id') && request('school_year_id'))

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6 text-center text-sm text-slate-500">
            No projects found for selected filters.
        </div>

    @endif

</div>

</x-app-layout>