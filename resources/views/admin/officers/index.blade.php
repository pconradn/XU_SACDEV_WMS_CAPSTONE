<x-app-layout>
    
<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    @php
        $organizationId = request('organization_id');
        $backUrl = $organizationId ? route('admin.orgs_by_sy.show', $organizationId) : route('admin.orgs_by_sy.index');

        $totalOfficers = $officers->count();
        $suspendedCount = $officers->where('is_suspended', true)->count();
        $probationCount = $officers->where('is_under_probation', true)->where('is_suspended', false)->count();
        $majorCount = $officers->where('is_major_officer', true)->count();
        $projectHeadCount = $officers->where('is_project_head', true)->count();
    @endphp

    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div>
            <div class="text-xs uppercase tracking-[0.18em] text-slate-400 font-semibold">
                Admin Monitoring
            </div>
            <h1 class="mt-1 text-2xl font-semibold text-slate-900">
                Officers Directory
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Review officer academic standing, major roles, project head assignments, and suspension controls.
            </p>
        </div>

        <a href="{{ $backUrl }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
            ← Back to Organization Hub
        </a>
    </div>

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="flex flex-wrap gap-3">

        <div class="px-3 py-2 rounded-lg border border-slate-200 bg-white flex items-center gap-2">
            <span class="text-[11px] text-slate-500 uppercase">Total</span>
            <span class="text-sm font-semibold text-slate-900">{{ $totalOfficers }}</span>
        </div>

        <div class="px-3 py-2 rounded-lg border border-slate-200 bg-white flex items-center gap-2">
            <span class="text-[11px] text-slate-500 uppercase">Major</span>
            <span class="text-sm font-semibold text-violet-700">{{ $majorCount }}</span>
        </div>

        <div class="px-3 py-2 rounded-lg border border-slate-200 bg-white flex items-center gap-2">
            <span class="text-[11px] text-slate-500 uppercase">Project Heads</span>
            <span class="text-sm font-semibold text-sky-700">{{ $projectHeadCount }}</span>
        </div>

        <div class="px-3 py-2 rounded-lg border border-amber-200 bg-amber-50 flex items-center gap-2">
            <span class="text-[11px] text-amber-600 uppercase">Probation</span>
            <span class="text-sm font-semibold text-amber-700">{{ $probationCount }}</span>
        </div>

        <div class="px-3 py-2 rounded-lg border border-red-200 bg-red-50 flex items-center gap-2">
            <span class="text-[11px] text-red-600 uppercase">Suspended</span>
            <span class="text-sm font-semibold text-red-700">{{ $suspendedCount }}</span>
        </div>

    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                <div>
                    <div class="text-sm font-semibold text-slate-900">Officer Monitoring Table</div>
                    <div class="text-xs text-slate-500">
                        Academic monitoring and administrative suspension controls for the selected organization and school year.
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto max-h-[560px] overflow-y-auto">
            <table class="min-w-full text-sm">
                <thead class="sticky top-0 z-10 bg-white border-b border-slate-200">
                    <tr class="text-left text-xs uppercase tracking-wide text-slate-500">
                        <th class="px-4 py-3 font-semibold">Officer</th>
                        <th class="px-4 py-3 font-semibold">Role Tags</th>
                        <th class="px-4 py-3 font-semibold">Previous QPI</th>
                        <th class="px-4 py-3 font-semibold">Current QPI</th>
                        <th class="px-4 py-3 font-semibold">Standing</th>
                        <th class="px-4 py-3 font-semibold text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($officers as $o)
                        @php
                            $rowClass = 'bg-white hover:bg-slate-50';
                            if ($o->is_suspended) {
                                $rowClass = 'bg-red-50 hover:bg-red-100/60';
                            } elseif ($o->is_under_probation) {
                                $rowClass = 'bg-amber-50 hover:bg-amber-100/60';
                            }
                        @endphp

                        <tr class="{{ $rowClass }} transition">
                            <td class="px-4 py-4 align-top">
                                <div class="font-semibold text-slate-900">
                                    {{ $o->full_name }}
                                </div>

                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $o->email ?: 'No email provided' }}
                                </div>

                                <div class="mt-2 space-y-1 text-xs text-slate-500">
                                    <div>
                                        <span class="text-slate-400">Organization:</span>
                                        <span class="text-slate-600">{{ $o->organization->name ?? '-' }}</span>
                                    </div>
                                    @if(!empty($o->position))
                                        <div>
                                            <span class="text-slate-400">Position:</span>
                                            <span class="text-slate-600">{{ $o->position }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($o->student_id_number))
                                        <div>
                                            <span class="text-slate-400">Student ID:</span>
                                            <span class="text-slate-600">{{ $o->student_id_number }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-4 align-top">
                                <div class="flex flex-wrap gap-2">
                                    @if($o->is_major_officer)
                                        <span class="inline-flex items-center rounded-full bg-violet-100 px-2.5 py-1 text-xs font-medium text-violet-700">
                                            Major Officer
                                        </span>
                                    @endif

                                    @if($o->is_project_head)
                                        <span class="inline-flex items-center rounded-full bg-sky-100 px-2.5 py-1 text-xs font-medium text-sky-700">
                                            Project Head
                                        </span>
                                    @endif

                                    @if(!$o->is_major_officer && !$o->is_project_head)
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                            Standard Officer
                                        </span>
                                    @endif
                                </div>

                                @if(optional($o->membership)->role)
                                    <div class="mt-2 text-xs text-slate-500">
                                        Membership Role:
                                        <span class="font-medium text-slate-700">
                                            {{ str_replace('_', ' ', optional($o->membership)->role) }}
                                        </span>
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-4 align-top">
                                <div class="min-w-[180px] space-y-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-3">
                                    <div class="flex items-center justify-between gap-3 text-xs">
                                        <span class="text-slate-500">Previous 1st Sem</span>
                                        <span class="font-semibold text-slate-800">{{ $o->prev_first_sem_qpi ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 text-xs">
                                        <span class="text-slate-500">Previous 2nd Sem</span>
                                        <span class="font-semibold text-slate-800">{{ $o->prev_second_sem_qpi ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 text-xs">
                                        <span class="text-slate-500">Previous Intersession</span>
                                        <span class="font-semibold text-slate-800">{{ $o->prev_intersession_qpi ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 align-top">
                                <div class="min-w-[180px] space-y-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-3">
                                    <div class="flex items-center justify-between gap-3 text-xs">
                                        <span class="text-slate-500">Current 1st Sem</span>
                                        <span class="font-semibold text-slate-800">{{ $o->current_first_sem_qpi ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 text-xs">
                                        <span class="text-slate-500">Current 2nd Sem</span>
                                        <span class="font-semibold text-slate-800">{{ $o->current_second_sem_qpi ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-4 align-top">
                                <div class="flex flex-col gap-2">
                                    @if($o->is_suspended)
                                        <span class="inline-flex w-fit items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">
                                            Suspended
                                        </span>
                                        <div class="text-xs text-red-600">
                                            Officer is currently restricted and requires admin override.
                                        </div>
                                    @elseif($o->is_under_probation)
                                        <span class="inline-flex w-fit items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">
                                            Under Probation
                                        </span>
                                        <div class="text-xs text-amber-600">
                                            Academic standing requires monitoring.
                                        </div>
                                    @else
                                        <span class="inline-flex w-fit items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                            Good Standing
                                        </span>
                                        <div class="text-xs text-emerald-600">
                                            No active disciplinary academic flag.
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-4 align-top">
                                <div class="flex flex-col items-end gap-2">
                                    @if($o->is_suspended)
                                        <form method="POST" action="{{ route('admin.officers.override-suspension', $o) }}">
                                            @csrf
                                            @method('PUT')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-2 text-xs font-medium text-white hover:bg-blue-700">
                                                Override Suspension
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('sacdev.officers.suspend', $o) }}"
                                              onsubmit="return confirm('Mark this officer as suspended?');">
                                            @csrf
                                            @method('PUT')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center rounded-lg bg-red-600 px-3 py-2 text-xs font-medium text-white hover:bg-red-700">
                                                Mark Suspended
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="text-sm font-medium text-slate-500">No officers found.</div>
                                <div class="mt-1 text-xs text-slate-400">
                                    There are no officers available for the selected organization and school year.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</x-app-layout>