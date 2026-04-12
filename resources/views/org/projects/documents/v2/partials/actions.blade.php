@php
    $projectHeadAssignment = \App\Models\ProjectAssignment::with('user')
        ->where('project_id', $project->id)
        ->where('assignment_role', 'project_head')
        ->whereNull('archived_at')
        ->first();

    $isProjectHead = optional($projectHeadAssignment)->user_id === auth()->id();
@endphp

<div x-data="{ openPostpone:false, openCancel:false }"
     class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm p-5 space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="zap" class="w-4 h-4 text-slate-500"></i>
            <h2 class="text-xs font-semibold text-slate-700">
                Action Center
            </h2>
        </div>
    </div>


    {{-- ================= PRIMARY ACTIONS ================= --}}
    <div class="space-y-2">

        <div class="text-[10px] uppercase tracking-wide text-slate-400">
            Primary
        </div>

        {{-- VIEW PROPOSAL --}}
        <a href="{{ route('org.projects.documents.combined-proposal.create', $project) }}"
           class="group flex items-center justify-between px-4 py-3 rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition shadow-sm">

            <div class="flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span class="text-xs font-semibold">View Proposal</span>
            </div>

            <div class="flex items-center gap-2">

                @if(($actions['proposal_status'] ?? null) === 'approved_by_sacdev')
                    <span class="text-[10px] px-2 py-0.5 bg-emerald-500/20 text-emerald-300 rounded font-semibold">
                        Approved
                    </span>
                @endif

                <span class="text-[10px] opacity-80 flex items-center gap-1">
                    Open
                    
                </span>
            </div>
        </a>


        {{-- AGREEMENT --}}
        <button 
            @click="openAgreement = true"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-sm">

            <div class="flex items-center gap-2">
                <i data-lucide="file-signature" class="w-4 h-4"></i>
                <span class="text-xs font-semibold">
                    {{ $needsAgreement ? 'Complete Agreement' : 'View Agreement' }}
                </span>
            </div>

            <span class="text-[10px] opacity-90">
                {{ $needsAgreement ? 'Required' : 'Open' }}
            </span>
        </button>


        
        <a href="{{ route('org.projects.packets.index', $project) }}"
        class="flex items-center justify-between px-4 py-3 rounded-xl border border-amber-200 bg-gradient-to-r from-amber-50 to-white hover:from-amber-100 hover:to-white transition shadow-sm">

            <div class="flex items-center gap-2">
                <i data-lucide="package" class="w-4 h-4 text-amber-600"></i>

                <span class="text-xs font-semibold text-slate-800">
                    Org Packet Submissions
                </span>
            </div>

            <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">
                Open
            </span>

        </a>

    </div>


    {{-- ================= PROJECT HEAD ONLY ================= --}}
    @if($isProjectHead)

    {{-- ================= SECONDARY ACTIONS ================= --}}
    <div class="space-y-2">

        <div class="text-[10px] uppercase tracking-wide text-slate-400">
            Documents
        </div>

        @if($actions['can_generate_dv'])
            <a href="{{ $actions['dv_url'] }}"
               class="flex items-center justify-between px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition">

                <div class="flex items-center gap-2 text-blue-700">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                    <span class="text-xs font-medium">Disbursement Voucher</span>
                </div>

                <span class="text-[10px] font-semibold text-blue-700">
                    Generate
                </span>
            </a>
        @endif


        @if($actions['travel_form']['can_create'])
            <a href="{{ $actions['travel_form']['create_url'] }}"
               class="flex items-center justify-between px-3 py-2 bg-blue-50 hover:bg-blue-100 rounded-lg transition">

                <div class="flex items-center gap-2 text-blue-700">
                    <i data-lucide="plane" class="w-4 h-4"></i>
                    <span class="text-xs font-medium">Student Travel Agreement</span>
                </div>

                <span class="text-[10px] font-semibold text-blue-700">
                    Generate
                </span>
            </a>
        @endif

    </div>


    {{-- ================= NOTICE ACTIONS ================= --}}
    <div class="space-y-2">

        <div class="text-[10px] uppercase tracking-wide text-slate-400">
            Notices
        </div>

        {{-- POSTPONEMENT --}}
        @if($actions['postponement']['exists'])

            @if($actions['postponement']['is_approved'] && !$actions['is_locked'])

                <button 
                    @click="openPostpone = true"
                    class="w-full flex items-center gap-2 px-3 py-2 bg-amber-50 hover:bg-amber-100 rounded-lg transition">

                    <i data-lucide="plus-circle" class="w-4 h-4 text-amber-600"></i>
                    <span class="text-xs font-medium text-amber-800">
                        New Postponement
                    </span>
                </button>

            @else

                <a href="{{ $actions['postponement']['view_url'] }}"
                class="flex items-center justify-between px-3 py-2 bg-amber-50 hover:bg-amber-100 rounded-lg transition">

                    <div class="flex items-center gap-2 text-amber-700">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                        <span class="text-xs font-medium">Postponement</span>
                    </div>

                    <span class="text-[10px] font-semibold text-amber-700">
                        View
                    </span>
                </a>

            @endif

        @elseif($actions['postponement']['can_create'])

            <button 
                @click="openPostpone = true"
                class="w-full flex items-center gap-2 px-3 py-2 bg-amber-50 hover:bg-amber-100 rounded-lg transition">

                <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                <span class="text-xs font-medium text-amber-800">
                    Create Postponement
                </span>
            </button>

        @endif


        {{-- CANCELLATION --}}
        @if($actions['cancellation']['exists'])

            <a href="{{ $actions['cancellation']['view_url'] }}"
            class="flex items-center justify-between px-3 py-2 bg-rose-50 hover:bg-rose-100 rounded-lg transition">

                <div class="flex items-center gap-2 text-rose-700">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                    <span class="text-xs font-medium">Cancellation</span>
                </div>

                <span class="text-[10px] font-semibold text-rose-700">
                    View
                </span>
            </a>

        @elseif($actions['cancellation']['can_create'])

            <button 
                @click="openCancel = true"
                class="w-full flex items-center gap-2 px-3 py-2 bg-rose-50 hover:bg-rose-100 rounded-lg transition">

                <i data-lucide="x-circle" class="w-4 h-4 text-rose-600"></i>
                <span class="text-xs font-medium text-rose-800">
                    Create Cancellation
                </span>
            </button>

        @endif

    </div>

    @endif


    {{-- ================= MODALS ================= --}}
    <div x-show="openPostpone" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" @click="openPostpone=false"></div>

        <div class="relative w-full max-w-md bg-white rounded-xl shadow-xl p-5 space-y-3">
            <h3 class="text-sm font-semibold text-slate-800">Create Postponement</h3>
            <p class="text-xs text-slate-600">Move your project to another date.</p>

            <div class="flex justify-end gap-2 pt-3">
                <button @click="openPostpone=false"
                    class="px-3 py-1 text-xs bg-slate-100 rounded">
                    Cancel
                </button>

                <a href="{{ $actions['postponement']['create_url'] }}"
                   class="px-3 py-1 text-xs bg-amber-600 text-white rounded hover:bg-amber-700">
                    Proceed
                </a>
            </div>
        </div>
    </div>


    <div x-show="openCancel" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" @click="openCancel=false"></div>

        <div class="relative w-full max-w-md bg-white rounded-xl shadow-xl p-5 space-y-3">
            <h3 class="text-sm font-semibold text-slate-800">Create Cancellation</h3>
            <p class="text-xs text-slate-600">This will stop the project workflow.</p>

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