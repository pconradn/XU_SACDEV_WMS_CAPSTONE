<div x-show="openReturn"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-black/40" @click="openReturn=false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl border p-6">

        <h3 class="text-lg font-semibold text-slate-900">
            Return to Organization
        </h3>

        <p class="text-sm text-slate-600 mt-1">
            Provide remarks explaining what needs correction.
        </p>

        <form method="POST"
              action="{{ route('admin.b2.president.return', $registration) }}"
              class="mt-4 space-y-4">

            @csrf

            {{-- QUILL --}}
            <div>
                <div id="return-editor" class="bg-white border rounded-lg"></div>
                <input type="hidden" name="sacdev_remarks" id="returnRemarksInput">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button"
                        @click="openReturn=false"
                        class="px-4 py-2 text-sm border rounded-lg">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 text-sm bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                    Return Submission
                </button>
            </div>

        </form>

    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const quill = new Quill('#return-editor', {
        theme: 'snow'
    });

    const form = document.querySelector('form[action*="return"]');

    if (form) {
        form.addEventListener('submit', function () {
            document.getElementById('returnRemarksInput').value = quill.root.innerHTML;
        });
    }

});
</script>