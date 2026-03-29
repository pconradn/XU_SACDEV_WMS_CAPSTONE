<x-app-layout>


<div class="mx-auto max-w-5xl space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-6">
            <h1 class="text-2xl font-bold text-slate-900">
                Create Role
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Define a role and assign permissions to control system access.
            </p>
        </div>
    </div>

    {{-- FORM --}}
    <form action="{{ route('admin.roles.store') }}" method="POST"
          class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        @csrf

        <div class="px-6 py-6 space-y-6">

            {{-- ROLE NAME --}}
            <div>
                <label class="text-sm font-semibold text-slate-700">
                    Role Name (System)
                </label>
                <input type="text" name="name"
                       value="{{ old('name') }}"
                       placeholder="e.g. sacdev_staff"
                       class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2 text-sm">

                <p class="mt-1 text-xs text-slate-400">
                    Used internally. Should be unique and lowercase.
                </p>

                @error('name')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- LABEL --}}
            <div>
                <label class="text-sm font-semibold text-slate-700">
                    Display Name
                </label>
                <input type="text" name="label"
                       value="{{ old('label') }}"
                       placeholder="e.g. SACDEV Staff"
                       class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2 text-sm">

                @error('label')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PERMISSIONS --}}
            <div>
                <label class="text-sm font-semibold text-slate-700">
                    Permissions
                </label>

                <p class="text-xs text-slate-400 mt-1">
                    Select what actions this role is allowed to perform.
                </p>

                @php
                    $grouped = $permissions->groupBy(function ($perm) {
                        return explode('.', $perm->code)[0];
                    });
                @endphp

                <div class="mt-4 space-y-4">

                    @foreach($grouped as $group => $perms)
                        <div class="rounded-xl border border-slate-200 p-4">

                            {{-- GROUP TITLE --}}
                            <div class="mb-3 flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-slate-800 capitalize">
                                    {{ str_replace('_', ' ', $group) }}
                                </h3>

                                <button type="button"
                                        onclick="toggleGroup('{{ $group }}')"
                                        class="text-xs text-blue-600 hover:underline">
                                    Toggle
                                </button>
                            </div>

                            {{-- PERMISSIONS --}}
                            <div id="group-{{ $group }}"
                                 class="grid grid-cols-2 md:grid-cols-3 gap-2">

                                @foreach($perms as $permission)
                                    <label class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm hover:bg-slate-50">

                                        <input type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->id }}"
                                               class="rounded border-slate-300 text-blue-600"
                                               {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>

                                        <span>{{ $permission->label }}</span>

                                    </label>
                                @endforeach

                            </div>

                        </div>
                    @endforeach

                </div>

                @error('permissions')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- ACTION BAR --}}
        <div class="border-t border-slate-200 px-6 py-4 flex justify-end gap-3 bg-slate-50">

            <a href="{{ route('admin.roles.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100">
                Cancel
            </a>

            <button type="submit"
                    class="rounded-lg bg-blue-900 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-800">
                Create Role
            </button>

        </div>

    </form>

</div>

{{-- SIMPLE JS --}}
<script>
function toggleGroup(group) {
    const el = document.getElementById('group-' + group);
    if (!el) return;

    el.style.display = el.style.display === 'none' ? 'grid' : 'none';
}
</script>

</x-app-layout>
