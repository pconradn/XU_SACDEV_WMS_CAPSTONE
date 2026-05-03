<x-app-layout>

    <div x-data="{ 
        openApproverModal: false,
        projectSearch: ''
    }">

    <div class="mx-auto max-w-7xl px-4 py-6 space-y-6">

        {{-- PAGE HEADER --}}
        <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">
            <div class="px-5 py-5 sm:px-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                    <div class="min-w-0">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 border border-blue-100">
                                <i data-lucide="user-cog" class="w-5 h-5"></i>
                            </div>

                            <div>
                                <h1 class="text-lg font-semibold text-slate-900">
                                    Assign Project Heads
                                </h1>
                                <p class="text-xs text-slate-500 mt-1">
                                    Select the responsible project head (officer or department member) for each project under the active encoding context.
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                                <i data-lucide="calendar-range" class="w-3.5 h-3.5 mr-1.5"></i>
                                Encode SY ID: {{ $syId }}
                            </span>

                            <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-medium text-slate-600">
                                <i data-lucide="folder-kanban" class="w-3.5 h-3.5 mr-1.5"></i>
                                {{ $projects->count() }} project{{ $projects->count() !== 1 ? 's' : '' }}
                            </span>

                            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">
                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5 mr-1.5"></i>
                                {{ collect($heads)->count() }} assigned
                            </span>
                        </div>
                    </div>

                    <div class="lg:max-w-sm">
                        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3">
                            <div class="flex items-start gap-2">
                                <i data-lucide="info" class="w-4 h-4 text-amber-600 mt-0.5"></i>
                                <div>
                                    <div class="text-xs font-semibold text-amber-800">
                                        Quick instructions
                                    </div>
                                    <ul class="mt-1 space-y-1 text-xs text-amber-700">
                                        <li>Choose a project and assign one officer as the project head.</li>
                                        <li>Only officers in the current organization context should be selected.</li>
                                        <li>You may update the assigned project head anytime if responsibilities change.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- FLASH --}}
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 shadow-sm">
                <div class="flex items-start gap-2">
                    <i data-lucide="badge-check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                    <div class="text-sm font-medium text-emerald-800">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif

        {{-- MAIN CONTENT --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

            {{-- LEFT: PROJECT LIST --}}
            <div class="xl:col-span-8 space-y-4">

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="list-todo" class="w-4 h-4 text-slate-500"></i>
                                    <h2 class="text-sm font-semibold text-slate-800">
                                        Project Assignment List
                                    </h2>
                                </div>

                                <p class="mt-1 text-xs text-slate-500">
                                    Review each project below and assign or update its designated project head.
                                </p>
                            </div>

                            <div class="w-full sm:max-w-xs">
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                                    </div>

                                    <input
                                        type="text"
                                        x-model.debounce.150ms="projectSearch"
                                        placeholder="Search projects..."
                                        class="w-full rounded-xl border border-slate-300 bg-white py-2 pl-9 pr-3 text-sm text-slate-700 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="max-h-[720px] overflow-y-auto">
                    @forelse($projects as $p)
                        @php
                            $assignment = $p->assignments
                                ->where('assignment_role', 'project_head')
                                ->whereNull('archived_at')
                                ->first();

                            $user = $assignment?->user;

                            $assignedHead = $user;
                        @endphp

                        <div
                            x-show="'{{ strtolower(addslashes($p->title)) }}'.includes(projectSearch.toLowerCase())"
                            x-cloak
                            class="px-5 py-4 border-b last:border-b-0 border-slate-100 hover:bg-slate-50/70 transition"
                        >
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                                {{-- PROJECT INFO --}}
                                <div class="min-w-0">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-600">
                                            <i data-lucide="folder-open" class="w-4 h-4"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <h3 class="text-sm font-semibold text-slate-900 break-words">
                                                {{ $p->title }}
                                            </h3>

                                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                                @if($assignedHead)
                                                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 mr-1.5"></i>
                                                        Head Assigned
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                                        <i data-lucide="circle-help" class="w-3.5 h-3.5 mr-1.5"></i>
                                                        No Head Yet
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="mt-3 text-xs text-slate-600">
                                                @if($assignedHead)
                                                    <span class="font-medium text-slate-700">Current head:</span>
                                                    {{ $assignedHead?->name 
                                                        ?? $assignedHead?->email 
                                                        ?? 'Assigned User' }}
                                                    @if(!empty($assignedHead?->email))
                                                        <span class="text-slate-400">• {{ $assignedHead->email }}</span>
                                                    @endif
                                                @else
                                                    No officer has been assigned to this project yet.
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ACTION --}}
                                <div
                                    x-data="{ open: false }"
                                    class="shrink-0"
                                >
                                    <button
                                        type="button"

                                        @click="
                                            @if(isset($missingRoles) && $missingRoles->isNotEmpty())
                                                openApproverModal = true
                                            @else
                                                open = true
                                            @endif
                                        "
                                        class="inline-flex items-center justify-center gap-2 rounded-xl border px-4 py-2 text-xs font-semibold shadow-sm transition
                                            {{ $assignedHead
                                                ? 'border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100'
                                                : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}"
                                    >
                                        <i data-lucide="{{ $assignedHead ? 'pencil-line' : 'user-plus' }}" class="w-4 h-4"></i>
                                        {{ $assignedHead ? 'Update Assignment' : 'Assign Project Head' }}
                                    </button>

                                    {{-- MODAL --}}
                                    <div
                                        x-show="open"
                                        x-data="{ 
                                            type: '{{ $assignment && $assignment->officerEntry ? 'officer' : 'member' }}', 
                                            selectedDept: '' 
                                        }"
                                        x-cloak
                                        x-transition.opacity
                                        class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/50 backdrop-blur-sm px-3"
                                        style="display: none;"
                                    >
                                        <div
                                            @click.away="open = false"
                                            x-transition
                                            class="w-full max-w-2xl rounded-2xl border border-slate-200 bg-white shadow-2xl overflow-hidden"
                                        >

                                            {{-- MODAL HEADER --}}
                                            <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-b from-slate-50 to-white">
                                                <div class="flex items-start justify-between gap-4">
                                                    <div class="min-w-0">
                                                        <div class="flex items-center gap-2">
                                                            <div class="flex h-9 w-9 items-center justify-center rounded-xl border border-blue-100 bg-blue-50 text-blue-600">
                                                                <i data-lucide="user-cog" class="w-4 h-4"></i>
                                                            </div>
                                                            <div>
                                                                <h3 class="text-sm font-semibold text-slate-900">
                                                                    {{ $assignedHead ? 'Update Project Head' : 'Assign Project Head' }}
                                                                </h3>
                                                                <p class="text-xs text-slate-500 mt-1">
                                                                    Select either an officer or a department member who will be responsible for this project.
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="mt-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
                                                            <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                                                Project
                                                            </div>
                                                            <div class="mt-1 text-sm font-medium text-slate-800 break-words">
                                                                {{ $p->title }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button
                                                        type="button"
                                                        @click="open = false"
                                                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition"
                                                    >
                                                        <i data-lucide="x" class="w-4 h-4"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- MODAL BODY --}}
                                            <form method="POST" action="{{ route('org.assign-project-heads.update', $p) }}">
                                                @csrf

                                                <div class="px-5 py-5 space-y-5">

                                                    {{-- GUIDANCE --}}
                                                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3">
                                                        <div class="flex items-start gap-2">
                                                            <i data-lucide="shield-alert" class="w-4 h-4 text-amber-600 mt-0.5"></i>
                                                            <div>
                                                                <div class="text-xs font-semibold text-amber-800">
                                                                    Before assigning
                                                                </div>
                                                                <ul class="mt-1 space-y-1 text-xs text-amber-700">
                                                                    <li>Make sure the selected person is included in the current officer list.</li>
                                                                    <li>Choose the officer who will prepare and manage this project's required submissions.</li>
                                                                    <li>Updating this assignment later is allowed if the organization changes responsibilities.</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- CURRENT STATUS --}}
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                                            <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                                                Assignment Status
                                                            </div>
                                                            <div class="mt-2">
                                                                @if($assignedHead)
                                                                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 mr-1.5"></i>
                                                                        Assigned
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                                                        <i data-lucide="circle-help" class="w-3.5 h-3.5 mr-1.5"></i>
                                                                        Not Yet Assigned
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                                            <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                                                Current Head
                                                            </div>
                                                            <div class="mt-2 text-sm text-slate-700">
                                                                {{ $assignedHead?->name ?? $assignedHead?->email ?? 'None assigned yet' }}
                                                            </div>
                                                            @if($assignedHead && !empty($assignedHead->email))
                                                                <div class="mt-1 text-xs text-slate-500">
                                                                    {{ $assignedHead->email }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- SELECT --}}
                                                    <div>
                                                        <label class="block text-xs font-semibold text-slate-700 mb-2">
                                                            Assignment Type
                                                        </label>

                                                        <div class="flex gap-2 mb-3">

                                                            <button type="button"
                                                                @click="type = 'officer'"
                                                                :class="type === 'officer'
                                                                    ? 'bg-blue-600 text-white'
                                                                    : 'bg-white text-slate-700 border border-slate-300'"
                                                                class="px-3 py-1.5 text-xs rounded-lg">
                                                                Officer
                                                            </button>

                                                            <button type="button"
                                                                @click="type = 'member'"
                                                                :class="type === 'member'
                                                                    ? 'bg-indigo-600 text-white'
                                                                    : 'bg-white text-slate-700 border border-slate-300'"
                                                                class="px-3 py-1.5 text-xs rounded-lg">
                                                                Department Member
                                                            </button>

                                                        </div>

                                                        <input type="hidden" name="assignment_type" :value="type">

                                                        <div class="relative">


                                                            <div x-show="type === 'officer'">
                                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                                    <i data-lucide="users" class="w-4 h-4 text-slate-400"></i>
                                                                </div>

                                                                <select
                                                                    name="officer_id"
                                                                    class="w-full rounded-2xl border border-slate-300 bg-white pl-10 pr-4 py-3 text-sm text-slate-800 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                                                    
                                                                >
                                                                    <option value="">Select an officer...</option>
                                                                    @foreach($officers as $o)
                                                                        @php
                                                                            $selectedId = $assignment?->user_id ?? null;
                                                                        @endphp
                                                                        <option value="{{ $o->id }}"
                                                                            {{ $assignment && $assignment->user_id == $o->user_id ? 'selected' : '' }}>
                                                                            {{ $o->full_name }}{{ !empty($o->email) ? ' ('.$o->email.')' : '' }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>


                                                            </div>

                                                            <div x-show="type === 'member'" class="space-y-3">

                                                                {{-- DEPARTMENT --}}
                                                                <div>
                                                                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                                                                        Select Department
                                                                    </label>

                                                                    <select name="department_id"
                                                                            x-model="selectedDept"
                                                                            class="w-full rounded-2xl border border-slate-300 text-sm">
                                                                        <option value="">Choose department...</option>

                                                                        @foreach($departments as $dept)
                                                                            <option value="{{ $dept->id }}">
                                                                                {{ $dept->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                {{-- MEMBER --}}
                                                                <div>
                                                                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                                                                        Select Member
                                                                    </label>

                                                                    <select name="member_id"
                                                                            class="w-full rounded-2xl border border-slate-300 text-sm">

                                                                        <option value="">Choose member...</option>

                                                                    @foreach($members as $m)
                                                                        <option value="{{ $m->id }}"
                                                                            x-bind:hidden="selectedDept && selectedDept != '{{ $m->department_id }}'"
                                                                            {{ $assignment && $m->user_id && $assignment->user_id == $m->user_id ? 'selected' : '' }}>
                                                                            {{ $m->full_name }}
                                                                        </option>
                                                                    @endforeach

                                                                    </select>
                                                                </div>

                                                            </div>


                                                        </div>

                                                        @error('officer_id')
                                                            <div class="mt-2 text-xs font-medium text-rose-600">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror

                                                        <div class="mt-2 text-xs text-slate-500">
                                                            Only users within the current organization context will appear (officers or department members).
                                                        </div>
                                                    </div>

                                                </div>

                                                {{-- MODAL FOOTER --}}
                                                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                                                    <div class="text-xs text-slate-500">
                                                        Changes will take effect immediately after saving.
                                                    </div>

                                                    <div class="flex items-center gap-2">
                                                        <button
                                                            type="button"
                                                            @click="open = false"
                                                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition"
                                                        >
                                                            Cancel
                                                        </button>

                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-700 transition"
                                                        >
                                                            <i data-lucide="save" class="w-4 h-4"></i>
                                                            {{ $assignedHead ? 'Update Assignment' : 'Save Assignment' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-14 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-400">
                                <i data-lucide="folder-search" class="w-5 h-5"></i>
                            </div>
                            <h3 class="mt-4 text-sm font-semibold text-slate-800">
                                No projects found
                            </h3>
                            <p class="mt-1 text-xs text-slate-500">
                                There are currently no available projects in this school year context.
                            </p>
                        </div>
                    @endforelse
                    </div>
                </div>
            </div>

            {{-- RIGHT: HELP / SUMMARY --}}
            <div class="xl:col-span-4 space-y-4">

                <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <i data-lucide="clipboard-list" class="w-4 h-4 text-slate-500"></i>
                            <h2 class="text-sm font-semibold text-slate-800">
                                Assignment Notes
                            </h2>
                        </div>
                    </div>

                    <div class="p-5 space-y-4 text-xs text-slate-600">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-blue-50 text-blue-600 border border-blue-100">
                                1
                            </div>
                            <div>
                                <div class="font-semibold text-slate-700">Review each project</div>
                                <div class="mt-1">Check whether a project head has already been assigned before making changes.</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                2
                            </div>
                            <div>
                                <div class="font-semibold text-slate-700">Select the proper officer</div>
                                <div class="mt-1">The assigned project head should be the officer responsible for preparing and coordinating project requirements.</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full bg-amber-50 text-amber-600 border border-amber-100">
                                3
                            </div>
                            <div>
                                <div class="font-semibold text-slate-700">Update when needed</div>
                                <div class="mt-1">Assignments may be revised later if officer roles change or responsibilities are reassigned.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 text-slate-500"></i>
                            <h2 class="text-sm font-semibold text-slate-800">
                                Quick Summary
                            </h2>
                        </div>
                    </div>

                    <div class="p-5 grid grid-cols-1 gap-3">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <div class="text-[11px] uppercase tracking-wide font-semibold text-slate-500">
                                Total Projects
                            </div>
                            <div class="mt-1 text-lg font-semibold text-slate-900">
                                {{ $projects->count() }}
                            </div>
                        </div>

                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                            <div class="text-[11px] uppercase tracking-wide font-semibold text-emerald-700">
                                Assigned
                            </div>
                            <div class="mt-1 text-lg font-semibold text-emerald-800">
                                {{ collect($heads)->count() }}
                            </div>
                        </div>

                        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
                            <div class="text-[11px] uppercase tracking-wide font-semibold text-amber-700">
                                Unassigned
                            </div>
                            <div class="mt-1 text-lg font-semibold text-amber-800">
                                {{ max($projects->count() - collect($heads)->count(), 0) }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>




        <div x-show="openApproverModal"
            x-cloak
            x-transition.opacity
            class="fixed inset-0 z-[1000] flex items-center justify-center bg-slate-950/50 backdrop-blur-sm px-4">

            <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white shadow-2xl overflow-hidden">

                {{-- HEADER --}}
                <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-b from-slate-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                            <i data-lucide="shield-alert" class="w-5 h-5"></i>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">
                                Approvers Required
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Required roles must be assigned before continuing.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- BODY --}}
                <div class="px-5 py-5 space-y-4 text-xs text-slate-600">

                    <div class="flex items-start gap-2 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-amber-700">
                        <i data-lucide="info" class="w-4 h-4 mt-0.5"></i>
                        <span>
                            You must assign all approver roles before assigning project heads.
                        </span>
                    </div>

                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 mb-2">
                            Missing Roles
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @foreach($missingRoles ?? [] as $role)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 text-[11px]">
                                    <i data-lucide="user-x" class="w-3.5 h-3.5 text-rose-500"></i>
                                    {{ str_replace('_',' ', $role) }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50 flex justify-between items-center">

                    <button
                        @click="openApproverModal = false"
                        class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        Cancel
                    </button>

                    <a href="{{ route('org.approver-assignments.edit') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition shadow-sm">
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        Assign Now
                    </a>

                </div>

            </div>
        </div>

    </div>

</x-app-layout>