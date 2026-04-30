<x-app-layout>

@php
    $roleRowsCollection = collect($roleRows ?? []);
    $projectRowsCollection = collect($projectRows ?? []);

    $totalRoleRows = $roleRowsCollection->count();
    $activatedRoleRows = $roleRowsCollection->where('activated', true)->count();
    $pendingRoleRows = $totalRoleRows - $activatedRoleRows;

    $totalProjectRows = $projectRowsCollection->count();
    $activatedProjectRows = $projectRowsCollection->where('activated', true)->count();
    $pendingProjectRows = $totalProjectRows - $activatedProjectRows;

    $totalAccounts = $totalRoleRows + $totalProjectRows;
    $totalActivated = $activatedRoleRows + $activatedProjectRows;
    $totalPending = $pendingRoleRows + $pendingProjectRows;
@endphp

<div class="min-h-screen bg-slate-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li>
                    <a href="{{ route('org.organization-info.show') }}"
                       class="font-medium text-slate-600 hover:text-slate-900 transition">
                        Organization
                    </a>
                </li>

                <li class="text-slate-300">/</li>

                <li class="font-medium text-indigo-700">
                    Activation Status
                </li>
            </ol>
        </nav>

        <div class="rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-blue-50 shadow-sm overflow-hidden">
            <div class="p-6 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200 shadow-sm">
                        <i data-lucide="user-check" class="w-7 h-7"></i>
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                <i data-lucide="badge-check" class="w-3 h-3"></i>
                                Account Activation
                            </span>

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                <i data-lucide="calendar-range" class="w-3 h-3"></i>
                                Encode SY ID: {{ $syId }}
                            </span>
                        </div>

                        <h1 class="text-2xl font-semibold text-slate-900 leading-tight">
                            Activation Status
                        </h1>

                        <p class="mt-1 text-sm leading-6 text-slate-600 max-w-3xl">
                            Track whether key organization users have logged in and changed their temporary password.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                        <div class="text-[11px] font-medium text-slate-500">
                            Total
                        </div>
                        <div class="mt-1 text-lg font-semibold text-slate-900">
                            {{ $totalAccounts }}
                        </div>
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 shadow-sm">
                        <div class="text-[11px] font-medium text-emerald-700">
                            Activated
                        </div>
                        <div class="mt-1 text-lg font-semibold text-emerald-800">
                            {{ $totalActivated }}
                        </div>
                    </div>

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 shadow-sm">
                        <div class="text-[11px] font-medium text-amber-700">
                            Pending
                        </div>
                        <div class="mt-1 text-lg font-semibold text-amber-800">
                            {{ $totalPending }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                    <i data-lucide="info" class="w-4 h-4 text-indigo-600"></i>
                    What does activated mean?
                </div>

                <div class="mt-1 text-xs text-slate-500">
                    A user is considered activated when they have logged in and changed their temporary password.
                </div>
            </div>

            <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                            <i data-lucide="key-round" class="w-4 h-4"></i>
                        </div>

                        <div>
                            <div class="text-sm font-semibold text-slate-900">
                                Temporary accounts
                            </div>

                            <div class="mt-1 text-xs leading-5 text-slate-600">
                                Some users may receive temporary login credentials when assigned to organization roles.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        </div>

                        <div>
                            <div class="text-sm font-semibold text-slate-900">
                                Activated users
                            </div>

                            <div class="mt-1 text-xs leading-5 text-slate-600">
                                Activated users have already completed their first-login password change.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                            <i data-lucide="clock-alert" class="w-4 h-4"></i>
                        </div>

                        <div>
                            <div class="text-sm font-semibold text-slate-900">
                                Pending activation
                            </div>

                            <div class="mt-1 text-xs leading-5 text-slate-600">
                                Pending users may need to log in first before they can fully participate in workflows.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <i data-lucide="users-round" class="w-4 h-4 text-indigo-600"></i>
                                Treasurer, Finance Officer & Moderator
                            </div>

                            <div class="mt-1 text-xs text-slate-500">
                                Activation status for key organization-level roles.
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 text-[11px]">
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 font-semibold text-emerald-700">
                                <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                {{ $activatedRoleRows }} active
                            </span>

                            <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 font-semibold text-amber-700">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                {{ $pendingRoleRows }} pending
                            </span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="max-h-[430px] overflow-y-auto">
                        <table class="min-w-full text-sm">
                            <thead class="sticky top-0 z-10 bg-white border-b border-slate-200">
                                <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <th class="px-5 py-3">Role</th>
                                    <th class="px-5 py-3">User</th>
                                    <th class="px-5 py-3">Email</th>
                                    <th class="px-5 py-3">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @forelse($roleRows as $r)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-5 py-4 align-top">
                                            <span class="inline-flex items-center gap-1.5 rounded-full border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                                <i data-lucide="shield-user" class="w-3 h-3"></i>
                                                {{ $r['label'] }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4 align-top">
                                            <div class="font-semibold text-slate-900">
                                                {{ $r['name'] }}
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 align-top">
                                            <div class="text-xs text-slate-500">
                                                {{ $r['email'] }}
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 align-top">
                                            @if($r['activated'])
                                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                    <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                                    Activated
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                    <i data-lucide="clock-alert" class="w-3 h-3"></i>
                                                    Not Activated
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-12">
                                            <div class="text-center">
                                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-400">
                                                    <i data-lucide="users-round" class="w-6 h-6"></i>
                                                </div>

                                                <div class="mt-3 text-sm font-semibold text-slate-800">
                                                    No treasurer, finance officer, or moderator assigned yet
                                                </div>

                                                <div class="mt-1 text-xs text-slate-500">
                                                    Assigned role accounts will appear here.
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <i data-lucide="folder-kanban" class="w-4 h-4 text-indigo-600"></i>
                                Project Heads
                            </div>

                            <div class="mt-1 text-xs text-slate-500">
                                Activation status for users assigned to manage project workflows.
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 text-[11px]">
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 font-semibold text-emerald-700">
                                <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                {{ $activatedProjectRows }} active
                            </span>

                            <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 font-semibold text-amber-700">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                {{ $pendingProjectRows }} pending
                            </span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="max-h-[430px] overflow-y-auto">
                        <table class="min-w-full text-sm">
                            <thead class="sticky top-0 z-10 bg-white border-b border-slate-200">
                                <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    <th class="px-5 py-3">Project</th>
                                    <th class="px-5 py-3">User</th>
                                    <th class="px-5 py-3">Email</th>
                                    <th class="px-5 py-3">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                @forelse($projectRows as $p)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="px-5 py-4 align-top">
                                            <div class="max-w-[320px] font-semibold text-slate-900 truncate">
                                                {{ $p['label'] }}
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 align-top">
                                            <div class="font-semibold text-slate-900">
                                                {{ $p['name'] }}
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 align-top">
                                            <div class="text-xs text-slate-500">
                                                {{ $p['email'] }}
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 align-top">
                                            @if($p['activated'])
                                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                    <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                                    Activated
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                    <i data-lucide="clock-alert" class="w-3 h-3"></i>
                                                    Not Activated
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-12">
                                            <div class="text-center">
                                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-400">
                                                    <i data-lucide="folder-kanban" class="w-6 h-6"></i>
                                                </div>

                                                <div class="mt-3 text-sm font-semibold text-slate-800">
                                                    No project heads assigned yet
                                                </div>

                                                <div class="mt-1 text-xs text-slate-500">
                                                    Assigned project heads will appear here.
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

</x-app-layout>