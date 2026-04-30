<x-app-layout>

<div class="min-h-screen bg-slate-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <nav class="text-xs text-slate-500">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li class="font-medium text-slate-600">
                    Dashboard
                </li>

                <li class="text-slate-300">/</li>

                <li class="font-medium text-indigo-700">
                    Workflow Home
                </li>
            </ol>
        </nav>

        <div class="rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-blue-50 shadow-sm overflow-hidden">
            <div class="p-6 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200 shadow-sm">
                        <i data-lucide="layout-dashboard" class="w-7 h-7"></i>
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-2.5 py-1 text-[11px] font-semibold text-indigo-700">
                                <i data-lucide="sparkles" class="w-3 h-3"></i>
                                Workflow Guide
                            </span>

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">
                                <i data-lucide="calendar-range" class="w-3 h-3"></i>
                                Active School Year
                            </span>
                        </div>

                        <h1 class="text-2xl font-semibold text-slate-900 leading-tight">
                            Welcome to your SACDEV workspace
                        </h1>

                        <p class="mt-1 text-sm leading-6 text-slate-600 max-w-3xl">
                            This dashboard shows what the system is for, what step your organization is currently in, and what actions should be done next.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="#next-actions"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        <i data-lucide="list-checks" class="w-4 h-4"></i>
                        View Next Actions
                    </a>

                    <a href="#workflow-guide"
                       class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <i data-lucide="map" class="w-4 h-4"></i>
                        System Guide
                    </a>
                </div>

            </div>
        </div>

        <div id="next-actions" class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                        <i data-lucide="circle-alert" class="w-4 h-4 text-indigo-600"></i>
                        What needs your attention?
                    </div>
                    <div class="mt-1 text-xs text-slate-500">
                        The system highlights the next practical steps instead of only showing counters.
                    </div>
                </div>

                <div class="p-5 space-y-3">

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="text-sm font-semibold text-slate-900">
                                        Complete organization setup
                                    </div>

                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-amber-700">
                                        First Step
                                    </span>
                                </div>

                                <p class="mt-1 text-xs leading-5 text-slate-600">
                                    Before regular project workflows can proceed, the organization must satisfy its registration or re-registration requirements for the active school year.
                                </p>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <a href="#"
                                       class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-amber-700">
                                        Open Re-Registration
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>

                                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-white px-3 py-1.5 text-xs font-medium text-amber-700">
                                        <i data-lucide="info" class="w-3 h-3"></i>
                                        Placeholder action
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                                <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="text-sm font-semibold text-slate-900">
                                        Continue project document workflow
                                    </div>

                                    <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-indigo-700">
                                        Main Workflow
                                    </span>
                                </div>

                                <p class="mt-1 text-xs leading-5 text-slate-600">
                                    Projects move through proposal preparation, approvals, SACDEV review, implementation, and post-implementation requirements.
                                </p>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <a href="#"
                                       class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-700">
                                        Open Projects
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>

                                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-white px-3 py-1.5 text-xs font-medium text-indigo-700">
                                        <i data-lucide="file-check-2" class="w-3 h-3"></i>
                                        3 documents need review
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                                <i data-lucide="user-round-check" class="w-5 h-5"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-slate-900">
                                    Check role-based responsibilities
                                </div>

                                <p class="mt-1 text-xs leading-5 text-slate-600">
                                    Presidents, project heads, treasurers, finance officers, moderators, and SACDEV admins see different actions depending on their role in the workflow.
                                </p>

                                <div class="mt-3">
                                    <a href="#"
                                       class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                        View My Responsibilities
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                        <i data-lucide="navigation" class="w-4 h-4 text-indigo-600"></i>
                        Start here
                    </div>
                    <div class="mt-1 text-xs text-slate-500">
                        Beginner-friendly shortcuts.
                    </div>
                </div>

                <div class="p-5 space-y-3">

                    <a href="#"
                       class="group block rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50/30">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                <i data-lucide="building-2" class="w-5 h-5"></i>
                            </div>

                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-slate-900">
                                    Organization Profile
                                </div>
                                <div class="mt-1 text-xs leading-5 text-slate-500">
                                    View your organization identity, officers, members, and setup status.
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="#"
                       class="group block rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50/30">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                            </div>

                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-slate-900">
                                    Projects Module
                                </div>
                                <div class="mt-1 text-xs leading-5 text-slate-500">
                                    Prepare project documents, track approvals, and continue requirements.
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="#"
                       class="group block rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50/30">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                            </div>

                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-slate-900">
                                    Notifications
                                </div>
                                <div class="mt-1 text-xs leading-5 text-slate-500">
                                    See returned documents, approval requests, and important updates.
                                </div>
                            </div>
                        </div>
                    </a>

                </div>
            </div>

        </div>

        <div id="workflow-guide" class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                    <i data-lucide="map" class="w-4 h-4 text-indigo-600"></i>
                    System workflow guide
                </div>
                <div class="mt-1 text-xs text-slate-500">
                    A simple overview of how users move through the system.
                </div>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                            <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                        </div>

                        <div class="mt-4 text-sm font-semibold text-slate-900">
                            1. Register
                        </div>

                        <div class="mt-1 text-xs leading-5 text-slate-600">
                            Complete organization requirements for the active school year.
                        </div>
                    </div>

                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>

                        <div class="mt-4 text-sm font-semibold text-slate-900">
                            2. Assign Roles
                        </div>

                        <div class="mt-1 text-xs leading-5 text-slate-600">
                            Officers and project heads are identified for workflow responsibilities.
                        </div>
                    </div>

                    <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                            <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                        </div>

                        <div class="mt-4 text-sm font-semibold text-slate-900">
                            3. Prepare Projects
                        </div>

                        <div class="mt-1 text-xs leading-5 text-slate-600">
                            Project heads prepare proposals, budgets, and required documents.
                        </div>
                    </div>

                    <div class="rounded-2xl border border-violet-200 bg-violet-50 p-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                            <i data-lucide="workflow" class="w-5 h-5"></i>
                        </div>

                        <div class="mt-4 text-sm font-semibold text-slate-900">
                            4. Route Approvals
                        </div>

                        <div class="mt-1 text-xs leading-5 text-slate-600">
                            Documents pass through required role-based approvals before final review.
                        </div>
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                        </div>

                        <div class="mt-4 text-sm font-semibold text-slate-900">
                            5. Complete
                        </div>

                        <div class="mt-1 text-xs leading-5 text-slate-600">
                            After implementation, post-activity documents and final requirements are completed.
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                        <i data-lucide="folder-kanban" class="w-4 h-4 text-indigo-600"></i>
                        Current project workflow
                    </div>
                    <div class="mt-1 text-xs text-slate-500">
                        Placeholder project cards showing how the dashboard can guide users.
                    </div>
                </div>

                <div class="p-5 space-y-3">

                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                    <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                                </div>

                                <div>
                                    <div class="text-sm font-semibold text-slate-900">
                                        Leadership Training Seminar
                                    </div>

                                    <div class="mt-1 flex flex-wrap items-center gap-2">
                                        <span class="rounded-full border border-blue-200 bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700">
                                            Pre-Implementation
                                        </span>

                                        <span class="rounded-full border border-amber-200 bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700">
                                            Needs Revision
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <a href="#"
                               class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                Continue
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-slate-600">
                                    Document progress
                                </span>
                                <span class="font-semibold text-indigo-700">
                                    60%
                                </span>
                            </div>

                            <div class="mt-1.5 h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full w-[60%] rounded-full bg-gradient-to-r from-indigo-500 to-blue-500"></div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                </div>

                                <div>
                                    <div class="text-sm font-semibold text-slate-900">
                                        Community Outreach Activity
                                    </div>

                                    <div class="mt-1 flex flex-wrap items-center gap-2">
                                        <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                                            Approved
                                        </span>

                                        <span class="rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-[10px] font-semibold text-slate-600">
                                            Post-Implementation Next
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <a href="#"
                               class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                View Requirements
                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                            </a>
                        </div>

                        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">
                            This project is approved. The next step is to prepare required post-implementation documents after the activity.
                        </div>
                    </div>

                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-5 py-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                        <i data-lucide="book-open" class="w-4 h-4 text-indigo-600"></i>
                        Quick explanations
                    </div>
                    <div class="mt-1 text-xs text-slate-500">
                        Helps first-time users understand terms.
                    </div>
                </div>

                <div class="p-5 space-y-3">

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-900">
                            What is a project document?
                        </div>

                        <div class="mt-1 text-xs leading-5 text-slate-600">
                            A form connected to a project, such as a project proposal, budget proposal, liquidation report, or documentation report.
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-900">
                            What does “returned” mean?
                        </div>

                        <div class="mt-1 text-xs leading-5 text-slate-600">
                            The document needs corrections. Review the feedback, update the form, then submit it again.
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-900">
                            Who approves documents?
                        </div>

                        <div class="mt-1 text-xs leading-5 text-slate-600">
                            Approvers depend on the document type and assigned roles, such as president, treasurer, finance officer, moderator, or SACDEV admin.
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

</x-app-layout>