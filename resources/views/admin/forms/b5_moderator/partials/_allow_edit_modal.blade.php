<div x-show="openAllowEdit"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-black/40" @click="openAllowEdit=false"></div>

    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border p-6">

        <h3 class="text-lg font-semibold text-slate-900">
            Allow Edit
        </h3>

        <p class="text-sm text-slate-600 mt-1">
            You may provide an optional note before allowing edits.
        </p>

        <form method="POST"
              action="{{ route('admin.moderator_submissions.allow_edit', $submission) }}"
              class="mt-4 space-y-4">

            @csrf

            <textarea name="sacdev_remarks"
                      rows="4"
                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                      placeholder="Optional note..."></textarea>

            <div class="flex justify-end gap-2">

                <button type="button"
                        @click="openAllowEdit=false"
                        class="px-4 py-2 text-sm border rounded-lg">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 text-sm bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                    Allow Edit
                </button>

            </div>

        </form>

    </div>
</div>