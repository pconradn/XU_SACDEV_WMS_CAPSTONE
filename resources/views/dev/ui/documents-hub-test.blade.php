<x-app-layout>

    {{-- PAGE HEADER --}}
    <div class="mb-5 flex items-start justify-between">

        <div>
            <div class="text-lg font-semibold text-slate-900">
                Page Title
            </div>
            <div class="text-xs text-slate-500">
                Short description or guidance text
            </div>
        </div>

        <div class="flex gap-2">
            <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                Add
            </button>
        </div>

    </div>

    <div class="grid grid-cols-12 gap-4">

        {{-- LEFT CONTENT --}}
        <div class="col-span-12 lg:col-span-8 space-y-4">

            {{-- MAIN CARD --}}
            <div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-4 space-y-3">

                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-700">
                            Section Header
                        </div>
                        <div class="text-[11px] text-slate-500">
                            Supporting description
                        </div>
                    </div>

                    <i data-lucide="layout-list" class="w-4 h-4 text-slate-400"></i>
                </div>

                <div class="divide-y">

                    {{-- ROW --}}
                    <div class="py-3 flex items-center justify-between hover:bg-slate-50 px-2 rounded-lg transition">

                        <div>
                            <div class="text-xs font-semibold text-slate-800">
                                Item Title
                            </div>
                            <div class="text-[11px] text-slate-500">
                                Small description
                            </div>
                        </div>

                        <div class="flex items-center gap-2">

                            {{-- STATUS BADGE --}}
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold">
                                Approved
                            </span>

                            <button class="text-xs px-2 py-1 rounded-md border border-slate-200 hover:bg-slate-50">
                                View
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- RIGHT SIDE (ACTIONS / STATUS) --}}
        <div class="col-span-12 lg:col-span-4 space-y-4">

            {{-- STATUS CARD --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4 space-y-3">

                <div class="text-xs font-semibold text-slate-700">
                    Status Overview
                </div>

                <div class="space-y-2">

                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">Progress</span>
                        <span class="font-semibold text-slate-800">70%</span>
                    </div>

                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 w-[70%]"></div>
                    </div>

                </div>

                <div class="text-[11px] text-slate-500">
                    Last updated 2 hours ago
                </div>

            </div>

            {{-- ACTION CARD --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4 space-y-3">

                <div class="text-xs font-semibold text-slate-700">
                    Actions
                </div>

                <div class="flex flex-col gap-2">

                    <button class="w-full flex items-center justify-center gap-1 px-3 py-2 text-xs font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                        Approve
                    </button>

                    <button class="w-full flex items-center justify-center gap-1 px-3 py-2 text-xs font-semibold rounded-lg border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100 transition">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        Return
                    </button>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>