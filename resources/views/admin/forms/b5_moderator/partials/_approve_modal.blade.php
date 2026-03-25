<div x-show="openApprove"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    <div class="absolute inset-0 bg-black/40" @click="openApprove=false"></div>

    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border p-6">

        <h3 class="text-lg font-semibold text-slate-900">
            Approve Submission
        </h3>

        <p class="text-sm text-slate-600 mt-2">
            Are you sure you want to approve this moderator submission?
        </p>

        <div class="flex justify-end gap-2 mt-6">

            <button type="button"
                    @click="openApprove=false"
                    class="px-4 py-2 text-sm border rounded-lg">
                Cancel
            </button>

            <button type="button"
                    onclick="document.getElementById('approveForm').submit()"
                    class="px-4 py-2 text-sm bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                Confirm Approval
            </button>

        </div>

    </div>
</div>