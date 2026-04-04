<div x-data="{ openPostpone:false, openCancel:false }"
     class="bg-white border rounded-xl p-4 shadow-sm space-y-4">

    <h2 class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
        Action Center
    </h2>

    <div class="space-y-2 text-xs">

        {{-- ================= PRIMARY ACTIONS ================= --}}
        
        {{-- VIEW PROPOSAL --}}
        <a href="{{ $actions['proposal_url'] ?? '#' }}"
           class="group flex items-center justify-between px-3 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-800 transition">

            <div class="flex items-center gap-2">
                <span>📄</span>
                <span class="font-medium">View Proposal</span>
            </div>

            <div class="flex items-center gap-2">

                {{-- APPROVED INDICATOR --}}
                @if(($actions['proposal_status'] ?? null) === 'approved_by_sacdev')
                    <span class="text-[10px] px-2 py-0.5 bg-emerald-500/20 text-emerald-300 rounded">
                        Approved
                    </span>
                @endif

                <span class="text-[10px] opacity-80">Open</span>
            </div>
        </a>


        {{-- VIEW AGREEMENT --}}
        <button 
            @click="openAgreement = true"
            class="w-full flex items-center justify-between px-3 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">

            <div class="flex items-center gap-2">
                <span>📝</span>
                <span class="font-medium">
                    {{ $needsAgreement ? 'Complete Agreement' : 'View Agreement' }}
                </span>
            </div>

            <span class="text-[10px] opacity-80">
                {{ $needsAgreement ? 'Required' : 'Open' }}
            </span>
        </button>


        {{-- ================= SECONDARY ACTIONS ================= --}}

        @if($actions['can_generate_dv'])
            <a href="{{ $actions['dv_url'] }}"
               class="flex items-center justify-between px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg font-medium transition">

                <div class="flex items-center gap-2">
                    <span>💰</span>
                    <span>Disbursement Voucher</span>
                </div>

                <span class="text-[10px] text-blue-700 font-semibold" style="text-align:right">
                    Generate
                </span>
                
            </a>
        @endif


        {{-- ================= TRAVEL FORM (CONDITIONAL) ================= --}}
        @if($actions['travel_form']['can_create'])
            <a href="{{ $actions['travel_form']['create_url'] }}"
               class="flex items-center justify-between px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg font-medium transition">

                <div class="flex items-center gap-2">
                    <span>✈</span>
                    <span>Travel Consent Form</span>
                </div>

                <span class="text-[10px] text-blue-700 font-semibold">
                    Generate
                </span>
            </a>
        @endif


        {{-- ================= NOTICE ACTIONS ================= --}}

        @if($actions['postponement']['exists'])

            {{-- IF APPROVED → ONLY CREATE NEW --}}
            @if($actions['postponement']['is_approved'] && !$actions['is_locked'])

                <button 
                    @click="openPostpone = true"
                    class="w-full flex items-center gap-2 px-3 py-2 bg-amber-50 hover:bg-amber-100 rounded-lg font-medium transition">

                    <span>➕</span>
                    <span>Create New Postponement</span>
                </button>

            {{-- IF NOT APPROVED → SHOW VIEW --}}
            @else

                <a href="{{ $actions['postponement']['view_url'] }}"
                class="flex items-center justify-between px-3 py-2 bg-yellow-50 hover:bg-yellow-100 rounded-lg font-medium transition">

                    <div class="flex items-center gap-2">
                        <span>⏳</span>
                        <span>Notice of Postponement</span>
                    </div>

                    <span class="text-[10px] text-yellow-700 font-semibold">
                        View
                    </span>
                </a>

            @endif

        @elseif($actions['postponement']['can_create'])

            <button 
                @click="openPostpone = true"
                class="w-full flex items-center gap-2 px-3 py-2 bg-yellow-50 hover:bg-yellow-100 rounded-lg font-medium transition">

                <span>⏳</span>
                <span>Create Notice of Postponement</span>
            </button>

        @endif

        {{-- ================= CANCELLATION ================= --}}
        @if($actions['cancellation']['exists'])

            <a href="{{ $actions['cancellation']['view_url'] }}"
            class="flex items-center justify-between px-3 py-2 bg-rose-50 hover:bg-rose-100 rounded-lg font-medium transition">

                <div class="flex items-center gap-2">
                    <span>❌</span>
                    <span>Notice of Cancellation</span>
                </div>

                <span class="text-[10px] text-rose-700 font-semibold">
                    View
                </span>
            </a>

        @elseif($actions['cancellation']['can_create'])

            <button 
                @click="openCancel = true"
                class="w-full flex items-center gap-2 px-3 py-2 bg-rose-50 hover:bg-rose-100 rounded-lg font-medium transition">

                <span>❌</span>
                <span>Create Notice of Cancellation</span>
            </button>

        @endif


        {{-- ================= SYSTEM ================= --}}
        <a href="{{ route('org.projects.packets.index', $project) }}"
           class="flex items-center gap-2 px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition">

            <span>📦</span>
            <span>Packet Submissions</span>
        </a>

    </div>


    {{-- ================= POSTPONEMENT MODAL ================= --}}
    <div x-show="openPostpone" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" @click="openPostpone=false"></div>

        <div class="relative w-full max-w-md bg-white rounded-xl shadow-xl p-5 space-y-3">

            <h3 class="text-base font-semibold text-slate-800">
                Create Notice of Postponement
            </h3>

            <p class="text-xs text-slate-600">
                Move your project to a different date with proper justification.
            </p>

            <div class="flex justify-end gap-2 pt-3">
                <button @click="openPostpone=false"
                    class="px-3 py-1 text-xs bg-slate-100 rounded">
                    Cancel
                </button>

                <a href="{{ $actions['postponement']['create_url'] }}"
                   class="px-3 py-1 text-xs bg-yellow-600 text-white rounded hover:bg-yellow-700">
                    Proceed
                </a>
            </div>

        </div>
    </div>


    {{-- ================= CANCELLATION MODAL ================= --}}
    <div x-show="openCancel" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" @click="openCancel=false"></div>

        <div class="relative w-full max-w-md bg-white rounded-xl shadow-xl p-5 space-y-3">

            <h3 class="text-base font-semibold text-slate-800">
                Create Notice of Cancellation
            </h3>

            <p class="text-xs text-slate-600">
                This will cancel the project and stop its workflow.
            </p>

            <div class="flex justify-end gap-2 pt-3">
                <button @click="openCancel=false"
                    class="px-3 py-1 text-xs bg-slate-100 rounded">
                    Cancel
                </button>

                <a href="{{ $actions['cancellation']['create_url'] }}"
                   class="px-3 py-1 text-xs bg-rose-600 text-white rounded hover:bg-rose-700">
                    Proceed
                </a>
            </div>

        </div>
    </div>

</div>