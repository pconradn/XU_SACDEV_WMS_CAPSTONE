<div id="itemsModal"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl">

        {{-- HEADER --}}
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-sm font-semibold text-slate-800">
                Manage Solicitation Entries
            </h3>

            <button onclick="closeModal('itemsModal')" class="text-slate-500 text-xs">
                Close
            </button>
        </div>

        {{-- BODY --}}
        <div class="p-6 max-h-[70vh] overflow-y-auto">

            @include('org.projects.documents.solicitation-sponsorship-report.partials._items-table')

        </div>

        {{-- FOOTER --}}
        <div class="px-6 py-4 border-t flex justify-end">
            <button onclick="closeModal('itemsModal')"
                class="px-4 py-2 text-xs bg-blue-600 text-white rounded-lg">
                Done
            </button>
        </div>

    </div>
</div>