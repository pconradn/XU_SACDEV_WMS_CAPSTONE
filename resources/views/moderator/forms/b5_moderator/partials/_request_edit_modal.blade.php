<div id="editRequestModal" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative mx-auto mt-24 w-full max-w-lg px-4">
        <div class="rounded-2xl bg-white shadow-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200">
                <div class="text-base font-semibold text-slate-900">Request Edit</div>
                <div class="text-sm text-slate-600 mt-1">
                    This will notify SACDEV that you need to edit a submitted/approved form.
                </div>
            </div>

            <form method="POST" action="{{ route('moderator.b5.moderator.requestEdit') }}">
                @csrf

                <div class="p-5 space-y-3">
                    <label class="block text-sm font-medium text-slate-700">Message (optional)</label>
                    <textarea name="edit_request_message" rows="4"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                              placeholder="Explain what needs to be changed (optional).">{{ old('edit_request_message') }}</textarea>

                    @if($submission->status === 'approved_by_sacdev')
                        <div class="rounded-lg border border-amber-200 bg-amber-50 p-3 text-sm text-amber-900">
                            Note: This submission is already approved. Once SACDEV grants your request,
                            the status will be moved to <span class="font-semibold">returned_by_sacdev</span> so you can resubmit.
                        </div>
                    @endif
                </div>

                <div class="px-5 py-4 border-t border-slate-200 flex items-center justify-end gap-2">
                    <button type="button"
                            data-close-edit-request-modal
                            class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                        Cancel
                    </button>

                    <button type="submit"
                            class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Send Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
