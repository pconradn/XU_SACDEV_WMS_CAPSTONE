<x-app-layout>

@php
    $totalOfficers = collect($officers ?? [])->count();

    $suspendedCount = collect($officers ?? [])->filter(function ($o) {
        return optional($o->membership)->is_suspended;
    })->count();

    $probationCount = collect($officers ?? [])->filter(function ($o) {
        return optional($o->membership)->is_under_probation;
    })->count();

    $goodStandingCount = collect($officers ?? [])->filter(function ($o) {
        $membership = $o->membership ?? null;

        return $membership
            && !$membership->is_suspended
            && !$membership->is_under_probation;
    })->count();
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
                    Officers
                </li>
            </ol>
        </nav>

        <div class="rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-blue-50 shadow-sm overflow-hidden">
            <div class="p-6 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200 shadow-sm">
                        <i data-lucide="users-round" class="w-7 h-7"></i>
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                <i data-lucide="badge-check" class="w-3 h-3"></i>
                                Officer Directory
                            </span>

                            @if($myRole === 'president')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                    <i data-lucide="pencil-line" class="w-3 h-3"></i>
                                    QPI Editing Enabled
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                    <i data-lucide="eye" class="w-3 h-3"></i>
                                    View Only
                                </span>
                            @endif
                        </div>

                        <h1 class="text-2xl font-semibold text-slate-900 leading-tight">
                            Organization Officers
                        </h1>

                        <p class="mt-1 text-sm leading-6 text-slate-600 max-w-3xl">
                            View officer roles, academic QPI records, and eligibility standing for the current organization.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                        <div class="text-[11px] font-medium text-slate-500">Total</div>
                        <div class="mt-1 text-lg font-semibold text-slate-900">{{ $totalOfficers }}</div>
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 shadow-sm">
                        <div class="text-[11px] font-medium text-emerald-700">Good</div>
                        <div class="mt-1 text-lg font-semibold text-emerald-800">{{ $goodStandingCount }}</div>
                    </div>

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 shadow-sm">
                        <div class="text-[11px] font-medium text-amber-700">Probation</div>
                        <div class="mt-1 text-lg font-semibold text-amber-800">{{ $probationCount }}</div>
                    </div>

                    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 shadow-sm">
                        <div class="text-[11px] font-medium text-rose-700">Suspended</div>
                        <div class="mt-1 text-lg font-semibold text-rose-800">{{ $suspendedCount }}</div>
                    </div>
                </div>

            </div>
        </div>

        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm">
                <div class="flex items-start gap-2">
                    <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5 shrink-0"></i>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                            <i data-lucide="clipboard-list" class="w-4 h-4 text-indigo-600"></i>
                            Officer Academic Standing
                        </div>

                        <div class="mt-1 text-xs text-slate-500">
                            Tracks previous QPI, current QPI, and officer eligibility status.
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 text-[11px]">
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 font-semibold text-emerald-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Good Standing
                        </span>

                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 font-semibold text-amber-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            Probation
                        </span>

                        <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 font-semibold text-rose-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                            Suspended
                        </span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="max-h-[540px] overflow-y-auto">
                    <table id="officersTable" class="min-w-full text-sm">

                        <thead class="sticky top-0 z-10 bg-white border-b border-slate-200">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <th class="px-5 py-3">Officer</th>
                                <th class="px-5 py-3">Position</th>
                                <th class="px-5 py-3">Previous QPI</th>
                                <th class="px-5 py-3">Current QPI</th>
                                <th class="px-5 py-3">Standing</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($officers as $o)

                                @php
                                    $membership = $o->membership ?? null;

                                    $rowClass = 'hover:bg-slate-50';

                                    if ($membership) {
                                        if ($membership->is_suspended) {
                                            $rowClass = 'bg-rose-50/70 hover:bg-rose-100/70';
                                        } elseif ($membership->is_under_probation) {
                                            $rowClass = 'bg-amber-50/70 hover:bg-amber-100/70';
                                        }
                                    }
                                @endphp

                                <tr class="{{ $rowClass }} transition">

                                    <td class="px-5 py-4 align-top">
                                        <div class="flex items-start gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                                <i data-lucide="user-round" class="w-5 h-5"></i>
                                            </div>

                                            <div class="min-w-0">
                                                <div class="font-semibold text-slate-900 truncate">
                                                    {{ $o->full_name }}
                                                </div>

                                                <div class="mt-0.5 text-xs text-slate-500 truncate">
                                                    {{ $o->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 align-top">
                                        <span class="inline-flex max-w-full items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                            <span class="truncate">
                                                {{ $o->position ?? '-' }}
                                            </span>
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 align-top">
                                        <div class="w-44 space-y-1.5 text-xs">
                                            <div class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-2.5 py-1.5">
                                                <span class="text-slate-500">Prev 1st</span>
                                                <span class="font-semibold text-slate-800">{{ $o->prev_first_sem_qpi ?? '-' }}</span>
                                            </div>

                                            <div class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-2.5 py-1.5">
                                                <span class="text-slate-500">Prev 2nd</span>
                                                <span class="font-semibold text-slate-800">{{ $o->prev_second_sem_qpi ?? '-' }}</span>
                                            </div>

                                            <div class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-2.5 py-1.5">
                                                <span class="text-slate-500">Inter</span>
                                                <span class="font-semibold text-slate-800">{{ $o->prev_intersession_qpi ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 align-top">
                                        @if($myRole === 'president')

                                            <form method="POST"
                                                  action="{{ route('org.officers.update-qpi', $o) }}"
                                                  class="w-56 space-y-2">

                                                @csrf
                                                @method('PUT')

                                                <div class="space-y-2">
                                                    <label class="flex items-center justify-between gap-3">
                                                        <span class="w-20 text-[11px] font-medium text-slate-500">
                                                            Current 1st
                                                        </span>

                                                        <input type="number"
                                                               step="0.01"
                                                               min="0"
                                                               max="4"
                                                               name="current_first_sem_qpi"
                                                               value="{{ $o->current_first_sem_qpi }}"
                                                               class="w-24 rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs text-slate-700 shadow-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                                                    </label>

                                                    <label class="flex items-center justify-between gap-3">
                                                        <span class="w-20 text-[11px] font-medium text-slate-500">
                                                            Current 2nd
                                                        </span>

                                                        <input type="number"
                                                               step="0.01"
                                                               min="0"
                                                               max="4"
                                                               name="current_second_sem_qpi"
                                                               value="{{ $o->current_second_sem_qpi }}"
                                                               class="w-24 rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs text-slate-700 shadow-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100">
                                                    </label>
                                                </div>

                                                <button type="submit"
                                                        class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                                                    <i data-lucide="save" class="w-3 h-3"></i>
                                                    Save QPI
                                                </button>

                                            </form>

                                        @else

                                            <div class="w-44 space-y-1.5 text-xs">
                                                <div class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-2.5 py-1.5">
                                                    <span class="text-slate-500">Current 1st</span>
                                                    <span class="font-semibold text-slate-800">{{ $o->current_first_sem_qpi ?? '-' }}</span>
                                                </div>

                                                <div class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-2.5 py-1.5">
                                                    <span class="text-slate-500">Current 2nd</span>
                                                    <span class="font-semibold text-slate-800">{{ $o->current_second_sem_qpi ?? '-' }}</span>
                                                </div>
                                            </div>

                                        @endif
                                    </td>

                                    <td class="px-5 py-4 align-top">
                                        @if($membership)

                                            @if($membership->is_suspended)
                                                <span class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                                    <i data-lucide="ban" class="w-3 h-3"></i>
                                                    Suspended
                                                </span>

                                            @elseif($membership->is_under_probation)
                                                <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                                    <i data-lucide="triangle-alert" class="w-3 h-3"></i>
                                                    Under Probation
                                                </span>

                                            @else
                                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                                    <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                                    Good Standing
                                                </span>
                                            @endif

                                        @else
                                            <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-400">
                                                N/A
                                            </span>
                                        @endif
                                    </td>

                                </tr>

                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12">
                                        <div class="text-center">
                                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-400">
                                                <i data-lucide="users-round" class="w-6 h-6"></i>
                                            </div>

                                            <div class="mt-3 text-sm font-semibold text-slate-800">
                                                No officers found
                                            </div>

                                            <div class="mt-1 text-xs text-slate-500">
                                                Officer records will appear here once available.
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

</x-app-layout>