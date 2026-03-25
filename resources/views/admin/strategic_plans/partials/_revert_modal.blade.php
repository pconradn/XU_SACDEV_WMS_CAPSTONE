{{-- REVERT MODAL --}}
<div x-show="openRevert" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-slate-900/50" @click="openRevert=false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl p-5">

        <h3 class="text-lg font-semibold text-slate-900">
            Revert Approval
        </h3>

        <p class="text-sm text-slate-600 mt-1">
            This will move the submission back to SACDEV review. Remarks are required.
        </p>

        <form method="POST"
              action="{{ route('admin.strategic_plans.revert_approval', $submission) }}"
              class="mt-4 space-y-3"
              onsubmit="syncRevertRemarks()">

            @csrf

            {{-- HIDDEN INPUT --}}
            <input type="hidden" name="remarks" id="revertRemarksInput">

            {{-- QUILL EDITOR --}}
            <div id="revertQuill"
                 class="bg-white border border-slate-200 rounded-lg">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button"
                        @click="openRevert=false"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Cancel
                </button>

                <button type="submit"
                        class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-700">
                    Revert
                </button>
            </div>

        </form>

    </div>
</div>
<script>
    let revertQuill;

    document.addEventListener("DOMContentLoaded", function () {

        revertQuill = new Quill('#revertQuill', {
            theme: 'snow',
            placeholder: 'Enter reason for reverting...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

    });

    function syncRevertRemarks() {
        const html = revertQuill.root.innerHTML;
        document.getElementById('revertRemarksInput').value = html;
    }
</script>