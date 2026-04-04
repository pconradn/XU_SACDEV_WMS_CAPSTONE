<x-app-layout>

@php
    $totalRequired = 8;
    $approved = 8;
    $progress = ($approved / $totalRequired) * 100;
@endphp

<div class="w-full space-y-4 p-4 text-sm bg-slate-50">

    {{-- HEADER --}}
    <div class="bg-white border rounded-2xl px-6 py-5 flex justify-between items-start shadow-sm">

        <div>
            <h1 class="text-lg font-semibold text-slate-900">
                Campus Website Development Initiative
            </h1>

            <div class="text-xs text-slate-500">
                Web Development Society • 2026–2027
            </div>

            <div class="text-xs text-slate-400 mt-1">
                Project Head: Jay Jacobs
            </div>
        </div>

        <div class="flex gap-2">
            <span class="text-[10px] px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full font-medium">
                Completed
            </span>
            <span class="text-[10px] px-3 py-1 bg-purple-100 text-purple-700 rounded-full font-medium">
                Off-Campus
            </span>
        </div>

    </div>


    {{-- GRID --}}
    <div class="grid grid-cols-12 gap-4">

        {{-- LEFT --}}
        <div class="col-span-12 lg:col-span-8 space-y-4">

            {{-- SNAPSHOT --}}
            <div class="bg-white border rounded-2xl p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-700 mb-3">
                    Project Snapshot
                </h2>

                <div class="grid grid-cols-3 text-xs">
                    <div>
                        <div class="text-slate-400">Date</div>
                        <div class="font-medium">Dec 06, 2026</div>
                    </div>
                    <div>
                        <div class="text-slate-400">Time</div>
                        <div class="font-medium">9:00 AM – 6:00 PM</div>
                    </div>
                    <div>
                        <div class="text-slate-400">Venue</div>
                        <div class="font-medium">Luxe Hotel</div>
                    </div>
                </div>
            </div>


            {{-- PROGRESS --}}
            <div class="bg-white border rounded-2xl p-5 shadow-sm">

                <div class="flex justify-between text-xs mb-2">
                    <span class="font-semibold text-slate-700">Workflow Progress</span>
                    <span>{{ round($progress) }}%</span>
                </div>

                <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-600"
                         style="width: {{ $progress }}%"></div>
                </div>

                <div class="text-[10px] text-slate-500 mt-2">
                    {{ $approved }} / {{ $totalRequired }} documents approved
                </div>

            </div>


<div class="bg-white border rounded-2xl shadow-sm overflow-hidden">

    <div class="px-5 py-3 border-b text-xs font-semibold">
        Project Documents
    </div>


    {{-- 🔴 ACTION REQUIRED --}}
    <div class="px-5 py-2 bg-rose-50 text-rose-700 text-[11px] font-semibold">
        Action Required
    </div>

    @for($i=1;$i<=2;$i++)
    <div class="flex justify-between items-center px-5 py-3 border-b hover:bg-rose-50/40 transition">

        <div class="flex items-start gap-2">
            <div class="w-2 h-2 mt-1 rounded-full bg-rose-500"></div>

            <div>
                <div class="text-xs font-medium">
                    Documentation Report {{ $i }}
                </div>

                <div class="text-[10px] text-slate-500 flex gap-2 flex-wrap">
                    <span>Waiting: SACDEV Admin</span>
                    <span>•</span>
                    <span class="text-rose-600 font-medium">Needs action</span>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="px-3 py-1 text-[10px] bg-slate-900 text-white rounded-lg hover:bg-slate-700">
                Review
            </button>
            <button class="px-3 py-1 text-[10px] bg-rose-500 text-white rounded-lg hover:bg-rose-600">
                Return
            </button>
        </div>

    </div>
    @endfor



    {{-- 🟡 REQUIRED --}}
    <div class="px-5 py-2 bg-amber-50 text-amber-700 text-[11px] font-semibold">
        Required Forms
    </div>

    @for($i=1;$i<=2;$i++)
    <div class="flex justify-between items-center px-5 py-3 border-b hover:bg-amber-50/40 transition">

        <div class="flex items-start gap-2">
            <div class="w-2 h-2 mt-1 rounded-full bg-amber-500"></div>

            <div>
                <div class="text-xs font-medium">
                    Liquidation Report {{ $i }}
                </div>

                <div class="text-[10px] text-slate-500">
                    Required but not yet submitted
                </div>
            </div>
        </div>

        <span class="text-[10px] px-2 py-1 bg-amber-100 text-amber-700 rounded-full">
            Required
        </span>

    </div>
    @endfor



    {{-- 🔵 SUBMITTED (OPTIONAL) --}}
    <div class="px-5 py-2 bg-blue-50 text-blue-700 text-[11px] font-semibold">
        Submitted (Optional)
    </div>

    @for($i=1;$i<=2;$i++)
    <div class="flex justify-between items-center px-5 py-3 border-b hover:bg-blue-50/40 transition">

        <div class="flex items-start gap-2">
            <div class="w-2 h-2 mt-1 rounded-full bg-blue-500"></div>

            <div>
                <div class="text-xs font-medium">
                    Selling Activity Report {{ $i }}
                </div>

                <div class="text-[10px] text-slate-500">
                    Submitted • Not required
                </div>
            </div>
        </div>

        <span class="text-[10px] px-2 py-1 bg-blue-100 text-blue-700 rounded-full">
            Submitted
        </span>

    </div>
    @endfor



    {{-- 🟢 APPROVED --}}
    <div class="px-5 py-2 bg-emerald-50 text-emerald-700 text-[11px] font-semibold">
        Approved
    </div>

    @for($i=1;$i<=3;$i++)
    <div class="flex justify-between items-center px-5 py-3 border-b hover:bg-emerald-50/40 transition">

        <div class="flex items-start gap-2">
            <div class="w-2 h-2 mt-1 rounded-full bg-emerald-500"></div>

            <div>
                <div class="text-xs font-medium">
                    Project Proposal {{ $i }}
                </div>

                <div class="text-[10px] text-slate-500">
                    Approved by SACDEV
                </div>
            </div>
        </div>

        <span class="text-[10px] px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full">
            Approved
        </span>

    </div>
    @endfor



    {{-- ⚪ OTHERS --}}
    <div class="px-5 py-2 bg-slate-50 text-slate-600 text-[11px] font-semibold">
        Other Forms
    </div>

    @for($i=1;$i<=2;$i++)
    <div class="flex justify-between items-center px-5 py-3 border-b hover:bg-slate-50 transition">

        <div class="flex items-start gap-2">
            <div class="w-2 h-2 mt-1 rounded-full bg-slate-300"></div>

            <div>
                <div class="text-xs font-medium">
                    Request to Purchase {{ $i }}
                </div>

                <div class="text-[10px] text-slate-400">
                    Not submitted
                </div>
            </div>
        </div>

        <span class="text-[10px] px-2 py-1 bg-slate-100 text-slate-500 rounded-full">
            Optional
        </span>

    </div>
    @endfor

</div>
        </div>


        {{-- RIGHT --}}
        <div class="col-span-12 lg:col-span-4 space-y-4 sticky top-4 h-fit">

            {{-- PRE IMPLEMENTATION --}}
            <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-3">

                <div class="flex justify-between">
                    <div>
                        <div class="text-sm font-semibold">
                            Pre-Implementation
                        </div>
                        <div class="text-[11px] text-slate-500">
                            Proposal & Budget review
                        </div>
                    </div>

                    <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                        Required
                    </span>
                </div>

                <button class="w-full border rounded-lg py-2 text-xs hover:bg-slate-50">
                    Open Combined Proposal
                </button>

                <div class="grid grid-cols-2 gap-2">
                    <button class="text-xs bg-slate-100 rounded-lg py-2 hover:bg-slate-200">
                        Print Proposal
                    </button>
                    <button class="text-xs bg-slate-100 rounded-lg py-2 hover:bg-slate-200">
                        Print Budget
                    </button>
                </div>

            </div>


            {{-- CLEARANCE --}}
            <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-4">

                <div class="flex justify-between items-center">
                    <div class="text-sm font-semibold">
                        Off-Campus Clearance
                    </div>

                    <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                        In Review
                    </span>
                </div>

                <div class="text-xs text-slate-500">
                    Reference:
                    <span class="font-mono text-blue-700 ml-1">
                        CL-2026-0003
                    </span>
                </div>

                {{-- TIMELINE --}}
                <div class="bg-slate-50 rounded-xl p-3 text-xs space-y-2">

                    <div class="flex justify-between">
                        <span>Requested</span>
                        <span class="text-emerald-600">✔</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Uploaded</span>
                        <span class="text-emerald-600">✔</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Verification</span>
                        <span class="text-blue-600 font-medium">In Progress</span>
                    </div>

                </div>

                {{-- ACTIONS --}}
                <div class="grid grid-cols-2 gap-2">

                    <button class="text-xs bg-emerald-600 text-white py-2 rounded-lg hover:bg-emerald-700 transition">
                        Verify
                    </button>

                    <button class="text-xs bg-rose-500 text-white py-2 rounded-lg hover:bg-rose-600 transition">
                        Return
                    </button>

                </div>

                <button class="w-full text-xs border rounded-lg py-2 hover:bg-slate-50">
                    View Uploaded Clearance
                </button>

            </div>


            {{-- PACKETS --}}
            <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-3">

                <div class="text-sm font-semibold">
                    External Packets
                </div>

                <div class="flex justify-between text-xs">
                    <span>OSA</span>
                    <span class="text-blue-600 font-medium">Processing</span>
                </div>

                <div class="flex justify-between text-xs">
                    <span>Finance</span>
                    <span class="text-emerald-600 font-medium">Submitted</span>
                </div>

                <button class="w-full text-xs border rounded-lg py-2 hover:bg-slate-50">
                    View Packets
                </button>

            </div>

        </div>

    </div>


    {{-- FLOATING BAR --}}
    <div class="fixed bottom-5 left-1/2 -translate-x-1/2 bg-white border shadow-xl rounded-full px-5 py-2 flex gap-2 text-xs">

        <button class="px-4 py-1 bg-slate-900 text-white rounded-full hover:bg-slate-700 transition">
            View Packets
        </button>

        <button class="px-4 py-1 bg-emerald-600 text-white rounded-full hover:bg-emerald-700 transition">
            Mark as Completed
        </button>

    </div>

</div>

</x-app-layout>