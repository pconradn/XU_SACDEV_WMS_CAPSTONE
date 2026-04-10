<x-app-layout>

<style>
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    font-size: 0.75rem;
    color: #475569;
}
table.dataTable.no-footer { border-bottom: none !important; }

.dataTables_filter input,
.dataTables_length select {
    border: 1px solid #e2e8f0 !important;
    border-radius: 0.75rem !important;
    padding: 6px 10px !important;
}

.dataTables_paginate .paginate_button {
    border: none !important;
    background: transparent !important;
    margin: 0 2px;
}
.dataTables_paginate .paginate_button.current {
    background: #2563eb !important;
    color: white !important;
    border-radius: 0.5rem;
}
.dataTables_paginate .paginate_button:hover {
    background: #f1f5f9 !important;
    border-radius: 0.5rem;
}

.dataTables_length select {
    appearance: none;
    padding-right: 2rem !important;
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 12px;
    background-image: url("data:image/svg+xml;utf8,<svg fill='%2364758b' height='20' viewBox='0 0 20 20' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M5 7l5 5 5-5z'/></svg>");
}
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Organization Members</h2>
            <p class="text-xs text-slate-500">
                Manage members and assign them to departments for project head eligibility
            </p>
        </div>

        @if($isPresident)
        <button onclick="openAddModal()"
            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-xl
                   bg-blue-600 text-white hover:bg-blue-700 transition shadow-sm">
            <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
            Add Member
        </button>
        @endif
    </div>

    {{-- DEPARTMENTS --}}
    @if($isPresident)
    <div class="rounded-2xl border border-indigo-200 bg-gradient-to-b from-indigo-50 to-white p-4 shadow-sm space-y-4">

        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-slate-900">Departments</div>
                <div class="text-xs text-slate-500">
                    Assign members into departments so they can be selected as project heads
                </div>
            </div>

            <button onclick="openDeptModal()"
                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs rounded-lg
                       bg-indigo-600 text-white hover:bg-indigo-700 transition">
                <i data-lucide="plus" class="w-3 h-3"></i>
                Add
            </button>
        </div>

        <div class="flex flex-wrap gap-2">
            @foreach($departments as $dept)
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white border border-indigo-200 text-xs shadow-sm">

                <span class="font-medium text-slate-700">{{ $dept->name }}</span>

                <button onclick="editDept({{ $dept->id }}, '{{ $dept->name }}')"
                    class="text-blue-600 hover:underline text-[11px]">
                    Edit
                </button>

                <form method="POST" action="{{ route('org.departments.destroy', $dept->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="text-rose-600 hover:underline text-[11px]">
                        Archive
                    </button>
                </form>

            </div>
            @endforeach
        </div>

    </div>
    @endif

    {{-- TABLE --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white">
            <div class="text-sm font-semibold text-slate-900">Members Directory</div>
            <div class="text-xs text-slate-500">All registered members for current school year</div>
        </div>

        <div class="overflow-x-auto">

            <table id="membersTable" class="min-w-full text-xs text-slate-700">

                <thead class="bg-slate-100 text-[10px] uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Student ID</th>
                        <th class="px-4 py-3 text-left">Course</th>
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

                        <td class="px-4 py-3 font-medium text-slate-900">
                            {{ $member->full_name }}
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            {{ $member->student_id_number ?? '-' }}
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            {{ $member->course_and_year ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            @if($member->latest_qpi)
                            <span class="px-2 py-1 text-[10px] rounded bg-blue-50 text-blue-700">
                                {{ number_format($member->latest_qpi, 2) }}
                            </span>
                            @else
                            <span class="text-slate-400">N/A</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            <div>{{ $member->email ?? '-' }}</div>
                            <div class="text-[10px] text-slate-400">{{ $member->mobile_number ?? '' }}</div>
                        </td>

                        @if($isPresident)
                        <td class="px-4 py-3">

                            <form method="POST"
                                action="{{ route('org.members.assign-department', $member->id) }}">
                                @csrf
                                @method('PUT')

                                <select name="department_id"
                                    onchange="this.form.submit()"
                                    class="text-[11px] rounded-lg border-slate-200">

                                    <option value="">No Department</option>

                                    @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}"
                                        {{ $member->department_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                    @endforeach

                                </select>
                            </form>

                        </td>

                        <td class="px-4 py-3 text-right">

                            <div class="flex justify-end gap-2">

                                <button onclick="openEditModal({{ $member->id }})"
                                    class="px-3 py-1 text-[11px] rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100">
                                    Edit
                                </button>

                                <form method="POST"
                                    action="{{ route('org.organization-members.destroy', $member->id) }}"
                                    onsubmit="return confirm('Remove this member?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="px-3 py-1 text-[11px] rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100">
                                        Remove
                                    </button>

                                </form>

                            </div>

                        </td>
                        @endif

                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-slate-400 text-xs">
                            No members found
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>

</div>



{{-- ================= DEPARTMENT MODAL ================= --}}
<div id="deptModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl w-full max-w-sm shadow-xl">

        <form method="POST" action="{{ route('org.departments.store') }}">
            @csrf

            <div class="p-5 space-y-3">
                <h2 class="text-sm font-semibold text-slate-900">New Department</h2>

                <input name="name" required
                    placeholder="Department name"
                    class="w-full rounded-xl border-slate-200 text-sm focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="px-5 py-3 border-t flex justify-end gap-2">
                <button type="button" onclick="closeDeptModal()"
                    class="text-xs text-slate-600 hover:text-slate-800">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 text-xs font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    Save
                </button>
            </div>

        </form>

    </div>
</div>


{{-- ================= ADD MEMBER MODAL ================= --}}
<div id="addModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-xl overflow-hidden">

        <div class="px-5 py-3 border-b">
            <h2 class="text-sm font-semibold text-slate-900">Add Member</h2>
        </div>

        <form method="POST" action="{{ route('org.organization-members.store') }}">
            @csrf

            <div class="p-5 space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                    <input name="first_name" required placeholder="First Name"
                        class="rounded-xl border-slate-200 text-sm">

                    <input name="last_name" required placeholder="Last Name"
                        class="rounded-xl border-slate-200 text-sm">

                    <input name="middle_initial" placeholder="MI"
                        class="rounded-xl border-slate-200 text-sm">
                </div>

                <input type="number" step="0.01" name="latest_qpi"
                    placeholder="Latest QPI"
                    class="w-full rounded-xl border-slate-200 text-sm">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                    <input name="student_id_number" placeholder="Student ID"
                        class="rounded-xl border-slate-200 text-sm">

                    <input name="course_and_year" placeholder="Course & Year"
                        class="rounded-xl border-slate-200 text-sm">

                    <input name="email" placeholder="Email"
                        class="rounded-xl border-slate-200 text-sm">

                    <input name="mobile_number" placeholder="Mobile"
                        class="rounded-xl border-slate-200 text-sm">
                </div>

                <select name="department_id"
                    class="w-full rounded-xl border-slate-200 text-sm">

                    <option value="">No Department</option>

                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>

            </div>

            <div class="px-5 py-3 border-t flex justify-end gap-2">

                <button type="button" onclick="closeAddModal()"
                    class="text-xs text-slate-600">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 text-xs font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Save
                </button>

            </div>

        </form>

    </div>
</div>


{{-- ================= EDIT MODAL ================= --}}
<div id="editModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-xl overflow-hidden">

        <div class="px-5 py-3 border-b">
            <h2 class="text-sm font-semibold text-slate-900">Edit Member</h2>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="p-5 space-y-4">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                    <input id="edit_first_name" name="first_name" required
                        class="rounded-xl border-slate-200 text-sm">

                    <input id="edit_last_name" name="last_name" required
                        class="rounded-xl border-slate-200 text-sm">

                    <input id="edit_mi" name="middle_initial"
                        class="rounded-xl border-slate-200 text-sm">
                </div>

                <input id="edit_qpi" name="latest_qpi" type="number" step="0.01"
                    class="w-full rounded-xl border-slate-200 text-sm">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                    <input id="edit_sid" name="student_id_number"
                        class="rounded-xl border-slate-200 text-sm">

                    <input id="edit_course" name="course_and_year"
                        class="rounded-xl border-slate-200 text-sm">

                    <input id="edit_email" name="email"
                        class="rounded-xl border-slate-200 text-sm">

                    <input id="edit_mobile" name="mobile_number"
                        class="rounded-xl border-slate-200 text-sm">
                </div>

                <select id="edit_department_id" name="department_id"
                    class="w-full rounded-xl border-slate-200 text-sm">

                    <option value="">No Department</option>

                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>

            </div>

            <div class="px-5 py-3 border-t flex justify-end gap-2">

                <button type="button" onclick="closeEditModal()"
                    class="text-xs text-slate-600">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 text-xs font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                    Update
                </button>

            </div>

        </form>

    </div>
</div>

<div id="editDeptModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl w-full max-w-sm shadow-xl">

        <form id="editDeptForm" method="POST">
            @csrf
            @method('PUT')

            <div class="p-5 space-y-3">
                <h2 class="text-sm font-semibold text-slate-900">Edit Department</h2>

                <input id="edit_dept_name" name="name" required
                    class="w-full rounded-xl border-slate-200 text-sm focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="px-5 py-3 border-t flex justify-end gap-2">

                <button type="button" onclick="closeEditDeptModal()"
                    class="text-xs text-slate-600">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 text-xs font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    Update
                </button>

            </div>

        </form>

    </div>
</div>

<script>
function editDept(id, name){
    document.getElementById('edit_dept_name').value = name;
    document.getElementById('editDeptForm').action = `/org/departments/${id}`;
    document.getElementById('editDeptModal').classList.remove('hidden');
}

function closeEditDeptModal(){
    document.getElementById('editDeptModal').classList.add('hidden');
}
</script>

<script>
function openAddModal(){document.getElementById('addModal').classList.remove('hidden')}
function closeAddModal(){document.getElementById('addModal').classList.add('hidden')}
function openDeptModal(){document.getElementById('deptModal').classList.remove('hidden')}
function closeDeptModal(){document.getElementById('deptModal').classList.add('hidden')}
function closeEditModal(){document.getElementById('editModal').classList.add('hidden')}

function openEditModal(id){
    const members = @json($members);
    const m = members.find(x => x.id === id);
    if(!m) return;

    document.getElementById('edit_first_name').value = m.first_name ?? '';
    document.getElementById('edit_last_name').value = m.last_name ?? '';
    document.getElementById('edit_mi').value = m.middle_initial ?? '';
    document.getElementById('edit_qpi').value = m.latest_qpi ?? '';
    document.getElementById('edit_sid').value = m.student_id_number ?? '';
    document.getElementById('edit_course').value = m.course_and_year ?? '';
    document.getElementById('edit_email').value = m.email ?? '';
    document.getElementById('edit_mobile').value = m.mobile_number ?? '';
    document.getElementById('edit_department_id').value = m.department_id ?? '';

    document.getElementById('editForm').action = `/org/organization-members/${id}`;
    document.getElementById('editModal').classList.remove('hidden');
}
</script>

<script>
$(document).ready(function () {

    let options = {
        pageLength: 10,
        lengthMenu: [10,25,50],
        ordering: true,
        responsive: true,
        dom: `
            <"flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4"
                <"flex items-center gap-2 text-xs text-slate-600"l>
                <"flex items-center text-xs text-slate-600"f>
            >
            t
            <"flex flex-col md:flex-row md:items-center md:justify-between gap-3 mt-4"
                <"text-xs text-slate-500"i>
                <"flex items-center gap-1"p>
            >
        `
    };

    @if($isPresident)
    options.columnDefs = [{ orderable:false, targets:5 }];
    @endif

    let hasRows = $('#membersTable tbody tr').filter(function(){
        return $(this).find('td').length > 1;
    }).length > 0;

    if(hasRows){
        $('#membersTable').DataTable(options);
    }

});
</script>




</x-app-layout>