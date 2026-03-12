<div id="officerModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">

            <h2 id="officerModalTitle"
                class="text-base font-semibold text-slate-900">
                Add Officer
            </h2>

            <button type="button"
                id="cancelOfficerBtn"
                class="text-slate-400 hover:text-slate-600 text-lg font-semibold">
                ×
            </button>

        </div>


        {{-- Body --}}
        <div class="px-5 py-4 space-y-4">

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Position
                </label>
                <input id="modal_position"
                    type="text"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring focus:ring-blue-200 focus:outline-none">
            </div>


            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Officer Name
                </label>
                <input id="modal_name"
                    type="text"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring focus:ring-blue-200 focus:outline-none">
            </div>


            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Student ID
                </label>
                <input id="modal_student_id"
                    type="text"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring focus:ring-blue-200 focus:outline-none">
            </div>


            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Course & Year
                </label>
                <input id="modal_course"
                    type="text"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring focus:ring-blue-200 focus:outline-none">
            </div>


            <div class="grid grid-cols-3 gap-3">

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        1st Sem QPI
                    </label>
                    <input id="modal_first_qpi"
                        type="number"
                        step="0.01"
                        min="0"
                        max="4"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring focus:ring-blue-200 focus:outline-none">
                </div>


                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        2nd Sem QPI
                    </label>
                    <input id="modal_second_qpi"
                        type="number"
                        step="0.01"
                        min="0"
                        max="4"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring focus:ring-blue-200 focus:outline-none">
                </div>


                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Intersession QPI
                    </label>
                    <input id="modal_inter_qpi"
                        type="number"
                        step="0.01"
                        min="0"
                        max="4"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring focus:ring-blue-200 focus:outline-none">
                </div>

            </div>


            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Mobile Number
                </label>
                <input id="modal_mobile"
                    type="text"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring focus:ring-blue-200 focus:outline-none">
            </div>

        </div>


        {{-- Footer --}}
        <div class="flex justify-end gap-2 px-5 py-4 border-t border-slate-200">

            <button type="button"
                id="cancelOfficerBtn"
                class="px-4 py-2 text-sm font-semibold text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200">
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