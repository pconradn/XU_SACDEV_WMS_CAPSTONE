<div id="officerModal"
class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl mx-4 border border-slate-200">

        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50 rounded-t-2xl">

            <div>
                <h2 id="officerModalTitle"
                    class="text-base font-semibold text-slate-900">
                    Add Officer
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Fill in officer details
                </p>
            </div>

            <button type="button"
                id="cancelOfficerBtn"
                class="text-slate-400 hover:text-slate-600 text-xl leading-none">
                ×
            </button>

        </div>

        <div class="px-6 py-5 space-y-5">

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">
                    Position
                </label>
                <input id="modal_position"
                    type="text"
                    placeholder="Ex: Committee Head"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">
                    Officer Name
                </label>
                <input id="modal_name"
                    type="text"
                    placeholder="Full name"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">
                    Student ID
                </label>
                <input id="modal_student_id"
                    type="text"
                    inputmode="numeric"
                    pattern="\d*"
                    placeholder="Ex: 202112345"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">
                    Course & Year
                </label>
                <input id="modal_course"
                    type="text"
                    placeholder="Ex: BSIT 3"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-3 gap-3">

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        1st Sem QPI
                    </label>
                    <input id="modal_first_qpi"
                        type="number"
                        step="0.01"
                        min="0"
                        max="4"
                        placeholder="0.00"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                               focus:ring-2 focus:ring-blue-200 focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        2nd Sem QPI
                    </label>
                    <input id="modal_second_qpi"
                        type="number"
                        step="0.01"
                        min="0"
                        max="4"
                        placeholder="0.00"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                               focus:ring-2 focus:ring-blue-200 focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">
                        Intersession QPI
                    </label>
                    <input id="modal_inter_qpi"
                        type="number"
                        step="0.01"
                        min="0"
                        max="4"
                        placeholder="0.00"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                               focus:ring-2 focus:ring-blue-200 focus:border-blue-500 focus:outline-none">
                </div>

            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">
                    Mobile Number
                </label>
                <input id="modal_mobile"
                    type="text"
                    placeholder="Ex: 09123456789"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-500 focus:outline-none">
            </div>

        </div>

        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-200 bg-slate-50 rounded-b-2xl">

            <button type="button"
                id="cancelOfficerBtnFooter"
                class="px-4 py-2 text-sm font-medium text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-100">
                Cancel
            </button>

            <button type="button"
                id="saveOfficerBtn"
                class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                Save Officer
            </button>

        </div>

    </div>

</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('officerModal');

    const closeModal = () => {
        modal.classList.add('hidden');
    };

    document.getElementById('cancelOfficerBtn')?.addEventListener('click', closeModal);
    document.getElementById('cancelOfficerBtnFooter')?.addEventListener('click', closeModal);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});
</script>