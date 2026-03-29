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
</x-app-layout>