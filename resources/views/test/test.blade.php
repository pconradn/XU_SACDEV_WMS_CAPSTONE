<x-app-layout>

    <style>
        .content-frame.soft {
            background: #ffffff !important;
        }
    </style>

    @php
        $kpis = [
            [
                'label' => 'Organizations',
                'value' => 42,
                'hint' => 'Active this school year',
                'icon' => 'building-2',
                'color' => 'indigo',
                'trend' => '+6 this month',
            ],
            [
                'label' => 'Pending Reviews',
                'value' => 18,
                'hint' => 'Documents needing action',
                'icon' => 'file-check-2',
                'color' => 'blue',
                'trend' => 'Needs attention',
            ],
            [
                'label' => 'Ready Registrations',
                'value' => 7,
                'hint' => 'Complete and awaiting approval',
                'icon' => 'clipboard-check',
                'color' => 'amber',
                'trend' => 'Review queue',
            ],
            [
                'label' => 'Upcoming Activities',
                'value' => 12,
                'hint' => 'Scheduled in the next 30 days',
                'icon' => 'calendar-days',
                'color' => 'emerald',
                'trend' => 'Calendar view',
            ],
        ];

        $registrationCases = [
            [
                'org' => 'Computer Studies Council',
                'acronym' => 'CSC',
                'status' => 'Ready for Approval',
                'requirements' => '5 / 5 complete',
                'school_year' => '2026-2027',
                'color' => 'emerald',
            ],
            [
                'org' => 'Nursing Student Body Organization',
                'acronym' => 'NSBO',
                'status' => 'Missing Moderator Profile',
                'requirements' => '4 / 5 complete',
                'school_year' => '2026-2027',
                'color' => 'amber',
            ],
            [
                'org' => 'Engineering Council',
                'acronym' => 'EC',
                'status' => 'Strategic Plan Returned',
                'requirements' => '3 / 5 complete',
                'school_year' => '2026-2027',
                'color' => 'rose',
            ],
        ];

        $approvalItems = [
            [
                'document' => 'Project Proposal',
                'project' => 'Digital Skills Bootcamp 2026',
                'org' => 'CSC',
                'submitted_by' => 'Juan Dela Cruz',
                'status' => 'Submitted to SACDEV',
                'phase' => 'Pre-Implementation',
                'color' => 'indigo',
            ],
            [
                'document' => 'Off-Campus Activity Form',
                'project' => 'Community Tech Outreach',
                'org' => 'EC',
                'submitted_by' => 'Maria Santos',
                'status' => 'Awaiting Review',
                'phase' => 'Off-Campus',
                'color' => 'purple',
            ],
            [
                'document' => 'Liquidation Report',
                'project' => 'Leadership Camp',
                'org' => 'NSBO',
                'submitted_by' => 'Carlo Reyes',
                'status' => 'Post-Implementation Review',
                'phase' => 'Post-Implementation',
                'color' => 'emerald',
            ],
            [
                'document' => 'Solicitation Application',
                'project' => 'Fundraising Fair',
                'org' => 'CSC',
                'submitted_by' => 'Ana Lim',
                'status' => 'Needs SACDEV Action',
                'phase' => 'Other Requirement',
                'color' => 'blue',
            ],
        ];

        $activationRows = [
            ['name' => 'Juan Dela Cruz', 'role' => 'President', 'org' => 'CSC', 'status' => 'Activated'],
            ['name' => 'Maria Santos', 'role' => 'Moderator', 'org' => 'EC', 'status' => 'Not Activated'],
            ['name' => 'Carlo Reyes', 'role' => 'Treasurer', 'org' => 'NSBO', 'status' => 'Activated'],
            ['name' => 'Ana Lim', 'role' => 'Project Head', 'org' => 'CSC', 'status' => 'Not Activated'],
        ];

        $calendarItems = [
            [
                'date' => 'May 05',
                'title' => 'Community Tech Outreach',
                'org' => 'Engineering Council',
                'type' => 'Off-campus activity',
                'color' => 'purple',
            ],
            [
                'date' => 'May 09',
                'title' => 'Digital Skills Bootcamp',
                'org' => 'Computer Studies Council',
                'type' => 'Workshop',
                'color' => 'indigo',
            ],
            [
                'date' => 'May 15',
                'title' => 'Leadership Camp',
                'org' => 'Nursing Student Body Organization',
                'type' => 'Post-activity reports due',
                'color' => 'emerald',
            ],
        ];
    @endphp

    <div class="min-h-screen bg-slate-50">

        <div class="px-3 sm:px-4 lg:px-5 pt-4 space-y-4">

            <div class="rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-slate-50 shadow-sm overflow-hidden">
                <div class="px-5 py-5 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                    <div class="flex items-start gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200 shadow-sm">
                            <i data-lucide="shield-check" class="w-7 h-7"></i>
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                    <i data-lucide="layout-dashboard" class="w-3 h-3"></i>
                                    SACDEV Panel
                                </span>

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                    <i data-lucide="activity" class="w-3 h-3"></i>
                                    Sample Template
                                </span>
                            </div>

                            <h1 class="text-2xl font-semibold text-slate-900 leading-tight">
                                Admin Workflow Dashboard
                            </h1>

                            <p class="mt-1 text-sm leading-6 text-slate-600 max-w-3xl">
                                Monitor organization registration, project approvals, activation status, and upcoming activities from one admin workspace.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <div class="rounded-2xl border border-white/70 bg-white/80 px-4 py-3 shadow-sm">
                            <div class="flex items-center gap-2 text-[11px] font-medium text-slate-500">
                                <i data-lucide="building-2" class="w-3.5 h-3.5 text-indigo-600"></i>
                                Organizations
                            </div>
                            <div class="mt-1 text-lg font-semibold text-slate-900">
                                42
                            </div>
                        </div>

                        <div class="rounded-2xl border border-white/70 bg-white/80 px-4 py-3 shadow-sm">
                            <div class="flex items-center gap-2 text-[11px] font-medium text-slate-500">
                                <i data-lucide="file-check-2" class="w-3.5 h-3.5 text-blue-600"></i>
                                Approvals
                            </div>
                            <div class="mt-1 text-lg font-semibold text-slate-900">
                                18
                            </div>
                        </div>

                        <div class="col-span-2 sm:col-span-1 rounded-2xl border border-white/70 bg-white/80 px-4 py-3 shadow-sm">
                            <div class="flex items-center gap-2 text-[11px] font-medium text-slate-500">
                                <i data-lucide="calendar-days" class="w-3.5 h-3.5 text-emerald-600"></i>
                                Activities
                            </div>
                            <div class="mt-1 text-lg font-semibold text-slate-900">
                                12
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="px-3 sm:px-4 lg:px-5 py-4 space-y-4">

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

                @foreach($kpis as $kpi)
                    <div class="rounded-2xl border border-{{ $kpi['color'] }}-200 bg-white shadow-sm overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-xs font-medium text-slate-500">
                                        {{ $kpi['label'] }}
                                    </div>

                                    <div class="mt-2 text-2xl font-semibold text-slate-900">
                                        {{ $kpi['value'] }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ $kpi['hint'] }}
                                    </div>
                                </div>

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-{{ $kpi['color'] }}-50 text-{{ $kpi['color'] }}-600">
                                    <i data-lucide="{{ $kpi['icon'] }}" class="w-5 h-5"></i>
                                </div>
                            </div>

                            <div class="mt-4 inline-flex items-center gap-1.5 rounded-full border border-{{ $kpi['color'] }}-200 bg-{{ $kpi['color'] }}-50 px-2.5 py-1 text-[11px] font-semibold text-{{ $kpi['color'] }}-700">
                                <i data-lucide="sparkles" class="w-3 h-3"></i>
                                {{ $kpi['trend'] }}
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-4">

                <div class="xl:col-span-3 space-y-4">

                    <div class="rounded-2xl border border-amber-200 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-amber-200 bg-gradient-to-r from-amber-50 to-white px-5 py-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                        <i data-lucide="building-2" class="w-4 h-4 text-amber-600"></i>
                                        Organization Registration Queue
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        Organizations with incomplete, returned, or ready-for-approval registration requirements.
                                    </div>
                                </div>

                                <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                    <i data-lucide="clipboard-check" class="w-3 h-3"></i>
                                    Re-registration
                                </span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-white border-b border-slate-200">
                                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        <th class="px-5 py-3">Organization</th>
                                        <th class="px-5 py-3">School Year</th>
                                        <th class="px-5 py-3">Requirements</th>
                                        <th class="px-5 py-3">Status</th>
                                        <th class="px-5 py-3 text-right">Action</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100">
                                    @foreach($registrationCases as $case)
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-{{ $case['color'] }}-50 text-{{ $case['color'] }}-600">
                                                        <i data-lucide="building-2" class="w-5 h-5"></i>
                                                    </div>

                                                    <div>
                                                        <div class="font-semibold text-slate-900">
                                                            {{ $case['org'] }}
                                                        </div>
                                                        <div class="text-xs text-slate-500">
                                                            {{ $case['acronym'] }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-5 py-4 text-xs text-slate-600">
                                                {{ $case['school_year'] }}
                                            </td>

                                            <td class="px-5 py-4">
                                                <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                                    {{ $case['requirements'] }}
                                                </span>
                                            </td>

                                            <td class="px-5 py-4">
                                                <span class="inline-flex items-center gap-1.5 rounded-full border border-{{ $case['color'] }}-200 bg-{{ $case['color'] }}-50 px-2.5 py-1 text-xs font-semibold text-{{ $case['color'] }}-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-{{ $case['color'] }}-500"></span>
                                                    {{ $case['status'] }}
                                                </span>
                                            </td>

                                            <td class="px-5 py-4 text-right">
                                                <button class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                                    Open
                                                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-indigo-200 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-indigo-200 bg-gradient-to-r from-indigo-50 to-white px-5 py-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                        <i data-lucide="file-check-2" class="w-4 h-4 text-indigo-600"></i>
                                        Project Document Approvals
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        Documents waiting for SACDEV review, return, approval, or workflow action.
                                    </div>
                                </div>

                                <span class="inline-flex items-center gap-1.5 rounded-full border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                    <i data-lucide="inbox" class="w-3 h-3"></i>
                                    Approval Queue
                                </span>
                            </div>
                        </div>

                        <div class="p-4 space-y-3">
                            @foreach($approvalItems as $item)
                                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:border-indigo-200 hover:bg-indigo-50/20 transition">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="flex items-start gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-{{ $item['color'] }}-50 text-{{ $item['color'] }}-600">
                                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                            </div>

                                            <div>
                                                <div class="text-sm font-semibold text-slate-900">
                                                    {{ $item['document'] }}
                                                </div>

                                                <div class="mt-1 text-xs text-slate-500">
                                                    {{ $item['project'] }} • {{ $item['org'] }}
                                                </div>

                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    <span class="inline-flex items-center rounded-full border border-{{ $item['color'] }}-200 bg-{{ $item['color'] }}-50 px-2.5 py-1 text-[11px] font-semibold text-{{ $item['color'] }}-700">
                                                        {{ $item['phase'] }}
                                                    </span>

                                                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                                        {{ $item['status'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col gap-2 sm:items-end">
                                            <div class="text-[11px] text-slate-500">
                                                Submitted by {{ $item['submitted_by'] }}
                                            </div>

                                            <button class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-700">
                                                Review
                                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="space-y-4">

                    <div class="rounded-2xl border border-emerald-200 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-emerald-200 bg-gradient-to-r from-emerald-50 to-white px-5 py-4">
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <i data-lucide="user-check" class="w-4 h-4 text-emerald-600"></i>
                                Account Activation
                            </div>

                            <div class="mt-1 text-xs text-slate-500">
                                Role-assigned accounts and login activation.
                            </div>
                        </div>

                        <div class="p-4 space-y-3">
                            @foreach($activationRows as $row)
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="text-xs font-semibold text-slate-900">
                                                {{ $row['name'] }}
                                            </div>

                                            <div class="mt-1 text-[11px] text-slate-500">
                                                {{ $row['role'] }} • {{ $row['org'] }}
                                            </div>
                                        </div>

                                        @if($row['status'] === 'Activated')
                                            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700">
                                                Pending
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <i data-lucide="bolt" class="w-4 h-4 text-indigo-600"></i>
                                Admin Shortcuts
                            </div>

                            <div class="mt-1 text-xs text-slate-500">
                                Common SACDEV actions.
                            </div>
                        </div>

                        <div class="p-4 grid grid-cols-1 gap-2">
                            <button class="inline-flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-3 py-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                <span class="inline-flex items-center gap-2">
                                    <i data-lucide="building-2" class="w-4 h-4 text-indigo-600"></i>
                                    Organizations
                                </span>
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </button>

                            <button class="inline-flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-3 py-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                <span class="inline-flex items-center gap-2">
                                    <i data-lucide="folder-kanban" class="w-4 h-4 text-blue-600"></i>
                                    Projects
                                </span>
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </button>

                            <button class="inline-flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-3 py-3 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                <span class="inline-flex items-center gap-2">
                                    <i data-lucide="calendar-days" class="w-4 h-4 text-emerald-600"></i>
                                    Calendar
                                </span>
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-blue-200 bg-blue-50 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-blue-200 bg-blue-100/50">
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <i data-lucide="book-open" class="w-4 h-4 text-blue-700"></i>
                                Admin Guide
                            </div>

                            <div class="mt-1 text-xs text-blue-800/80">
                                What to prioritize first.
                            </div>
                        </div>

                        <div class="p-4 space-y-3">

                            <div class="rounded-2xl border border-blue-200 bg-white p-3">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                        <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold text-slate-900">
                                            Start with ready registrations
                                        </div>
                                        <div class="mt-1 text-xs leading-5 text-slate-600">
                                            Prioritize organizations with complete requirements waiting for final approval.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-indigo-200 bg-white p-3">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                                        <i data-lucide="file-check-2" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold text-slate-900">
                                            Review document approvals
                                        </div>
                                        <div class="mt-1 text-xs leading-5 text-slate-600">
                                            Submitted documents should be reviewed, approved, or returned with clear remarks.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl border border-emerald-200 bg-white p-3">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                                        <i data-lucide="calendar-check" class="w-4 h-4"></i>
                                    </div>

                                    <div>
                                        <div class="text-xs font-semibold text-slate-900">
                                            Check upcoming activities
                                        </div>
                                        <div class="mt-1 text-xs leading-5 text-slate-600">
                                            Upcoming activity dates help SACDEV anticipate review workload.
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <i data-lucide="calendar-days" class="w-4 h-4 text-indigo-600"></i>
                                Activity Calendar Preview
                            </div>

                            <div class="mt-1 text-xs text-slate-500">
                                Sample upcoming activities and workflow dates.
                            </div>
                        </div>

                        <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                            <i data-lucide="calendar-range" class="w-3 h-3"></i>
                            Sample Schedule
                        </span>
                    </div>
                </div>

                <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($calendarItems as $item)
                        <div class="rounded-2xl border border-{{ $item['color'] }}-200 bg-{{ $item['color'] }}-50 p-4">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-{{ $item['color'] }}-600 border border-{{ $item['color'] }}-200">
                                    <i data-lucide="calendar" class="w-5 h-5"></i>
                                </div>

                                <div>
                                    <div class="text-xs font-semibold text-{{ $item['color'] }}-700">
                                        {{ $item['date'] }}
                                    </div>

                                    <div class="mt-1 text-sm font-semibold text-slate-900">
                                        {{ $item['title'] }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-600">
                                        {{ $item['org'] }}
                                    </div>

                                    <div class="mt-2 inline-flex rounded-full border border-{{ $item['color'] }}-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-{{ $item['color'] }}-700">
                                        {{ $item['type'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</x-app-layout>