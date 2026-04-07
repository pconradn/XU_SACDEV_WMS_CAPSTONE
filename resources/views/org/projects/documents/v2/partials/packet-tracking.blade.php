<div class="bg-white border rounded-2xl p-5 space-y-4">

    <h2 class="text-sm font-semibold text-slate-800">
        Packet Submissionss
    </h2>

    {{-- OSA --}}
    <div class="flex justify-between items-center">

        <div>
            <div class="text-sm font-medium text-slate-700">
                OSA Packet
            </div>
            <div class="text-xs text-slate-500">
                Submitted documents for clearance
            </div>
        </div>

        <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700">
            {{ $project->osa_packet_status ?? 'Not Submitted' }}
        </span>
    </div>

    {{-- FINANCE --}}
    <div class="flex justify-between items-center">

        <div>
            <div class="text-sm font-medium text-slate-700">
                Finance Packet
            </div>
            <div class="text-xs text-slate-500">
                Liquidation & budget validationss
            </div>
        </div>

        <span class="text-xs px-2 py-1 rounded-full bg-emerald-50 text-emerald-700">
            {{ $project->finance_packet_status ?? 'Not Submitted' }}
        </span>
    </div>

</div>