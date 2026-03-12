
<div id="editRequestModal"
     class="fixed inset-0 z-50 hidden bg-slate-900/50 px-4 py-6 overflow-y-auto">
    <div class="mx-auto w-full max-w-lg rounded-2xl bg-white p-5 shadow-xl">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Request Edit</h3>
                <p class="mt-1 text-sm text-slate-600">
                    Send a message to SACDEV asking them to allow editing for this form.
                </p>
            </div>

            <button type="button"
                    data-close-edit-request-modal
                    class="rounded-lg px-2 py-1 text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                ✕
            </button>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-slate-700">
                Message (optional)
            </label>

            <textarea name="edit_request_message"
                      rows="4"
                      class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-slate-400 focus:outline-none"
                      placeholder="Example: I need to correct my department/contact info. Please allow editing.">{{ old('edit_request_message', $submission->edit_request_message) }}</textarea>

            <div class="mt-2 text-xs text-slate-500">
                Once sent, the request will show as pending until SACDEV responds.
            </div>
        </div>

        <div class="mt-5 flex flex-col gap-2 sm:flex-row sm:justify-end">
            <button type="button"
                    data-close-edit-request-modal
                    class="inline-flex justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">
                Cancel
            </button>

            <button type="submit"
                    formaction="{{ route('org.moderator.rereg.b5.requestEdit') }}"
                    class="inline-flex justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Send Request
            </button>
        </div>
    </div>
</div>
