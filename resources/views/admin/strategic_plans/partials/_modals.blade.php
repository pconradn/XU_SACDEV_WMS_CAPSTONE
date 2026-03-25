<div x-show="openRevert" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-slate-900/50" @click="openRevert=false"></div>

    <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl p-5">

        <h3 class="text-lg font-semibold text-slate-900">
            Revert Strategic Plan
        </h3>

        <p class="text-sm text-slate-600 mt-1">
            This will revert the plan back to draft state.
        </p>

        <form method="POST"
              action="{{ route('admin.strategic_plans.revert_approval', $submission) }}"
              class="mt-4 space-y-3">

            @csrf

            <textarea name="remarks" rows="3"
                class="w-full rounded-lg border-slate-200"
                placeholder="Optional remarks..."></textarea>

            <div class="flex justify-end gap-2">
                <button type="button"
                        @click="openRevert=false"
                        class="rounded-lg border px-4 py-2 text-sm">
                    Cancel
                </button>

                <button type="submit"
                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm text-white">
                    Confirm Revert
                </button>
            </div>

        </form>

    </div>
</div>