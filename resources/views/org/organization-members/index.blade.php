<x-app-layout>


<style>
    /* Remove default DataTables styles */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 0.875rem;
        color: #475569; /* slate-600 */
    }

    /* Remove default borders */
    table.dataTable.no-footer {
        border-bottom: none !important;
    }

    /* Fix search + select inputs */
    .dataTables_filter input,
    .dataTables_length select {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        padding: 4px 8px !important;
        outline: none !important;
    }

    /* Pagination base */
    .dataTables_paginate .paginate_button {
        border: none !important;
        background: transparent !important;
        margin: 0 2px;
    }

    /* Active page */
    .dataTables_paginate .paginate_button.current {
        background: #2563eb !important; /* blue-600 */
        color: white !important;
        border-radius: 0.5rem;
    }

    /* Hover */
    .dataTables_paginate .paginate_button:hover {
        background: #f1f5f9 !important; /* slate-100 */
        border-radius: 0.5rem;
    }
</style>
<style>
    /* Fix dropdown arrow overlap */
    .dataTables_length select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;

        padding-right: 2rem !important; /* space for arrow */
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 12px;

        /* Custom arrow */
        background-image: url("data:image/svg+xml;utf8,<svg fill='%2364758b' height='20' viewBox='0 0 20 20' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M5 7l5 5 5-5z'/></svg>");
    }
</style>



    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-lg font-semibold text-slate-900">
                    Organization Members
                </h2>
                <p class="text-sm text-slate-500">
                    List of registered members for this organization
                </p>
            </div>

            {{-- ADD BUTTON (PRESIDENT ONLY) --}}
            @if($isPresident)
                <button
                    onclick="openAddModal()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
                    + Add Member
                </button>
            @endif

        </div>

        @if($isPresident)
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">

            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">
                        Departments
                    </h3>
                    <p class="text-xs text-slate-500">
                        Manage departments and assign members
                    </p>
                </div>

                <button onclick="openDeptModal()"
                    class="text-xs px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    + Add Department
                </button>
            </div>

            <div class="flex flex-wrap gap-2">
                @foreach($departments as $dept)
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-100 rounded-lg text-xs">

                        <span>{{ $dept->name }}</span>

                        <button onclick="editDept({{ $dept->id }}, '{{ $dept->name }}')"
                            class="text-blue-600 hover:underline">Edit</button>

                        <form method="POST"
                            action="{{ route('org.departments.destroy', $dept->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">
                                Archive
                            </button>
                        </form>

                    </div>
                @endforeach
            </div>

        </div>
        @endif


        {{-- ================= TABLE CARD ================= --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- HEADER --}}
            <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
                <div class="text-sm font-semibold text-slate-900">
                    Members Directory
                </div>
                <div class="text-xs text-slate-500">
                    Showing all members under current school year
                </div>
            </div>







            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table id="membersTable" class="min-w-full text-sm text-slate-700">

                    <thead class="bg-slate-100 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Student ID</th>
                            <th class="px-4 py-3 text-left">Course & Year</th>
                            <th class="px-4 py-3 text-left">QPI</th>
                            <th class="px-4 py-3 text-left">Contact</th>

                            @if($isPresident)
                                <th class="px-4 py-3 text-left">Department</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @forelse($members as $member)

                            <tr class="hover:bg-slate-50 transition">

                                {{-- NAME --}}
                                <td class="px-4 py-3 font-medium text-slate-900">
                                    {{ $member->full_name }}
                                </td>

                                {{-- STUDENT ID --}}
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $member->student_id_number ?? '-' }}
                                </td>

                                {{-- COURSE --}}
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $member->course_and_year ?? '-' }}
                                </td>

                                {{-- QPI --}}
                                <td class="px-4 py-3">
                                    @if($member->latest_qpi)
                                        <span class="px-2 py-1 text-xs rounded bg-blue-50 text-blue-700">
                                            {{ number_format($member->latest_qpi, 2) }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">N/A</span>
                                    @endif
                                </td>

                                {{-- CONTACT --}}
                                <td class="px-4 py-3 text-slate-600">
                                    <div class="text-xs">
                                        {{ $member->email ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        {{ $member->mobile_number ?? '' }}
                                    </div>
                                </td>

                                {{-- ACTIONS (PRESIDENT ONLY) --}}
                                @if($isPresident)
                                    <td class="px-4 py-3">
                                        @if($isPresident)

                                            <form method="POST"
                                                action="{{ route('org.members.assign-department', $member->id) }}">
                                                @csrf
                                                @method('PUT')

                                                <select name="department_id"
                                                        onchange="this.form.submit()"
                                                        class="text-xs rounded border-slate-300">

                                                    <option value="">No Department</option>

                                                    @foreach($departments as $dept)
                                                        <option value="{{ $dept->id }}"
                                                            {{ $member->department_id == $dept->id ? 'selected' : '' }}>
                                                            {{ $dept->name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </form>

                                        @else
                                            <span class="text-xs text-slate-600">
                                                {{ $member->department->name ?? '-' }}
                                            </span>
                                        @endif
                                    </td>




                                    <td class="px-4 py-3 text-right">

                                        <div class="flex justify-end gap-2">

                                            {{-- EDIT --}}
                                            <button
                                                onclick="openEditModal({{ $member->id }})"
                                                class="text-xs px-3 py-1 rounded bg-amber-50 text-amber-700 hover:bg-amber-100">
                                                Edit
                                            </button>

                                            {{-- DELETE --}}
                                            <form method="POST"
                                                  action="{{ route('org.organization-members.destroy', $member->id) }}"
                                                  onsubmit="return confirm('Remove this member?')">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    class="text-xs px-3 py-1 rounded bg-red-50 text-red-700 hover:bg-red-100">
                                                    Remove
                                                </button>
                                            </form>

                                        </div>

                                    </td>
                                @endif

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center py-10 text-slate-400 text-sm">
                                    No members found for this organization.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>



    <div id="deptModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl w-full max-w-sm shadow-xl">

            <form method="POST" action="{{ route('org.departments.store') }}">
                @csrf

                <div class="p-4 space-y-3">

                    <h2 class="text-sm font-semibold">Add Department</h2>

                    <input name="name"
                        placeholder="Department name"
                        required
                        class="w-full rounded-lg border-slate-200 text-sm">

                </div>

                <div class="px-4 py-3 border-t flex justify-end gap-2">

                    <button type="button" onclick="closeDeptModal()"
                        class="text-sm text-slate-600">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg">
                        Save
                    </button>

                </div>

            </form>

        </div>
    </div>

    {{-- ================= ADD MEMBER MODAL ================= --}}
    <div id="addModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl w-full max-w-xl shadow-xl overflow-hidden">

            {{-- HEADER --}}
            <div class="px-5 py-3 border-b">
                <h2 class="text-sm font-semibold text-slate-900">Add Member</h2>
            </div>

            {{-- FORM --}}
            <form method="POST" action="{{ route('org.organization-members.store') }}">
                @csrf

                <div class="p-5 space-y-4">

                    {{-- NAME --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                        <div>
                            <label class="text-xs text-slate-500">First Name</label>
                            <input name="first_name" required
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                        <div>
                            <label class="text-xs text-slate-500">Last Name</label>
                            <input name="last_name" required
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                        <div>
                            <label class="text-xs text-slate-500">MI</label>
                            <input name="middle_initial"
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                    </div>

                    {{-- QPI --}}
                    <div>
                        <label class="text-xs text-slate-500">Latest QPI</label>
                        <input type="number" step="0.01" name="latest_qpi"
                            class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                    </div>

                    {{-- OTHER INFO --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                        <div>
                            <label class="text-xs text-slate-500">Student ID</label>
                            <input name="student_id_number"
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                        <div>
                            <label class="text-xs text-slate-500">Course & Year</label>
                            <input name="course_and_year"
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                        <div>
                            <label class="text-xs text-slate-500">Email</label>
                            <input name="email"
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                        <div>
                            <label class="text-xs text-slate-500">Mobile</label>
                            <input name="mobile_number"
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                    </div>

                    {{-- DEPARTMENT --}}
                    <div>
                        <label class="text-xs text-slate-500">Department</label>

                        <select name="department_id"
                            class="mt-1 w-full rounded-lg border-slate-200 text-sm">

                            <option value="">No Department</option>

                            @if(!empty($departments) && count($departments))
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            @endif

                        </select>

                        @if(empty($departments) || !count($departments))
                            <p class="text-xs text-slate-400 mt-1">
                                No departments yet. You can still add the member.
                            </p>
                        @endif
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="px-5 py-3 border-t flex justify-end gap-2">

                    <button type="button" onclick="closeAddModal()"
                        class="text-sm text-slate-600">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        Save
                    </button>

                </div>

            </form>

        </div>
    </div>


    {{-- ================= EDIT MEMBER MODAL ================= --}}
    <div id="editModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl w-full max-w-xl shadow-xl overflow-hidden">

            {{-- HEADER --}}
            <div class="px-5 py-3 border-b">
                <h2 class="text-sm font-semibold text-slate-900">Edit Member</h2>
            </div>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="p-5 space-y-4">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                        <div>
                            <label class="text-xs text-slate-500">First Name</label>
                            <input id="edit_first_name" name="first_name" required
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                        <div>
                            <label class="text-xs text-slate-500">Last Name</label>
                            <input id="edit_last_name" name="last_name" required
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                        <div>
                            <label class="text-xs text-slate-500">MI</label>
                            <input id="edit_mi" name="middle_initial"
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                    </div>

                    <div>
                        <label class="text-xs text-slate-500">Latest QPI</label>
                        <input id="edit_qpi" name="latest_qpi" type="number" step="0.01"
                            class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                        <div>
                            <label class="text-xs text-slate-500">Student ID</label>
                            <input id="edit_sid" name="student_id_number"
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                        <div>
                            <label class="text-xs text-slate-500">Course & Year</label>
                            <input id="edit_course" name="course_and_year"
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                        <div>
                            <label class="text-xs text-slate-500">Email</label>
                            <input id="edit_email" name="email"
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                        <div>
                            <label class="text-xs text-slate-500">Mobile</label>
                            <input id="edit_mobile" name="mobile_number"
                                class="mt-1 w-full rounded-lg border-slate-200 text-sm">
                        </div>

                    </div>

                    {{-- DEPARTMENT --}}
                    <div>
                        <label class="text-xs text-slate-500">Department</label>

                        <select id="edit_department_id" name="department_id"
                            class="mt-1 w-full rounded-lg border-slate-200 text-sm">

                            <option value="">No Department</option>

                            @if(!empty($departments) && count($departments))
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            @endif

                        </select>

                        @if(empty($departments) || !count($departments))
                            <p class="text-xs text-slate-400 mt-1">
                                No departments yet. You can still edit the member.
                            </p>
                        @endif
                    </div>


                </div>

                <div class="px-5 py-3 border-t flex justify-end gap-2">

                    <button type="button" onclick="closeEditModal()"
                        class="text-sm text-slate-600">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>

    {{-- ================= JS ================= --}}
    <script>



        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openEditModal(id) {

            const members = @json($members);

            const m = members.find(x => x.id === id);
            if (!m) return;

            document.getElementById('edit_first_name').value = m.first_name ?? '';
            document.getElementById('edit_last_name').value = m.last_name ?? '';
            document.getElementById('edit_mi').value = m.middle_initial ?? '';
            document.getElementById('edit_qpi').value = m.latest_qpi ?? '';
            document.getElementById('edit_sid').value = m.student_id_number ?? '';
            document.getElementById('edit_course').value = m.course_and_year ?? '';
            document.getElementById('edit_email').value = m.email ?? '';
            document.getElementById('edit_mobile').value = m.mobile_number ?? '';
            document.getElementById('edit_department_id').value = m.department_id ?? '';

            document.getElementById('editForm').action =
                `/org/organization-members/${id}`;

            document.getElementById('editModal').classList.remove('hidden');
        }
    </script>

    <script>
    $(document).ready(function () {

        let options = {
            pageLength: 10,
            lengthMenu: [10, 25, 50],
            ordering: true,
            responsive: true,

            dom: `
                <"flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4"
                    <"flex items-center gap-2 text-sm text-slate-600"l>
                    <"flex items-center text-sm text-slate-600"f>
                >
                t
                <"flex flex-col md:flex-row md:items-center md:justify-between gap-3 mt-4"
                    <"text-sm text-slate-500"i>
                    <"flex items-center gap-1"p>
                >
            `
        };

        @if($isPresident)
            options.columnDefs = [
                { orderable: false, targets: 5 }
            ];
        @endif

        let hasRealRows = $('#membersTable tbody tr').filter(function () {
            return $(this).find('td').length > 1;
        }).length > 0;

        if (hasRealRows) {
            let table = $('#membersTable').DataTable(options);
        }

        /* ===== FORCE TAILWIND LOOK ===== */

        $('.dataTables_wrapper').addClass('px-5 pb-4');

        $('.dataTables_length select').addClass(
            'bg-white text-slate-700 text-sm rounded-lg px-3 py-1 border border-slate-200'
        );

        $('.dataTables_filter input').addClass(
            'bg-white text-slate-700 text-sm rounded-lg px-3 py-1 border border-slate-200 ml-2'
        );

        $('.dataTables_paginate').addClass('flex items-center gap-1');

        $('.dataTables_paginate .paginate_button').addClass(
            'px-3 py-1 text-sm text-slate-600 rounded-lg hover:bg-slate-100'
        );

    });
    </script>


    <script>
        function openDeptModal() {
            document.getElementById('deptModal').classList.remove('hidden');
        }
        function closeDeptModal() {
            document.getElementById('deptModal').classList.add('hidden');
        }
    </script>


</x-app-layout>