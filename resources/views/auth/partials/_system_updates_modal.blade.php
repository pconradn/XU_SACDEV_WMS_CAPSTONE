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

                {{-- STRATEGIC PLAN FLOW --}}
                <div class="rounded-2xl border border-blue-200 bg-blue-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-blue-800">
                        <i data-lucide="panel-top-open" class="w-4 h-4"></i>
                        Strategic Plan Page Flow
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>The Strategic Plan fill-up page was reorganized into a guided step-based layout for organization users.</li>
                        <li>The form now follows this sequence:</li>
                        <li class="ml-3">• Organization Identity</li>
                        <li class="ml-3">• Projects</li>
                        <li class="ml-3">• Sources of Funds</li>
                        <li class="ml-3">• Review and Submit</li>
                        <li>This improves continuity and reduces the disconnected feeling between form sections.</li>
                    </ul>
                </div>

                {{-- REVIEWER VIEW --}}
                <div class="rounded-2xl border border-cyan-200 bg-cyan-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-cyan-800">
                        <i data-lucide="scan-eye" class="w-4 h-4"></i>
                        Strategic Plan Review View
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>The step-based layout is only used when an organization user is completing the Strategic Plan.</li>
                        <li>Moderator and SACDEV review pages still show the full stacked layout.</li>
                        <li>This allows reviewers to scan organization identity, projects, funds, and submission details in one continuous view.</li>
                        <li>The same submission data is used, but the display changes based on the user’s role and task.</li>
                    </ul>
                </div>

                {{-- STEPPER REDIRECT FIX --}}
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-emerald-800">
                        <i data-lucide="move-right" class="w-4 h-4"></i>
                        Step Return and Scroll Behavior
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>The system now remembers the active Strategic Plan step after saving or reloading.</li>
                        <li>Project actions return users to the Projects step.</li>
                        <li>Fund source actions return users to the Sources of Funds step.</li>
                        <li>The page also scrolls back to the stepper area instead of returning to the very top of the page.</li>
                        <li>This improves the experience on mobile screens where the header takes more vertical space.</li>
                    </ul>
                </div>

                {{-- FUNDS LIVEWIRE FIX --}}
                <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-amber-800">
                        <i data-lucide="wallet-cards" class="w-4 h-4"></i>
                        Sources of Funds Save Behavior
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>The Sources of Funds card now returns to the correct step after saving.</li>
                        <li>The save confirmation modal now closes immediately when the user confirms the action.</li>
                        <li>The funding section remains Livewire-based, allowing fund source entries and totals to update more smoothly.</li>
                    </ul>
                </div>

                {{-- LIQUIDATION REPORT --}}
                <div class="rounded-2xl border border-rose-200 bg-rose-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-rose-800">
                        <i data-lucide="calculator" class="w-4 h-4"></i>
                        Liquidation Report Calculation Update
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>The Amount to be Returned fields in the Liquidation Report are no longer manually encoded by users.</li>
                        <li>The system now automatically calculates the return amount based on the entered liquidation values.</li>
                        <li>The fields were made read-only to prevent incorrect manual input.</li>
                        <li>This follows the previous SACDEV spreadsheet behavior where the return amount was computed automatically.</li>
                    </ul>
                </div>



                {{-- ROLE-BASED TASK MESSAGES --}}
                <div class="rounded-2xl border border-violet-200 bg-violet-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-violet-800">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        Role-Based Task Message Clarity
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Task and caution messages were revised to make role responsibility clearer.</li>
                        <li>Messages now better indicate when an action depends on another role, such as the moderator.</li>
                        <li>This reduces confusion when a pending item cannot be completed by the current user.</li>
                    </ul>
                </div>

                {{-- ON-PAGE GUIDANCE --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        On-Page Guidance Improvements
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Additional guide text was added inside key pages to support users during workflow completion.</li>
                        <li>The update helps reduce dependence on external task guides during testing or actual use.</li>
                        <li>Labels, helper text, and task descriptions were refined for better readability.</li>
                    </ul>
                </div>

                {{-- OFFICER SUBMISSION --}}
                <div class="rounded-2xl border border-indigo-200 bg-indigo-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-indigo-800">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        Officer Submission Changes
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>The President is now placed at the top of the officer section instead of being grouped with other major officers.</li>
                        <li>Separate input fields for multiple major officers were removed.</li>
                        <li>All other officers are now added through the Officer Submission list.</li>
                    </ul>
                </div>

                {{-- ACCOUNT CREATION CHANGE --}}
                <div class="rounded-2xl border border-purple-200 bg-purple-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-purple-800">
                        <i data-lucide="user-cog" class="w-4 h-4"></i>
                        Account Creation and Role Assignment
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Approval of officer submissions now creates organization membership records only.</li>
                        <li>System accounts are no longer automatically created for Treasurer and Finance Officer during approval.</li>
                        <li>Finance-related roles are assigned through Organization Info or the Project Head Assignment module.</li>
                        <li>This gives SACDEV better control over workflow permissions.</li>
                    </ul>
                </div>

                {{-- FINANCE APPROVER FLOW --}}
                <div class="rounded-2xl border border-orange-200 bg-orange-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-orange-800">
                        <i data-lucide="workflow" class="w-4 h-4"></i>
                        Finance Approver Workflow Requirement
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Finance approvers must be assigned before Project Heads can be designated.</li>
                        <li>This is now enforced as a workflow prerequisite.</li>
                        <li>The rule helps ensure that the approval chain is complete before project document processing begins.</li>
                    </ul>
                </div>

                {{-- PROJECT WORKFLOW --}}
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-emerald-800">
                        <i data-lucide="folder-kanban" class="w-4 h-4"></i>
                        Projects Module Improvements
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Projects now clearly represent activities derived from the approved Strategic Plan.</li>
                        <li>Users may still add additional projects for new or unplanned activities.</li>
                        <li>The module acts as the central hub for project documents, approvals, and tracking.</li>
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
                        <li>Packet items are now unified into a single structured list.</li>
                        <li>Users can add, review, and organize physical document items before submission.</li>
                        <li>Instructions were added for printing and envelope submission.</li>
                    </ul>
                </div>

                {{-- SACDEV REVIEW --}}
                <div class="rounded-2xl border border-indigo-200 bg-indigo-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-indigo-800">
                        <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                        SACDEV Item-Level Review
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>SACDEV can update the status of each packet item individually.</li>
                        <li>Supported statuses include Reviewed, Revision Required, and Ready for Claiming.</li>
                        <li>This allows more precise tracking instead of handling the packet as one whole item.</li>
                    </ul>
                </div>

                {{-- AUDIT LOGS --}}
                <div class="rounded-2xl border border-purple-200 bg-purple-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-purple-800">
                        <i data-lucide="history" class="w-4 h-4"></i>
                        Audit Logging
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Audit logs were added for key packet actions.</li>
                        <li>This includes creation, deletion, review, status updates, claiming, and reverting.</li>
                        <li>Logs capture the user action together with organization and project context.</li>
                    </ul>
                </div>

                {{-- BREADCRUMBS --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                        <i data-lucide="map" class="w-4 h-4"></i>
                        Breadcrumb Navigation
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Breadcrumbs were added across key pages to improve navigation clarity.</li>
                        <li>This includes Organization, Projects, Document Hub, and Packet pages.</li>
                        <li>Breadcrumb implementation will continue as additional pages are finalized.</li>
                    </ul>
                </div>

                {{-- DASHBOARD POST-IMPLEMENTATION TASK VISIBILITY --}}
                <div class="rounded-2xl border border-teal-200 bg-teal-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-teal-800">
                        <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                        Dashboard Post-Implementation Task Visibility
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Post-implementation requirements are no longer shown too early in the dashboard pending tasks.</li>
                        <li>Forms such as Documentation Report and Liquidation Report are hidden before the project implementation period is completed.</li>
                        <li>A dashboard note was added to inform users that other requirements may appear after implementation.</li>
                        <li>This prevents users from thinking that post-activity documents must be completed before the activity has taken place.</li>
                    </ul>
                </div>

                {{-- RETURNED DOCUMENT TASK HIGHLIGHT --}}
                <div class="rounded-2xl border border-rose-200 bg-rose-50/70 p-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-rose-800">
                        <i data-lucide="file-warning" class="w-4 h-4"></i>
                        Returned Document Task Highlight
                    </div>

                    <ul class="mt-3 space-y-1.5 text-[11px] leading-5 text-slate-600">
                        <li>Returned project documents are now separated from the normal list of pending project tasks.</li>
                        <li>Documents with return remarks are shown as revision tasks instead of regular required tasks.</li>
                        <li>A dedicated returned document card was added to make revision requirements more noticeable.</li>
                        <li>This helps project heads quickly identify which documents need corrections before resubmission.</li>
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