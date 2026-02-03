<div id="revertApprovalModal" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative mx-auto mt-24 w-full max-w-lg px-4">
        <div class="rounded-2xl bg-white shadow-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200">
                <div class="text-base font-semibold text-slate-900">Revert Approval</div>
                <div class="text-sm text-slate-600 mt-1">
                    This will unfinalize the approval and return the form for revision.
                    Remarks are required.
                </div>
            </div>

            <form method="POST" action="{{ route('admin.moderator_submissions.revert_approval', $submission) }}">
                @csrf

                <div class="p-5 space-y-3">
                    <label class="block text-sm font-medium text-slate-700">Reason / Remarks (required)</label>
                    <textarea name="sacdev_remarks" rows="5"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                              required>{{ old('sacdev_remarks') }}</textarea>

                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-900">
                        After reverting, the moderator can edit and must resubmit.
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-slate-200 flex items-center justify-end gap-2">
                    <button type="button"
                            data-close-revert-approval-modal
                            class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                        Cancel
                    </button>

                    <button type="submit"
                            class="inline-flex justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        Revert Approval
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
