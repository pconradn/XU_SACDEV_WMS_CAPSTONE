<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                President Assignments (Elections)
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash --}}
            @if(session('status'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                    <div class="font-semibold mb-1">Please fix the following:</div>
                    <ul class="list-disc ml-5 text-sm">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow rounded p-6">

                {{-- SY selector --}}
                <form method="GET" class="flex flex-col md:flex-row md:items-end gap-3 mb-6">
                    <div class="w-full md:w-80">
                        <label class="block text-sm font-medium text-slate-700">Target School Year</label>
                        <select name="school_year_id" class="mt-1 w-full border rounded p-2" required>
                            <option value="">-- Select SY --</option>
                            @foreach($schoolYears as $sy)
                                <option value="{{ $sy->id }}" @selected($selectedSyId == $sy->id)>
                                    {{ $sy->name }} {{ $sy->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="px-4 py-2 bg-blue-600 text-white rounded">
                        Load Organizations
                    </button>

                    <div class="flex-1"></div>

                    <div class="w-full md:w-72">
                        <label class="block text-sm font-medium text-slate-700">Search Org</label>
                        <input id="orgSearch" type="text" class="mt-1 w-full border rounded p-2"
                               placeholder="Type org name or acronym...">
                    </div>
                </form>

                @if($selectedSyId <= 0)
                    <div class="text-sm text-slate-600">
                        Select a school year to start.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead class="text-xs uppercase text-slate-500 border-b">
                                <tr>
                                    <th class="py-2 px-2">Organization</th>
                                    <th class="py-2 px-2">Status</th>
                                    <th class="py-2 px-2">Current President</th>
                                    <th class="py-2 px-2 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody id="orgTbody" class="divide-y">
                                @foreach($organizations as $org)
                                    @php
                                        $assigned = $assignedMap->get($org->id);
                                    @endphp
                                    <tr class="org-row"
                                        data-org="{{ strtolower($org->name . ' ' . ($org->acronym ?? '')) }}">
                                        <td class="py-3 px-2">
                                            <div class="font-semibold text-slate-900">
                                                {{ $org->name }}
                                            </div>
                                            @if($org->acronym)
                                                <div class="text-xs text-slate-500">{{ $org->acronym }}</div>
                                            @endif
                                        </td>

                                        <td class="py-3 px-2">
                                            @if($assigned)
                                                <span class="inline-flex rounded-full bg-emerald-50 border border-emerald-200 px-2 py-1 text-xs font-semibold text-emerald-700">
                                                    Assigned
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full bg-slate-50 border border-slate-200 px-2 py-1 text-xs font-semibold text-slate-700">
                                                    Not Assigned
                                                </span>
                                            @endif
                                        </td>

                                        <td class="py-3 px-2">
                                            @if($assigned)
                                                <div class="text-slate-900 font-medium">
                                                    {{ $assigned->full_name }}
                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $assigned->student_id_number }}
                                                </div>
                                            @else
                                                <span class="text-slate-400 text-sm">—</span>
                                            @endif
                                        </td>

                                        <td class="py-3 px-2 text-right">
                                            <button type="button"
                                                class="openAssignModal px-3 py-2 rounded bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700"
                                                data-org-id="{{ $org->id }}"
                                                data-org-name="{{ $org->name }}"
                                                data-sy-id="{{ $selectedSyId }}">
                                                {{ $assigned ? 'Replace' : 'Assign' }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>


    {{-- Modal --}}
    <div id="assignModal" class="fixed inset-0 hidden items-center justify-center bg-black/40 p-4">
        <div class="w-full max-w-lg rounded-xl bg-white shadow-lg">
            <div class="p-5 border-b">
                <div class="text-lg font-semibold text-slate-900">Assign President</div>
                <div id="modalOrgName" class="text-sm text-slate-600 mt-1"></div>
            </div>

            <form method="POST" action="{{ route('admin.president_assignments.assign') }}" class="p-5">
                @csrf
                <input type="hidden" name="organization_id" id="modalOrgId">
                <input type="hidden" name="school_year_id" id="modalSyId">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">President Full Name</label>
                        <input name="president_name" id="modalPresidentName"
                               class="mt-1 w-full border rounded p-2"
                               placeholder="Juan Dela Cruz" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Student ID Number</label>
                        <input name="student_id_number" id="modalStudentId"
                               class="mt-1 w-full border rounded p-2"
                               placeholder="2018xxxxxxx" required>

                        <div class="mt-2 text-sm text-slate-500">
                            Email will be generated:
                            <span id="modalEmailPreview" class="font-semibold text-blue-600">
                                studentID@xu.edu.ph
                            </span>
                        </div>
                    </div>

                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800">
                        Note: If the current president is already activated, replacement is blocked and must be done via the Major Officer Roles page (Active SY only).
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" id="closeAssignModal"
                            class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                        Cancel
                    </button>
                    <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const search = document.getElementById('orgSearch');
        const rows = document.querySelectorAll('.org-row');

        if (search) {
            search.addEventListener('input', () => {
                const q = search.value.trim().toLowerCase();
                rows.forEach(r => {
                    const hay = r.getAttribute('data-org') || '';
                    r.style.display = hay.includes(q) ? '' : 'none';
                });
            });
        }

        const modal = document.getElementById('assignModal');
        const modalOrgName = document.getElementById('modalOrgName');
        const modalOrgId = document.getElementById('modalOrgId');
        const modalSyId = document.getElementById('modalSyId');

        const modalStudentId = document.getElementById('modalStudentId');
        const modalEmailPreview = document.getElementById('modalEmailPreview');

        function openModal(btn) {
            modalOrgName.textContent = btn.dataset.orgName || '';
            modalOrgId.value = btn.dataset.orgId || '';
            modalSyId.value = btn.dataset.syId || '';

            document.getElementById('modalPresidentName').value = '';
            modalStudentId.value = '';
            modalEmailPreview.textContent = 'studentID@xu.edu.ph';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.querySelectorAll('.openAssignModal').forEach(btn => {
            btn.addEventListener('click', () => openModal(btn));
        });

        document.getElementById('closeAssignModal')?.addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        modalStudentId.addEventListener('input', () => {
            const id = modalStudentId.value.trim();
            modalEmailPreview.textContent = id ? `${id}@xu.edu.ph` : 'studentID@xu.edu.ph';
        });
    </script>

</x-app-layout>