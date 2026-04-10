{{-- resources/views/org/strategic_plan/partials/_modals.blade.php --}}
<div x-cloak>

    {{-- RETURN MODAL --}}
    <div x-show="openReturn"
         x-transition
         class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-black/40" @click="openReturn = false"></div>

        <div class="relative w-full max-w-xl rounded-2xl border border-slate-200 bg-white shadow-xl">

            <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                <h3 class="text-sm font-semibold text-slate-900">
                    Return Submission
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Provide remarks explaining what needs to be revised.
                </p>
            </div>

            <form method="POST"
                  action="{{ $canAdminAct
                        ? route('admin.strategic_plans.return', $submission)
                        : route('org.moderator.strategic_plans.return', $submission) }}"
                  class="p-5 space-y-4">
  

                @csrf

                <div class="space-y-2">
                <textarea name="remarks"
                        class="w-full rounded-lg border border-slate-200 text-xs px-3 py-2 focus:ring-1 focus:ring-blue-500"
                        placeholder="Enter remarks"
                        required></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-slate-200">

                    <button type="button"
                            @click="openReturn = false"
                            class="px-4 py-2 text-xs rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 text-xs rounded-lg bg-rose-600 text-white hover:bg-rose-700 transition">
                        Submit Return
                    </button>

                </div>

            </form>

        </div>
    </div>


    {{-- FORWARD MODAL (MODERATOR) --}}
    <div x-show="openForward"
         x-transition
         class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-black/40" @click="openForward = false"></div>

        <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-xl">

            <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                <h3 class="text-sm font-semibold text-slate-900">
                    Forward to SACDEV
                </h3>
            </div>

            <div class="p-5 space-y-2 text-xs text-slate-600">
                <p>
                    You are about to forward this submission to SACDEV for official review.
                </p>
                <p class="text-slate-500">
                    Editing will be locked unless it is returned.
                </p>
            </div>

            <div class="px-5 py-4 border-t border-slate-200 flex justify-end gap-2">

                <button type="button"
                        @click="openForward = false"
                        class="px-4 py-2 text-xs rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </button>

                <form method="POST"
                      action="{{ route('org.moderator.strategic_plans.forward', $submission) }}">
                    @csrf

                    <button type="submit"
                            class="px-4 py-2 text-xs rounded-lg bg-slate-900 text-white hover:bg-slate-800 transition">
                        Confirm Forward
                    </button>
                </form>

            </div>

        </div>
    </div>


    {{-- APPROVE MODAL (ADMIN) --}}
    <div x-show="openApprove"
         x-transition
         class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-black/40" @click="openApprove = false"></div>

        <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-xl">

            <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                <h3 class="text-sm font-semibold text-slate-900">
                    Approve Submission
                </h3>
            </div>

            <div class="p-5 space-y-2 text-xs text-slate-600">
                <p>
                    This will finalize the strategic plan.
                </p>
                <p class="text-slate-500">
                    Make sure everything has been reviewed before approving.
                </p>
            </div>

            <div class="px-5 py-4 border-t border-slate-200 flex justify-end gap-2">

                <button type="button"
                        @click="openApprove = false"
                        class="px-4 py-2 text-xs rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </button>

                <form method="POST"
                      action="{{ route('admin.strategic_plans.approve', $submission) }}">
                    @csrf

                    <button type="submit"
                            class="px-4 py-2 text-xs rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
                        Confirm Approve
                    </button>
                </form>

            </div>

        </div>
    </div>


    {{-- REVERT MODAL --}}
    <div x-show="openRevert"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-black/40" @click="openRevert = false"></div>

        <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-xl">

            <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
                <h3 class="text-sm font-semibold text-slate-900">
                    Retract Approval
                </h3>
            </div>

            <div class="p-5 text-xs text-slate-600 space-y-2">
                <p>
                    This will remove SACDEV approval and return the submission for further changes.
                </p>
            </div>

            <div class="px-5 py-4 border-t border-slate-200 flex justify-end gap-2">

                <button type="button"
                        @click="openRevert = false"
                        class="px-4 py-2 text-xs rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    Cancel
                </button>

                <form method="POST"
                    action="{{ route('admin.strategic_plans.revert_approval', $submission) }}">
                    @csrf

                    <button type="submit"
                            class="px-4 py-2 text-xs rounded-lg bg-amber-600 text-white hover:bg-amber-700 transition">
                        Confirm Retract
                    </button>
                </form>

            </div>

        </div>
    </div>

</div>