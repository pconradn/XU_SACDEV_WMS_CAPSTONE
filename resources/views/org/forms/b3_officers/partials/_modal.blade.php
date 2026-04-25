<div id="officerModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm">

    <div class="w-full max-w-3xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">

        <div class="flex items-start justify-between border-b border-slate-200 bg-gradient-to-b from-slate-50 to-white px-6 py-4">
            <div>
                <h2 id="officerModalTitle" class="text-sm font-semibold text-slate-900">
                    Add Officer
                </h2>
                <p class="mt-1 text-xs text-slate-500">
                    Fill in the officer details below.
                </p>
            </div>

            <button type="button"
                    id="cancelOfficerBtn"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-700">
                <span class="text-lg leading-none">&times;</span>
            </button>
        </div>

        <div class="max-h-[80vh] overflow-y-auto px-6 py-5">
            <div class="space-y-5">

                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="mb-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Officer Position
                        </div>
                    </div>

                    <label for="modal_position" class="block text-xs font-medium text-slate-600">
                        Position
                    </label>
                    <input id="modal_position"
                           type="text"
                           placeholder="Ex: Committee Head"
                           class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="mb-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Officer Name
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="modal_prefix" class="block text-xs font-medium text-slate-600">
                                Prefix
                            </label>
                            <input id="modal_prefix"
                                   type="text"
                                   placeholder="Ex: Mr., Engr."
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="modal_first" class="block text-xs font-medium text-slate-600">
                                First Name
                            </label>
                            <input id="modal_first"
                                   type="text"
                                   placeholder="First name"
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="modal_mi" class="block text-xs font-medium text-slate-600">
                                M.I.
                            </label>
                            <input id="modal_mi"
                                   type="text"
                                   maxlength="1"
                                   placeholder="M"
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="modal_last" class="block text-xs font-medium text-slate-600">
                                Last Name
                            </label>
                            <input id="modal_last"
                                   type="text"
                                   placeholder="Last name"
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="mb-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Student Information
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="modal_student_id" class="block text-xs font-medium text-slate-600">
                                Student ID
                            </label>
                            <input id="modal_student_id"
                                   type="text"
                                   placeholder="Ex: 202112345"
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="modal_course" class="block text-xs font-medium text-slate-600">
                                Course &amp; Year
                            </label>
                            <input id="modal_course"
                                   type="text"
                                   placeholder="Ex: BSIT 3"
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="mb-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Academic Performance
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label for="modal_first_qpi" class="block text-xs font-medium text-slate-600">
                                1st Sem QPI
                            </label>
                            <input id="modal_first_qpi"
                                   type="number"
                                   step="0.01"
                                   min="0"
                                   max="4"
                                   placeholder="0.00"
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="modal_second_qpi" class="block text-xs font-medium text-slate-600">
                                2nd Sem QPI
                            </label>
                            <input id="modal_second_qpi"
                                   type="number"
                                   step="0.01"
                                   min="0"
                                   max="4"
                                   placeholder="0.00"
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="modal_inter_qpi" class="block text-xs font-medium text-slate-600">
                                Intersession QPI
                            </label>
                            <input id="modal_inter_qpi"
                                   type="number"
                                   step="0.01"
                                   min="0"
                                   max="4"
                                   placeholder="0.00"
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="mb-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Contact Information
                        </div>
                    </div>

                    <label for="modal_mobile" class="block text-xs font-medium text-slate-600">
                        Mobile Number
                    </label>
                    <input id="modal_mobile"
                           type="text"
                           placeholder="Ex: 09123456789"
                           class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                </div>

            </div>
        </div>

        <div class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-6 py-4">
            <button type="button"
                    id="cancelOfficerBtnFooter"
                    class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                Cancel
            </button>

            <button type="button"
                    id="saveOfficerBtn"
                    class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">
                Save Officer
            </button>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('officerModal');
    if (!modal) return;

    const cancelTop = document.getElementById('cancelOfficerBtn');
    const cancelBottom = document.getElementById('cancelOfficerBtnFooter');

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            document.getElementById('modal_position')?.focus();
        }, 0);
    }

    cancelTop?.addEventListener('click', closeModal);
    cancelBottom?.addEventListener('click', closeModal);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    window.openOfficerModal = openModal;
    window.closeOfficerModal = closeModal;
});
</script>