@php $user = auth()->user(); @endphp

<div
    x-data="{ open: false }"
    x-on:open-guide-modal.window="open = true"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center px-4"
>

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-3xl rounded-2xl border border-slate-200 bg-white shadow-xl">

        {{-- HEADER --}}
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <div class="flex items-center gap-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <i data-lucide="book-open" class="h-4 w-4"></i>
                </div>
                <div class="text-sm font-semibold text-slate-900">
                    Registration Guide
                </div>
            </div>

            <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>

        {{-- BODY --}}
        <div class="p-5 space-y-5 text-xs text-slate-600 max-h-[70vh] overflow-y-auto">

            {{-- ================= PRESIDENT FLOW ================= --}}
            @if($isPresident)

            <div class="space-y-5">

                {{-- HEADER --}}
                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                    <i data-lucide="user-crown" class="h-4 w-4 text-indigo-600"></i>
                    President Workflow
                </div>

                <div class="space-y-3">

                    {{-- STEP 1 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-amber-200 bg-amber-50">
                        <i data-lucide="file-text" class="w-4 h-4 text-amber-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-amber-800">Strategic Plan</div>
                            <div class="text-slate-600">
                                Fill out your organization’s Strategic Plan including planned activities.
                                <span class="font-medium text-amber-700">
                                    Projects and budgets here are not final and are submitted mainly for compliance.
                                </span>
                            </div>
                            <div class="text-slate-600">
                                Forward it to your moderator for review before submission to SACDEV.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                        <i data-lucide="users" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-800">Officers List</div>
                            <div class="text-slate-600">
                                Add all official officers and ensure names and roles are accurate.
                            </div>
                            <div class="text-slate-600">
                                This will be reviewed and approved by SACDEV.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 3 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                        <i data-lucide="user-check" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-800">Assign Moderator</div>
                            <div class="text-slate-600">
                                Assign a moderator who will review your Strategic Plan before final submission.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 4 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                        <i data-lucide="id-card" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-800">Complete Profiles</div>
                            <div class="text-slate-600">
                                Ensure your profile is complete. The moderator must also complete their profile.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 5 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                        <i data-lucide="file-up" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-800">Upload Constitution</div>
                            <div class="text-slate-600">
                                Upload your organization constitution (PDF). Uploading again replaces the previous file.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 6 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-blue-200 bg-blue-50">
                        <i data-lucide="folder-open" class="w-4 h-4 text-blue-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-blue-800">Project Setup</div>
                            <div class="text-slate-600">
                                Once Strategic Plan and Officers List are approved, you may assign project heads.
                            </div>
                            <div class="text-slate-600">
                                Documents can only be saved as <span class="font-medium">draft</span> until the organization is fully registered.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 7 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-emerald-200 bg-emerald-50">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-emerald-800">Finalization</div>
                            <div class="text-slate-600">
                                Wait for SACDEV to complete registration. Once approved, your organization becomes active.
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            @endif


            {{-- ================= MODERATOR FLOW ================= --}}
            @if($isModerator)

            <div class="space-y-5">

                {{-- HEADER --}}
                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                    <i data-lucide="shield-check" class="h-4 w-4 text-blue-600"></i>
                    Moderator Workflow
                </div>

                <div class="space-y-3">

                    {{-- STEP 1 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-blue-200 bg-blue-50">
                        <i data-lucide="file-text" class="w-4 h-4 text-blue-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-blue-800">Review Strategic Plan</div>
                            <div class="text-slate-600">
                                Review the Strategic Plan submitted by the president.
                            </div>
                            <div class="text-slate-600">
                                Ensure activities are appropriate and aligned with organizational guidelines.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                        <i data-lucide="id-card" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-800">Complete Profile</div>
                            <div class="text-slate-600">
                                Fill out your moderator profile and ensure all required fields are complete.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 3 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                        <i data-lucide="send" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-800">Submit Moderator Form</div>
                            <div class="text-slate-600">
                                Fill out moderator details in Moderator Submission Page.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 4 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-emerald-200 bg-emerald-50">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-emerald-800">Forward to SACDEV</div>
                            <div class="text-slate-600">
                                Once reviewed, the Strategic Plan proceeds to SACDEV for final approval.
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            @endif


            {{-- ================= ADMIN FLOW ================= --}}
            @if($user?->isSacdev())

            <div class="space-y-5">

                {{-- HEADER --}}
                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                    <i data-lucide="settings" class="h-4 w-4 text-slate-700"></i>
                    Admin Workflow
                </div>

                <div class="space-y-3">

                    {{-- STEP 1 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-blue-200 bg-blue-50">
                        <i data-lucide="clipboard-check" class="w-4 h-4 text-blue-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-blue-800">Review Submissions</div>
                            <div class="text-slate-600">
                                Review submitted Strategic Plans and Officers Lists from organizations.
                            </div>
                            <div class="text-slate-600">
                                Ensure completeness, correctness, and alignment with guidelines.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                        <i data-lucide="message-square-warning" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-800">Return if Needed</div>
                            <div class="text-slate-600">
                                If issues are found, return the submission with clear remarks for correction.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 3 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-white">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-slate-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-slate-800">Approve Requirements</div>
                            <div class="text-slate-600">
                                Approve valid submissions once all requirements are satisfied.
                            </div>
                        </div>
                    </div>

                    {{-- STEP 4 --}}
                    <div class="flex items-start gap-3 p-3 rounded-lg border border-emerald-200 bg-emerald-50">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600 mt-0.5"></i>
                        <div>
                            <div class="font-semibold text-emerald-800">Activate Organization</div>
                            <div class="text-slate-600">
                                Once all requirements are approved, mark the organization as officially registered.
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            @endif


            {{-- ================= GENERAL NOTES ================= --}}
            <div class="border-t border-slate-200 pt-4 space-y-2">
                <div class="font-semibold text-slate-900">Important Notes</div>

                <ul class="space-y-1">
                    <li>• All required sections must be completed</li>
                    <li>• Approval is handled by SACDEV</li>
                    <li>• Status updates are reflected in the dashboard</li>
                </ul>
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="flex justify-end border-t border-slate-200 px-5 py-3">
            <button
                @click="open = false"
                class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                Got it
            </button>
        </div>

    </div>
</div>