<div x-data="{ updatesOpen: false }">

  
    <button
        type="button"
        @click="updatesOpen = true; $nextTick(() => window.lucide && window.lucide.createIcons())"
        class="group relative flex items-center justify-center w-10 h-10 rounded-xl border border-amber-200 bg-amber-50 hover:bg-amber-100 shadow-sm transition">

        <i data-lucide="hammer" class="w-4 h-4 text-amber-700"></i>

        {{-- tooltip --}}
        <div class="absolute right-full mr-2 hidden group-hover:flex items-center rounded-md bg-slate-900 px-2 py-1 text-[10px] text-white whitespace-nowrap">
            System Updates
        </div>

    </button>

    {{-- MODAL --}}
    <div
        x-show="updatesOpen"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4 py-6">

        <div
            @click.outside="updatesOpen = false"
            x-transition.scale
            class="w-full max-w-3xl max-h-[85vh] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">

            {{-- MODAL HEADER --}}
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 bg-gradient-to-r from-blue-50 via-white to-slate-50 px-5 py-4">

                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                        <i data-lucide="newspaper" class="w-5 h-5"></i>
                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">
                            Recent System Updates
                        </h2>

                        <p class="mt-1 text-[11px] leading-5 text-slate-500">
                            Summary of recent workflow, interface, and tracking improvements in the SACDEV system.
                        </p>
                    </div>
                </div>

                <button
                    type="button"
                    @click="updatesOpen = false"
                    class="rounded-lg p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>

            </div>

            {{-- SCROLLABLE CONTENT --}}
            <div class="max-h-[65vh] overflow-y-auto px-5 py-5 space-y-4 text-xs">

                {{-- OFFICER SUBMISSION --}}
                <div class="rounded-2xl border border-indigo-200 bg-indigo-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-indigo-800">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        Officer Submission Changes
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>The President is now placed at the top of the officer section instead of being grouped with other major officers.</li>
                        <li>Previously, there were separate input fields for multiple major officers, but those have been removed.</li>
                        <li>Only the President is entered in that top section.</li>
                        <li>All other officers are now added through the Officer Submission list below it.</li>
                    </ul>
                </div>

                {{-- ACCOUNT CREATION CHANGE --}}
                <div class="rounded-2xl border border-purple-200 bg-purple-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-purple-800">
                        <i data-lucide="user-cog" class="w-4 h-4"></i>
                        Account Creation & Role Assignment
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Approval of officer submissions now only creates organization membership records.</li>
                        <li>System accounts are no longer automatically created for Treasurer and Finance Officer during approval.</li>
                        <li>These roles must now be explicitly assigned through:</li>
                        <li class="ml-3">• Organization Info → Assign Finance Approvers</li>
                        <li class="ml-3">• Project Head Assignment module</li>
                        <li>This ensures better control over finance-related permissions and workflow setup.</li>
                    </ul>
                </div>

                {{-- FINANCE APPROVER FLOW --}}
                <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-amber-800">
                        <i data-lucide="workflow" class="w-4 h-4"></i>
                        Finance Approver Workflow Requirement
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Finance approvers (Treasurer and Finance Officer) must be assigned before Project Heads can be designated.</li>
                        <li>This is now enforced as a prerequisite in the workflow.</li>
                        <li>The assignment can be accessed in:</li>
                        <li class="ml-3">• Organization Info page</li>
                        <li class="ml-3">• Project Head Assignment module</li>
                        <li>This guarantees a complete approval chain before project execution begins.</li>
                    </ul>
                </div>

                {{-- PROJECT WORKFLOW --}}
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-emerald-800">
                        <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                        Projects Module Improvements
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Projects now clearly represent activities derived from the Strategic Plan submission.</li>
                        <li>Users can still create additional projects for unplanned or new activities.</li>
                        <li>The module now acts as the central workflow hub for documents, approvals, and tracking.</li>
                    </ul>
                </div>

                {{-- PACKET SYSTEM --}}
                <div class="rounded-2xl border border-blue-200 bg-blue-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-blue-800">
                        <i data-lucide="package-check" class="w-4 h-4"></i>
                        Organization Packet Submission Overhaul
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>The packet submission system was redesigned to better reflect physical document workflows.</li>
                        <li>All packet items are now unified into a single structured list for consistency.</li>
                        <li>Users can now clearly add, review, and organize documents before submission.</li>
                        <li>Instructions were added to guide users through printing and envelope submission.</li>
                    </ul>
                </div>

                {{-- SACDEV REVIEW --}}
                <div class="rounded-2xl border border-indigo-200 bg-indigo-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-indigo-800">
                        <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                        SACDEV Item-Level Review
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>SACDEV can now update the status of each packet item individually.</li>
                        <li>Supported statuses include:</li>
                        <li class="ml-3">• Reviewed</li>
                        <li class="ml-3">• Revision Required</li>
                        <li class="ml-3">• Ready for Claiming</li>
                        <li>This allows more precise tracking instead of handling the packet as a single unit.</li>
                        <li>The review interface was improved for clarity and faster decision-making.</li>
                    </ul>
                </div>

                {{-- AUDIT LOGS --}}
                <div class="rounded-2xl border border-purple-200 bg-purple-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-purple-800">
                        <i data-lucide="history" class="w-4 h-4"></i>
                        Audit Logging
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Audit logs were added for all key packet actions.</li>
                        <li>This includes creation, deletion, review, status updates, claiming, and reverting.</li>
                        <li>Logs capture user actions along with organization and project context.</li>
                    </ul>
                </div>

                {{-- BREADCRUMBS --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                        <i data-lucide="map" class="w-4 h-4"></i>
                        Breadcrumb Navigation (Ongoing)
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Breadcrumbs were added across key pages to improve navigation clarity.</li>
                        <li>This includes Organization, Projects, Document Hub, and Packet pages.</li>
                        <li>Implementation is still ongoing and will be expanded further.</li>
                    </ul>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="flex justify-end border-t border-slate-200 bg-slate-50 px-5 py-3">
                <button
                    type="button"
                    @click="updatesOpen = false"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-700">
                    Close
                </button>
            </div>

        </div>
    </div>

</div>