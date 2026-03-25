<div class="bg-white border rounded-2xl p-6 shadow-sm space-y-4">

    <h2 class="text-sm font-semibold text-slate-700">
        Action Center
    </h2>

    <div class="space-y-2">

        {{-- DV --}}
        @if($actions['can_generate_dv'])
            <a href="{{ $actions['dv_url'] }}"
               class="block w-full text-left px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg text-sm font-medium">
                Generate Disbursement Voucher
            </a>
        @endif


        {{-- POSTPONEMENT --}}
        @if($actions['can_postpone'])
            <button class="w-full text-left px-4 py-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg text-sm font-medium">
                Create Notice of Postponement
            </button>
        @endif


        {{-- CANCELLATION --}}
        @if($actions['can_cancel'])
            <button class="w-full text-left px-4 py-3 bg-red-50 hover:bg-red-100 rounded-lg text-sm font-medium">
                Create Notice of Cancellation
            </button>
        @endif


        {{-- PACKETS --}}
        <a href="{{ route('org.projects.packets.index', $project) }}"
           class="block w-full text-left px-4 py-3 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-medium">
            Open Packet Submissions
        </a>

    </div>

</div>