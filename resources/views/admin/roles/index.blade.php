<x-app-layout>


<div class="space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">
                    Roles & Permissions
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Manage system roles and assign permissions for SACDEV staff access control.
                </p>
            </div>

            <a href="{{ route('admin.roles.create') }}"
               class="inline-flex items-center rounded-xl bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-800">
                Create Role
            </a>

        </div>
    </div>


    {{-- ROLE LIST --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

        @forelse($roles as $role)
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5 flex flex-col justify-between">

                {{-- ROLE HEADER --}}
                <div>
                    <div class="flex items-center justify-between">

                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">
                                {{ $role->label }}
                            </h2>

                            <p class="text-xs text-slate-500 mt-1">
                                {{ $role->name }}
                            </p>
                        </div>

                        @if($role->is_default)
                            <span class="text-[10px] px-2 py-1 rounded-full bg-amber-100 text-amber-700 font-semibold">
                                Default
                            </span>
                        @endif

                    </div>

                    {{-- DESCRIPTION (optional safe) --}}
                    @if($role->description)
                        <p class="text-sm text-slate-500 mt-3">
                            {{ $role->description }}
                        </p>
                    @endif
                </div>

                {{-- PERMISSIONS --}}
                <div class="mt-4">

                    <p class="text-xs font-semibold text-slate-500 uppercase mb-2">
                        Permissions
                    </p>

                    @if($role->permissions->count())
                        <div class="flex flex-wrap gap-2">

                            @foreach($role->permissions as $permission)
                                <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 border border-blue-100">
                                    {{ $permission->label }}
                                </span>
                            @endforeach

                        </div>
                    @else
                        <p class="text-xs text-slate-400">
                            No permissions assigned.
                        </p>
                    @endif

                </div>

                {{-- ACTIONS --}}
                <div class="mt-5 flex justify-end gap-2">

                    <a href="{{ route('admin.roles.edit', $role) }}"
                       class="inline-flex rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Edit
                    </a>

                    <form action="{{ route('admin.roles.destroy', $role) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this role?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="inline-flex rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                            Delete
                        </button>
                    </form>

                </div>

            </div>
        @empty

            <div class="col-span-full text-center py-10">
                <p class="text-sm font-medium text-slate-700">
                    No roles found.
                </p>
                <p class="text-sm text-slate-500 mt-1">
                    Create a role to start assigning permissions.
                </p>
            </div>

        @endforelse

    </div>

</div>
</x-app-layout>
