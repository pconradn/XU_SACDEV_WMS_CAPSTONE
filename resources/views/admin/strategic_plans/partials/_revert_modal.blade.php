<div x-show="openReturn" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-slate-900/50" @click="openReturn=false"></div>

    <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl p-5">

        <h3 class="text-lg font-semibold text-slate-900">
            Return to Organization
        </h3>

        <p class="text-sm text-slate-600 mt-1">
            This will allow editing again. Remarks are required.
        </p>

        <form method="POST"
              action="{{ route('admin.strategic_plans.return', $submission) }}"
              class="mt-4 space-y-3">

            @csrf

            <textarea name="remarks" rows="4"
                required
                class="w-full rounded-lg border-slate-200"
                placeholder="Enter required changes..."></textarea>

            <div class="flex justify-end gap-2">
                <button type="button"
                        @click="openReturn=false"
                        class="rounded-lg border px-4 py-2 text-sm">
                    Cancel
                </button>

                <button type="submit"
                        class="rounded-lg bg-rose-600 px-4 py-2 text-sm text-white">
                    Return
                </button>
            </div>

        </form>

    </div>
</div>