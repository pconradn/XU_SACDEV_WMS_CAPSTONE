<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

    <table class="min-w-full text-sm">

        <thead class="bg-slate-50 border-b border-slate-200">
            <tr class="text-left text-slate-700 font-semibold">
                <th class="px-6 py-4">Project</th>
                <th class="px-6 py-4 w-[220px]">Project Head</th>
                <th class="px-6 py-4 w-[200px]">Documents</th>
                @if($isPresident)
                    <th class="px-6 py-4 w-[260px] text-right">Actions</th>
                @endif
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-200">

        @forelse ($projects as $p)

            @php
                $head = $p->assignments
                    ->where('assignment_role', 'project_head')
                    ->first();
            @endphp

            <tr class="hover:bg-slate-50 transition">

                {{-- PROJECT --}}
                <td class="px-6 py-5 align-top">
                    <div class="font-semibold text-slate-900">
                        {{ $p->title }}
                    </div>

                    <div class="text-xs text-slate-500 mt-1">
                        @if($p->target_date)
                            Target: {{ \Carbon\Carbon::parse($p->target_date)->format('M d, Y') }}
                        @else
                            No target date set
                        @endif
                    </div>
                </td>


                {{-- PROJECT HEAD --}}
                <td class="px-6 py-5 align-top">

                    @if($head && $head->officerEntry)

                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-slate-900">
                                {{ $head->officerEntry->full_name }}
                            </span>

                            <span class="text-xs text-slate-500">
                                {{ $head->officerEntry->email }}
                            </span>

                            <span class="mt-1 inline-flex w-fit items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                Assigned
                            </span>
                        </div>

                    @else

                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500">
                            Not assigned
                        </span>

                    @endif

                </td>


                {{-- DOCUMENTS --}}
                <td class="px-6 py-5 align-top">

                    <div class="flex flex-col gap-2">

                        <a href="{{ route('org.projects.documents.hub', $p) }}"
                           class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition">
                            Manage
                        </a>

                        <span class="text-xs text-slate-500">
                            {{ $p->documents_count }} document(s)
                        </span>

                    </div>

                </td>


                {{-- ACTIONS --}}
                @if($isPresident)
                <td class="px-6 py-5 text-right align-top">

                <div class="flex flex-col items-end gap-2">

                    <div class="flex gap-2">

                        {{-- EDIT --}}
                        <button
                            @click='selectedProject = @json($p); openEditModal = true'
                            class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                            Edit Title
                        </button>

                        {{-- ASSIGN --}}
                    <button
                        type="button"
                        @click="selectedProject = { id: {{ $p->id }} }; openAssignHeadModal = true"
                        class="inline-flex items-center rounded-lg bg-slate-800 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700 transition">
                        Assign Head
                    </button>

                    </div>

                    {{-- DELETE SECTION --}}
                    <div class="flex flex-col items-end gap-1">

                        @if($p->documents_count == 0)

                            <form method="POST"
                                action="{{ route('org.projects.destroy', $p) }}"
                                onsubmit="return confirm('Delete this project?');">
                                @csrf
                                @method('DELETE')

                                <button
                                    class="inline-flex items-center rounded-lg bg-rose-500 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-600 transition">
                                    Delete Project
                                </button>
                            </form>

                        @else

                            <button
                                disabled
                                class="inline-flex items-center rounded-lg bg-slate-200 px-3 py-2 text-xs font-semibold text-slate-400 cursor-not-allowed">
                                Delete Project
                            </button>

                            <span class="text-[10px] text-slate-400">
                                Cannot delete — documents already started
                            </span>

                        @endif

                    </div>

                </div>

                </td>
                @endif

            </tr>

        @empty

            <tr>
                <td colspan="{{ $isPresident ? 4 : 3 }}"
                    class="px-6 py-12 text-center text-slate-500">
                    No projects created yet.
                </td>
            </tr>

        @endforelse

        </tbody>
    </table>

</div>