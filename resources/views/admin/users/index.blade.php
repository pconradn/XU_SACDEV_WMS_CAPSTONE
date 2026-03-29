<x-app-layout>

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
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-sm font-semibold text-slate-900">
                User Directory
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Review user role assignments and cluster mappings.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            System Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Assigned Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Clusters
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-4 align-top">
                                <div class="font-semibold text-slate-900">
                                    {{ $user->name }}
                                </div>
                                <div class="mt-1 text-sm text-slate-500">
                                    {{ $user->email }}
                                </div>
                            </td>

                            <td class="px-6 py-4 align-top">
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    {{ $user->system_role ?? '—' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 align-top">
                                @if($user->role)
                                    <div class="font-medium text-slate-800">
                                        {{ $user->role->label }}
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $user->role->name }}
                                    </div>
                                @else
                                    <span class="text-sm text-slate-400">No role assigned</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 align-top">
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

                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="inline-flex rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this user?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="inline-flex rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center">
                                <div class="text-sm font-medium text-slate-700">
                                    No admin users found.
                                </div>
                                <div class="mt-1 text-sm text-slate-500">
                                    Create your first SACDEV admin account to begin assigning roles and clusters.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($users, 'links'))
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
</x-app-layout>