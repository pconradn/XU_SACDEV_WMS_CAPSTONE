<div class="sticky bottom-0 z-40">

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm px-5 py-4">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            {{-- LEFT (HELP TEXT) --}}
            <div class="text-xs text-slate-500">
                Review selected items and details before generating the voucher.
            </div>


            {{-- RIGHT (ACTIONS) --}}
            <div class="flex items-center gap-3">

                {{-- CANCEL --}}
                <a href="{{ route('org.projects.documents.hub', $project) }}"
                   class="px-4 py-2 text-xs font-semibold rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 transition">
                    Cancel
                </a>


                {{-- GENERATE --}}
                <button type="submit"
                        class="px-5 py-2 text-xs font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition shadow-sm">
                    Generate Voucher
                </button>

            </div>

        </div>

    </div>

</div>