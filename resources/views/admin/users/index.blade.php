<x-app-layout>
    <style>
/* remove default borders */
.dataTables_wrapper .dataTables_filter input {
    border-radius: 0.75rem;
    border: 1px solid #cbd5f5;
    padding: 6px 10px;
    font-size: 14px;
}

/* pagination buttons */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 0.5rem !important;
    border: 1px solid #e2e8f0 !important;
    padding: 4px 10px !important;
    margin: 0 2px;
    background: white !important;
    color: #334155 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #0f172a !important;
    color: white !important;
    border-color: #0f172a !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f1f5f9 !important;
}

/* remove weird table borders */
table.dataTable.no-footer {
    border-bottom: none;
}

/* header fix */
table.dataTable thead th {
    border-bottom: 1px solid #e2e8f0;
}

/* row hover */
table.dataTable tbody tr:hover {
    background-color: #f8fafc;
}
table.dataTable {
    border-collapse: separate !important;
    border-spacing: 0;
}

table.dataTable tbody tr {
    border-bottom: 1px solid #e2e8f0;
}
table.dataTable tbody td {
    border-bottom: 1px solid #e2e8f0;
}
</style>

<div class="space-y-6">

    {{-- PAGE HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
                    Admin Users
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Manage SACDEV administrator accounts, assigned roles, and cluster coverage.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex items-center rounded-xl bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-800">
                    Create User
                </a>
            </div>
        </div>
    </div>



    {{-- TABLE CARD --}}
    <div class="overflow-x-auto">
        <table id="usersTable" class="min-w-full">
            <thead>
                <tr class="bg-slate-50">
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">System Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Assigned Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">COA Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Clusters</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-500">Actions</th>
                </tr>
            </thead>

            <tbody class="bg-white">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50/70 border-b border-slate-200">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-900">{{ $user->name }}</div>
                        <div class="text-sm text-slate-500">{{ $user->email }}</div>
                    </td>

                    <td class="px-6 py-4">
                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                            {{ $user->system_role ?? '—' }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        @if($user->role)
                            <div class="font-medium text-slate-800">{{ $user->role->label }}</div>
                            <div class="text-xs text-slate-500">{{ $user->role->name }}</div>
                        @else
                            <span class="text-sm text-slate-400">No role assigned</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        @if($user->is_coa_officer)
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex rounded-full bg-purple-50 px-2.5 py-1 text-xs font-semibold text-purple-700 border border-purple-100">
                                    COA Officer
                                </span>

                                @if($user->is_default_coa)
                                    <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 border border-amber-100">
                                        Default
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-sm text-slate-400">—</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        @if($user->clusters->count())
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->clusters as $cluster)
                                    <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 border border-blue-100">
                                        {{ $cluster->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-sm text-slate-400">No clusters assigned</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                            class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                Edit
                            </a>

                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>



<script>
$(document).ready(function () {
    $('#usersTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        language: {
            search: "",
            searchPlaceholder: "Search users...",
        },
        dom:
            "<'flex items-center justify-between mb-4'<'text-sm text-slate-600'i><'flex items-center gap-2'f>>" +
            "<'overflow-x-auto't>" +
            "<'flex items-center justify-between mt-4'<'text-sm text-slate-500'i><'flex'p>>"
    });
});
</script>

</x-app-layout>