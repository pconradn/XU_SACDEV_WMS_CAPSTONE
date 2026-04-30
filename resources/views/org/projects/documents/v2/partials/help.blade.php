<div
    x-show="helpOpen"
    x-cloak
    x-transition.opacity
    class="fixed inset-0 z-[998] flex items-center justify-center bg-black/60 backdrop-blur-sm px-3"
>

    <div @click.away="helpOpen = false"
         class="bg-gradient-to-b from-slate-50 to-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-200">

        <div class="px-5 py-4 border-b border-slate-200 flex items-start justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">

            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 border border-amber-200">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                </div>

                <div>
                    <h2 class="text-base font-semibold text-slate-900">
                        Project Document Hub Guide
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Use this guide to understand submissions, approvals, off-campus clearance, packets, and document status rules.
                    </p>
                </div>
            </div>

            <button
                type="button"
                @click="helpOpen = false"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-800"
            >
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

        </div>

        <div class="px-5 py-4 overflow-y-auto text-xs text-slate-700">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                            <i data-lucide="file-stack" class="w-4 h-4"></i>
                        </div>

                        <div>
                            <div class="font-semibold text-slate-900">
                                Combined Proposal Form
                            </div>
                            <div class="mt-1 leading-5 text-slate-600">
                                The Project Proposal and Budget Proposal are handled together in one combined form. This helps the system read the project details, budget, venue, funding source, and activity setup in one place.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                            <i data-lucide="workflow" class="w-4 h-4"></i>
                        </div>

                        <div>
                            <div class="font-semibold text-slate-900">
                                Requirements Are Triggered by Submission
                            </div>
                            <div class="mt-1 leading-5 text-slate-600">
                                Project requirements are computed from the proposal data. After the proposal is submitted, the system can determine which forms are required, such as budget, off-campus, solicitation, selling, documentation, or liquidation requirements.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-cyan-200 bg-cyan-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-cyan-100 text-cyan-700">
                            <i data-lucide="users-round" class="w-4 h-4"></i>
                        </div>

                        <div>
                            <div class="font-semibold text-slate-900">
                                Project Head and Draftee Rules
                            </div>
                            <div class="mt-1 leading-5 text-slate-600">
                                Project heads can submit documents into the approval process. Draftees can help prepare and save drafts, but final submission should remain under the project head’s responsibility.
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
                            <div class="font-semibold text-slate-900">
                                What Counts as Completed?
                            </div>
                            <div class="mt-1 leading-5 text-slate-600">
                                A document is considered completed only when it reaches Approved by SACDEV. Draft, submitted, returned, and waiting-for-approval documents are still part of the workflow but are not yet completed.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-5 grid grid-cols-1 lg:grid-cols-2 gap-5">

                <div class="rounded-2xl border border-blue-200 bg-white overflow-hidden">
                    <div class="border-b border-blue-200 bg-blue-50 px-4 py-3">
                        <div class="flex items-center gap-2 font-semibold text-slate-900">
                            <i data-lucide="file-pen-line" class="w-4 h-4 text-blue-700"></i>
                            Accomplishing and Submitting Forms
                        </div>
                        <div class="mt-1 text-[11px] text-blue-800/80">
                            Use this when preparing proposals, reports, notices, and other project documents.
                        </div>
                    </div>

                    <div class="p-4 space-y-3">

                        <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-slate-200 text-[11px] font-bold text-slate-700">
                                1
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900">Open the Project Hub</div>
                                <div class="mt-0.5 leading-5 text-slate-600">
                                    The hub is the main workspace for one project. It shows required forms, optional actions, approval status, clearance, packets, and next steps.
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-slate-200 text-[11px] font-bold text-slate-700">
                                2
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900">Save Draft While Preparing</div>
                                <div class="mt-0.5 leading-5 text-slate-600">
                                    Use drafts while information is still incomplete. Draftees may assist by preparing and saving drafts when allowed.
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 p-3">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-blue-200 text-[11px] font-bold text-blue-700">
                                3
                            </div>
                            <div>
                                <div class="font-semibold text-blue-900">Submit When Ready</div>
                                <div class="mt-0.5 leading-5 text-slate-600">
                                    Submitting sends the document into the approval route. Once submitted, it is no longer simply a working draft.
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-3">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-white border border-amber-200 text-[11px] font-bold text-amber-700">
                                4
                            </div>
                            <div>
                                <div class="font-semibold text-amber-900">Resubmission Restarts Approval</div>
                                <div class="mt-0.5 leading-5 text-slate-600">
                                    If a returned or edited document is submitted again, it begins the approval process again so the updated version can be reviewed properly.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="rounded-2xl border border-violet-200 bg-white overflow-hidden">
                    <div class="border-b border-violet-200 bg-violet-50 px-4 py-3">
                        <div class="flex items-center gap-2 font-semibold text-slate-900">
                            <i data-lucide="route" class="w-4 h-4 text-violet-700"></i>
                            Approval and Status Rules
                        </div>
                        <div class="mt-1 text-[11px] text-violet-800/80">
                            These statuses explain where the document is in the workflow.
                        </div>
                    </div>

                    <div class="p-4 space-y-3">

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <div class="flex items-center gap-2 font-semibold text-slate-900">
                                <i data-lucide="file-pen-line" class="w-4 h-4 text-slate-600"></i>
                                Draft
                            </div>
                            <div class="mt-1 leading-5 text-slate-600">
                                The document is still being prepared. It has not entered the approval route yet.
                            </div>
                        </div>

                        <div class="rounded-xl border border-blue-200 bg-blue-50 p-3">
                            <div class="flex items-center gap-2 font-semibold text-blue-900">
                                <i data-lucide="send" class="w-4 h-4 text-blue-700"></i>
                                Submitted
                            </div>
                            <div class="mt-1 leading-5 text-slate-600">
                                The document is already in the approval flow. The project head usually waits while the current approver reviews it.
                            </div>
                        </div>

                        <div class="rounded-xl border border-rose-200 bg-rose-50 p-3">
                            <div class="flex items-center gap-2 font-semibold text-rose-900">
                                <i data-lucide="file-warning" class="w-4 h-4 text-rose-700"></i>
                                Returned
                            </div>
                            <div class="mt-1 leading-5 text-slate-600">
                                The document needs correction. Review remarks, update the form, then submit again.
                            </div>
                        </div>

                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3">
                            <div class="flex items-center gap-2 font-semibold text-emerald-900">
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-700"></i>
                                Approved by SACDEV
                            </div>
                            <div class="mt-1 leading-5 text-slate-600">
                                The document has completed the required approval route and counts as completed in the workflow.
                            </div>
                        </div>

                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-3">
                            <div class="flex items-center gap-2 font-semibold text-amber-900">
                                <i data-lucide="unlock" class="w-4 h-4 text-amber-700"></i>
                                Request to Edit
                            </div>
                            <div class="mt-1 leading-5 text-slate-600">
                                If a document is already approved by SACDEV, edits should be requested instead of directly changing the approved record.
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="mt-5 grid grid-cols-1 lg:grid-cols-2 gap-5">

                <div class="rounded-2xl border border-orange-200 bg-white overflow-hidden">
                    <div class="border-b border-orange-200 bg-orange-50 px-4 py-3">
                        <div class="flex items-center gap-2 font-semibold text-slate-900">
                            <i data-lucide="package-check" class="w-4 h-4 text-orange-700"></i>
                            Org Packet Submission Guide
                        </div>
                        <div class="mt-1 text-[11px] text-orange-800/80">
                            Used for physical or external documents that still need tracking.
                        </div>
                    </div>

                    <div class="p-4 space-y-3">

                        <div class="flex items-start gap-3 p-3 rounded-xl border border-orange-200 bg-orange-50">
                            <i data-lucide="check-square" class="w-4 h-4 text-orange-700 mt-0.5"></i>
                            <div>
                                <div class="font-semibold text-orange-900">Select Included Documents</div>
                                <div class="leading-5 text-slate-600">
                                    Mark documents included in your physical packet, such as receipts, vouchers, letters, or supporting files.
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 bg-slate-50">
                            <i data-lucide="plus-circle" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                            <div>
                                <div class="font-semibold text-slate-900">Add Supporting Details</div>
                                <div class="leading-5 text-slate-600">
                                    Enter receipt numbers, DV references, organization names, amounts, remarks, or other tracking details.
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 bg-slate-50">
                            <i data-lucide="save" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                            <div>
                                <div class="font-semibold text-slate-900">Save and Compile</div>
                                <div class="leading-5 text-slate-600">
                                    Save your packet entries before submitting the physical documents to SACDEV.
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl border border-emerald-200 bg-emerald-50">
                            <i data-lucide="inbox" class="w-4 h-4 text-emerald-700 mt-0.5"></i>
                            <div>
                                <div class="font-semibold text-emerald-900">Submit Physical Packet</div>
                                <div class="leading-5 text-slate-600">
                                    Once SACDEV receives the packet, editing may be locked and review tracking begins.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="rounded-2xl border border-purple-200 bg-white overflow-hidden">
                    <div class="border-b border-purple-200 bg-purple-50 px-4 py-3">
                        <div class="flex items-center gap-2 font-semibold text-slate-900">
                            <i data-lucide="map" class="w-4 h-4 text-purple-700"></i>
                            Off-Campus Clearance Guide
                        </div>
                        <div class="mt-1 text-[11px] text-purple-800/80">
                            Applies when the project is marked as an off-campus activity.
                        </div>
                    </div>

                    <div class="p-4 space-y-3">

                        <div class="flex items-start gap-3 p-3 rounded-xl border border-purple-200 bg-purple-50">
                            <i data-lucide="file-text" class="w-4 h-4 text-purple-700 mt-0.5"></i>
                            <div>
                                <div class="font-semibold text-purple-900">Generate Clearance</div>
                                <div class="leading-5 text-slate-600">
                                    Generate the clearance form from the system once the project requires off-campus processing.
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 bg-slate-50">
                            <i data-lucide="printer" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                            <div>
                                <div class="font-semibold text-slate-900">Print and Sign</div>
                                <div class="leading-5 text-slate-600">
                                    Print the clearance and secure the required signatures outside the system.
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 bg-slate-50">
                            <i data-lucide="upload" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                            <div>
                                <div class="font-semibold text-slate-900">Upload File</div>
                                <div class="leading-5 text-slate-600">
                                    Upload the signed clearance document. If a file is already uploaded, it should not keep appearing as a missing task unless rejected.
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl border border-amber-200 bg-amber-50">
                            <i data-lucide="refresh-cw" class="w-4 h-4 text-amber-700 mt-0.5"></i>
                            <div>
                                <div class="font-semibold text-amber-900">If Rejected or Returned</div>
                                <div class="leading-5 text-slate-600">
                                    Review the remarks and upload the corrected clearance file again.
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl border border-indigo-200 bg-indigo-50">
                            <i data-lucide="plane" class="w-4 h-4 text-indigo-700 mt-0.5"></i>
                            <div>
                                <div class="font-semibold text-indigo-900">Student Off-Campus Form</div>
                                <div class="leading-5 text-slate-600">
                                    The student off-campus form is available when the project is off-campus and the proposal has already been approved.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="mt-5 grid grid-cols-1 lg:grid-cols-3 gap-4">

                <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-700">
                            <i data-lucide="receipt-text" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">DV Generation</div>
                            <div class="mt-1 leading-5 text-slate-600">
                                The DV is generated from budget data. It is not treated as a full workflow document, but it can be accomplished outside the system and included in packet submissions.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-sky-200 bg-sky-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                            <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Postponement and Cancellation</div>
                            <div class="mt-1 leading-5 text-slate-600">
                                Notices are used when a project schedule changes or the activity will no longer continue. These should also follow approval rules.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-slate-600 border border-slate-200">
                            <i data-lucide="history" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Timeline and Remarks</div>
                            <div class="mt-1 leading-5 text-slate-600">
                                Use the timeline to review status changes, approval movement, returns, and remarks recorded throughout the document process.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="px-5 py-3 border-t border-slate-200 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between bg-white">

            <div class="text-[11px] text-slate-500">
                Open the Project Hub whenever you are unsure what requirement should be done next.
            </div>

            <button
                type="button"
                @click="helpOpen = false"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 transition"
            >
                <i data-lucide="check" class="w-4 h-4"></i>
                Got it
            </button>

        </div>

    </div>
</div>