{{-- RETURN MODAL --}}
<div x-show="openReturn" x-cloak
     x-data="{ quillReturn: null }"
     x-init="
        $watch('openReturn', value => {
            if (value) {
                $nextTick(() => {
                    if (!quillReturn) {
                        quillReturn = new Quill($refs.returnEditor, {
                            theme: 'snow',
                            placeholder: 'Enter required changes...',
                            modules: {
                                toolbar: [
                                    ['bold', 'italic', 'underline'],
                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                    ['link'],
                                    ['clean']
                                ]
                            }
                        });
                    }

                    quillReturn.focus();
                });
            }
        })
     "
     class="fixed inset-0 z-50 flex items-center justify-center p-4">

    {{-- BACKDROP --}}
    <div class="absolute inset-0 bg-slate-900/50" @click="openReturn=false"></div>

    {{-- MODAL --}}
    <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-xl">

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b">
            <h3 class="text-lg font-semibold text-slate-900">
                Return to Organization
            </h3>
            <p class="text-sm text-slate-600 mt-1">
                This will allow the president to edit again. Remarks are required.
            </p>
        </div>

        {{-- FORM --}}
        <form class="p-5 space-y-4"
              method="POST"
              action="{{ route('admin.strategic_plans.return', $submission) }}"
              @submit="
                if (!quillReturn.getText().trim()) {
                    alert('Remarks are required.');
                    return false;
                }
                $refs.returnInput.value = quillReturn.root.innerHTML;
              ">

            @csrf

            {{-- RICH TEXT --}}
            <div>
                <label class="text-sm font-medium text-slate-700">
                    Remarks
                </label>

                <div x-ref="returnEditor"
                     class="mt-2 bg-white border border-slate-200 rounded-lg min-h-[140px]"></div>

                {{-- hidden input --}}
                <input type="hidden" name="remarks" x-ref="returnInput">
            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-2 pt-2">
                <button type="button"
                        class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                        @click="openReturn=false">
                    Cancel
                </button>

                <button type="submit"
                        class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                    Return
                </button>
            </div>

        </form>

    </div>
</div>