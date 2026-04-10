<div x-show="openReturn"
     x-cloak
     x-transition
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
         @click="openReturn=false"></div>

    <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl overflow-hidden">

        <div class="px-5 py-4 border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white flex items-start justify-between">

            <div class="space-y-1">
                <h3 class="text-base font-semibold text-slate-900">
                    Return Submission
                </h3>

                <p class="text-[11px] text-slate-500">
                    Provide clear remarks for correction.
                </p>
            </div>

            <button @click="openReturn=false"
                    class="text-slate-400 hover:text-slate-600 text-base leading-none">
                ✕
            </button>

        </div>

        <form method="POST"
              action="{{ route('admin.officer_submissions.return', $submission->id) }}"
              class="p-5 space-y-4">

            @csrf

            <div class="text-[11px] text-slate-600">
                The organization can revise and resubmit after addressing your remarks.
            </div>

            <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[11px] text-rose-700 flex items-start gap-2">
                <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mt-0.5"></i>
                <span>This action cannot be undone.</span>
            </div>

            <div class="space-y-1.5">
                <label class="block text-[11px] font-semibold text-slate-700">
                    Return Remarks <span class="text-rose-500">*</span>
                </label>

                <textarea name="sacdev_remarks"
                          rows="5"
                          required
                          class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition"
                          placeholder="Specify required corrections..."></textarea>

                <p class="text-[10px] text-slate-500">
                    Be specific so revisions are clear.
                </p>
            </div>

            <div class="flex justify-end gap-2 pt-2">

                <button type="button"
                        @click="openReturn=false"
                        class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                    Cancel
                </button>

                <button type="submit"
                        class="px-3 py-1.5 text-xs rounded-lg bg-rose-600 text-white font-semibold hover:bg-rose-700 transition shadow-sm">
                    Confirm Return
                </button>

            </div>

        </form>

    </div>

</div>