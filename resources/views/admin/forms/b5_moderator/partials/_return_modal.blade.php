<template x-if="openReturn">
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">

        {{-- BACKDROP --}}
        <div class="absolute inset-0 bg-black/40" @click="openReturn = false"></div>

        {{-- MODAL --}}
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200">

            {{-- HEADER --}}
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">
                    Return to Moderator
                </h3>
                <p class="text-sm text-slate-500 mt-1">
                    Provide clear remarks so the moderator can revise the submission properly.
                </p>
            </div>

            {{-- FORM --}}
            <form method="POST"
                  action="{{ route('admin.moderator_submissions.return', $submission) }}"
                  id="b5ReturnForm"
                  class="px-6 py-5 space-y-5">

                @csrf

                {{-- CONTEXT --}}
                <p class="text-sm text-slate-600">
                    The moderator will be able to edit and resubmit after addressing your remarks.
                </p>

                {{-- WARNING --}}
                <div class="rounded-lg border border-rose-200 bg-rose-50/70 p-3 text-sm text-rose-900">
                    This action cannot be undone.
                </div>

                {{-- TEXTAREA (REPLACED QUILL) --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Remarks <span class="text-rose-500">*</span>
                    </label>

                    <textarea name="sacdev_remarks"
                              rows="6"
                              required
                              class="mt-2 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                              placeholder="Enter required changes..."></textarea>

                    <p class="mt-1 text-xs text-slate-500">
                        Be specific about what needs to be corrected.
                    </p>
                </div>

                {{-- ACTIONS --}}
                <div class="flex items-center justify-end gap-2 pt-2">

                    <button type="button"
                            @click="openReturn = false"
                            class="px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-rose-600 rounded-lg hover:bg-rose-700 transition">
                        Return Submission
                    </button>

                </div>

            </form>

        </div>
    </div>
</template>