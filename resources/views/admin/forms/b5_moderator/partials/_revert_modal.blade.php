<div x-show="openRevert"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-black/40" @click="openRevert=false"></div>

    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border p-6">

        <h3 class="text-lg font-semibold text-slate-900">
            Revert Approval
        </h3>

        <p class="text-sm text-slate-600 mt-2">
            This will move the submission back to review state.
        </p>
        {{-- 
        <form method="POST"
              action="{{ route('admin.moderator_submissions.revert', $submission) }}"
              class="mt-6 flex justify-end gap-2">

            @csrf

            <button type="button"
                    @click="openRevert=false"
                    class="px-4 py-2 text-sm border rounded-lg">
                Cancel
            </button>

            <button type="submit"
                    class="px-4 py-2 text-sm bg-slate-900 text-white rounded-lg hover:bg-slate-800">
                Confirm Revert
            </button>

        </form>
        --}}

    </div>
</div>