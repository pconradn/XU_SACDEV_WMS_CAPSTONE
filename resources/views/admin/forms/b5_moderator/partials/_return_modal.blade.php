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
                  class="px-6 py-5 space-y-4"
                  x-data="{ quillInstance: null }"
                  x-init="$nextTick(() => {
                      quillInstance = initQuillEditor('b5-return-editor');
                  })">

                @csrf

                {{-- LABEL --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Remarks <span class="text-red-500">*</span>
                    </label>

                    <div id="b5-return-editor"
                         class="bg-white border border-slate-300 rounded-lg min-h-[180px]"></div>

                    <input type="hidden" name="sacdev_remarks" id="b5ReturnRemarks">

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
                            class="px-4 py-2 text-sm font-semibold text-white bg-amber-600 rounded-lg hover:bg-amber-700 transition">
                        Return Submission
                    </button>

                </div>

            </form>

        </div>
    </div>
</template>

<script>
document.addEventListener('submit', function (e) {

    if (e.target.id === 'b5ReturnForm') {

        const editor = document.querySelector('#b5-return-editor .ql-editor');
        const input = document.getElementById('b5ReturnRemarks');

        if (editor && input) {
            input.value = editor.innerHTML;
        }

        const text = input.value
            .replace(/<(.|\n)*?>/g, '')
            .replace(/&nbsp;/g, ' ')
            .trim();

        if (!text) {
            e.preventDefault();
            alert('Please enter remarks.');
        }
    }

});
</script>