<x-app-layout>

<div class="mx-auto max-w-3xl space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-6">
            <h1 class="text-2xl font-bold text-slate-900">
                Create Admin User
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Add a new SACDEV administrator and assign their role and cluster access.
            </p>
        </div>
    </div>

    {{-- FORM --}}
    <form action="{{ route('admin.users.store') }}" method="POST"
          class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        @csrf

        <div class="px-6 py-6 space-y-6">

            {{-- NAME --}}
            <div>
                <label class="text-sm font-semibold text-slate-700">
                    Full Name
                </label>
                <input type="text" name="name"
                       value="{{ old('name') }}"
                       class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-100">

                @error('name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="text-sm font-semibold text-slate-700">
                    Email Address
                </label>
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-100">

                @error('email')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="text-sm font-semibold text-slate-700">
                    Password
                </label>
                <input type="password" name="password"
                       class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-100">

                <p class="mt-1 text-xs text-slate-400">
                    Minimum 6 characters.
                </p>

                @error('password')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- ROLE --}}
            <div>
                <label class="text-sm font-semibold text-slate-700">
                    Assigned Role
                </label>

                <select name="role_id"
                        class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-100">

                    <option value="">Select Role</option>

                    @foreach($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->label }} ({{ $role->name }})
                        </option>
                    @endforeach

                </select>

                @error('role_id')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- CLUSTERS --}}
            <div>
                <label class="text-sm font-semibold text-slate-700">
                    Assigned Clusters
                </label>

                <p class="text-xs text-slate-400 mt-1">
                    Select which clusters this admin can approve.
                </p>

                <div class="mt-3 grid grid-cols-2 md:grid-cols-3 gap-2">

                    @foreach($clusters as $cluster)
                        <label class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm hover:bg-slate-50">
                            <input type="checkbox"
                                   name="clusters[]"
                                   value="{{ $cluster->id }}"
                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                   {{ in_array($cluster->id, old('clusters', [])) ? 'checked' : '' }}>

                            <span>{{ $cluster->name }}</span>
                        </label>
                    @endforeach

                </div>

                @error('clusters')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- COA SETTINGS --}}
            <div class="space-y-3">

                <label class="text-sm font-semibold text-slate-700">
                    COA Configuration
                </label>

                <p class="text-xs text-slate-400">
                    Assign this admin as a COA officer and optionally set as default fallback.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                    {{-- COA OFFICER --}}
                    <label class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 hover:bg-slate-50">
                        <div>
                            <div class="text-sm font-medium text-slate-800">
                                COA Officer
                            </div>
                            <div class="text-xs text-slate-500">
                                Can approve financial-related documents
                            </div>
                        </div>

                        <input type="checkbox"
                            name="is_coa_officer"
                            value="1"
                            {{ old('is_coa_officer') ? 'checked' : '' }}
                            class="rounded border-slate-300 text-purple-600 focus:ring-purple-500">
                    </label>

                    {{-- DEFAULT COA --}}
                    <label class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 hover:bg-slate-50">
                        <div>
                            <div class="text-sm font-medium text-slate-800">
                                Default COA
                            </div>
                            <div class="text-xs text-slate-500">
                                Used when no COA is assigned to a project
                            </div>
                        </div>

                        <input type="checkbox"
                            name="is_default_coa"
                            value="1"
                            {{ old('is_default_coa') ? 'checked' : '' }}
                            class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                    </label>

                </div>

                {{-- ERROR --}}
                @error('is_default_coa')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror

            </div>





        </div>

        {{-- ACTION BAR --}}
        <div class="border-t border-slate-200 px-6 py-4 flex justify-end gap-3 bg-slate-50">

            <a href="{{ route('admin.users.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100">
                Cancel
            </a>

            <button type="submit"
                    class="rounded-lg bg-blue-900 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-800">
                Create User
            </button>

        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const coa = document.querySelector('input[name="is_coa_officer"]');
    const def = document.querySelector('input[name="is_default_coa"]');

    function sync() {
        if (!coa.checked) {
            def.checked = false;
            def.disabled = true;
        } else {
            def.disabled = false;
        }
    }

    coa.addEventListener('change', sync);
    sync();
});
</script>
</x-app-layout>