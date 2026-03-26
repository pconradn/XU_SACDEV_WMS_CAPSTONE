<div x-data="{ openPostpone:false, openCancel:false }"
     class="bg-white border rounded-2xl p-6 shadow-sm space-y-4">

    <h2 class="text-sm font-semibold text-slate-700">
        Action Center
    </h2>

    <div class="space-y-2">

        {{-- ===================== --}}
        {{-- DV (PRIMARY) --}}
        {{-- ===================== --}}
        @if($actions['can_generate_dv'])
            <a href="{{ $actions['dv_url'] }}"
               class="block w-full text-left px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg text-sm font-medium transition">
                Generate Disbursement Voucher
            </a>
        @endif


        {{-- ===================== --}}
        {{-- POSTPONEMENT --}}
        {{-- ===================== --}}
        @if($actions['postponement']['exists'])

            <a href="{{ $actions['postponement']['view_url'] }}"
               class="block w-full text-left px-4 py-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg text-sm font-medium transition">

                <div class="flex justify-between items-center">
                    <span>Notice of Postponement</span>
                    <span class="text-xs text-yellow-700 font-semibold">
                        View
                    </span>
                </div>

            </a>

        @elseif($actions['postponement']['can_create'])

            <button 
                @click="openPostpone = true"
                class="block w-full text-left px-4 py-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg text-sm font-medium transition">

                Create Notice of Postponement

            </button>

        @endif


        {{-- ===================== --}}
        {{-- CANCELLATION --}}
        {{-- ===================== --}}
        @if($actions['cancellation']['exists'])

            <a href="{{ $actions['cancellation']['view_url'] }}"
               class="block w-full text-left px-4 py-3 bg-red-50 hover:bg-red-100 rounded-lg text-sm font-medium transition">

                <div class="flex justify-between items-center">
                    <span>Notice of Cancellation</span>
                    <span class="text-xs text-red-700 font-semibold">
                        View
                    </span>
                </div>

            </a>

        @elseif($actions['cancellation']['can_create'])

            <button 
                @click="openCancel = true"
                class="block w-full text-left px-4 py-3 bg-red-50 hover:bg-red-100 rounded-lg text-sm font-medium transition">

                Create Notice of Cancellation

            </button>

        @endif


        {{-- ===================== --}}
        {{-- PACKETS --}}
        {{-- ===================== --}}
        <a href="{{ route('org.projects.packets.index', $project) }}"
           class="block w-full text-left px-4 py-3 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-medium transition">

            Open Packet Submissions

        </a>

    </div>


    {{-- ===================== --}}
    {{-- POSTPONEMENT MODAL --}}
    {{-- ===================== --}}
    <div x-show="openPostpone" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-black/40" @click="openPostpone=false"></div>

        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl p-6 space-y-4">

            <h3 class="text-lg font-semibold text-slate-800">
                Create Notice of Postponement
            </h3>

            <p class="text-sm text-slate-600">
                This form is used when the project needs to be moved to a different date.
                You will be required to provide a reason and updated schedule.
            </p>

            <div class="flex justify-end gap-2 pt-4">
                <button @click="openPostpone=false"
                    class="px-4 py-2 text-sm bg-slate-100 rounded-lg">
                    Cancel
                </button>

                <a href="{{ $actions['postponement']['create_url'] }}"
                   class="px-4 py-2 text-sm bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                    Proceed
                </a>
            </div>

        </div>
    </div>


    {{-- ===================== --}}
    {{-- CANCELLATION MODAL --}}
    {{-- ===================== --}}
    <div x-show="openCancel" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-black/40" @click="openCancel=false"></div>

        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl p-6 space-y-4">

            <h3 class="text-lg font-semibold text-slate-800">
                Create Notice of Cancellation
            </h3>

            <p class="text-sm text-slate-600">
                This will formally cancel the project. Once submitted and approved,
                the project workflow will be halted.
            </p>

            <div class="flex justify-end gap-2 pt-4">
                <button @click="openCancel=false"
                    class="px-4 py-2 text-sm bg-slate-100 rounded-lg">
                    Cancel
                </button>

                <a href="{{ $actions['cancellation']['create_url'] }}"
                   class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Proceed
                </a>
            </div>

        </div>
    </div>

</div>