<div 
    x-data="{
        showRequireModal: false,
        showRetractModal: false
    }"
    class="w-full"
>

@php
    $isCoa = auth()->user()?->is_coa_officer ?? false;

    $status = $project->clearance_status;

    $cardStyles = match(true) {
        !$project->requires_clearance => 'border-slate-200 bg-white',
        $status === 'uploaded' => 'border-purple-300 border-l-4 border-l-purple-400 bg-gradient-to-br from-purple-50 to-white',
        $status === 'verified' => 'border-emerald-300 bg-gradient-to-br from-emerald-50 to-white',
        $status === 'rejected' => 'border-rose-300 bg-gradient-to-br from-rose-50 to-white',
        default => 'border-slate-200 bg-white'
    };

    $statusLabel = match($status) {
        'required' => ['Waiting for upload', 'bg-amber-100 text-amber-700'],
        'uploaded' => ['Uploaded', 'bg-purple-100 text-purple-700'],
        'verified' => ['Verified', 'bg-emerald-100 text-emerald-700'],
        'rejected' => ['Returned', 'bg-rose-100 text-rose-700'],
        default => ['—', 'bg-slate-100 text-slate-500']
    };
@endphp


<div class="rounded-2xl border shadow-sm p-4 space-y-4 {{ $cardStyles }}">

    {{-- HEADER --}}
    <div class="flex items-start justify-between gap-4">

        <div>
            <div class="text-[10px] uppercase tracking-wide text-slate-500">
                Off-Campus Clearance
            </div>

            <div class="mt-1 text-sm font-semibold text-slate-900">
                @if(!$project->requires_clearance)
                    Not Required
                @else
                    Reference:
                    <span class="font-mono text-purple-700">
                        {{ $project->clearance_reference }}
                    </span>
                @endif
            </div>
        </div>


        {{-- ACTION --}}
        <div class="flex items-center gap-2">

            {{-- REQUIRE --}}
            @if(!$project->requires_clearance && !$isCoa)

                <button
                    @click="showRequireModal = true"
                    class="px-3 py-2 text-xs font-semibold rounded-xl 
                           bg-gradient-to-r from-purple-600 to-purple-500 text-white 
                           hover:from-purple-700 hover:to-purple-600 transition shadow-sm">
                    Require Clearance
                </button>

            {{-- RETRACT --}}
            @elseif($project->requires_clearance && !$isCoa)

                <button
                    @click="showRetractModal = true"
                    class="px-3 py-2 text-xs font-semibold rounded-xl 
                           bg-gradient-to-r from-rose-600 to-rose-500 text-white 
                           hover:from-rose-700 hover:to-rose-600 transition shadow-sm">
                    Undo Requirement
                </button>

            {{-- COA BLOCK --}}
            @else

                <button disabled
                        class="px-3 py-2 text-xs font-semibold rounded-xl 
                               bg-slate-200 text-slate-500 cursor-not-allowed">
                    Restricted
                </button>

            @endif

        </div>

    </div>


    {{-- STATUS --}}
    @if($project->requires_clearance)

        <div class="flex items-center justify-between border-t border-slate-100 pt-3">
            <div class="text-[11px] text-slate-500">
                Status
            </div>

            <span class="px-2.5 py-1 text-[10px] font-semibold rounded-full {{ $statusLabel[1] }}">
                {{ $statusLabel[0] }}
            </span>
        </div>

    @endif


    {{-- ACTIONS --}}
    @if($project->requires_clearance && $status === 'uploaded' && !$isCoa)

        <div class="flex gap-2">

            <form method="POST"
                  action="{{ route('admin.projects.clearance.verify', $project) }}"
                  class="flex-1">
                @csrf
                <button class="w-full px-3 py-2 text-xs font-semibold rounded-xl 
                               bg-gradient-to-r from-emerald-600 to-emerald-500 text-white">
                    Verify Clearance
                </button>
            </form>

            <form method="POST"
                  action="{{ route('admin.projects.clearance.reject', $project) }}"
                  class="flex-1">
                @csrf
                <button class="w-full px-3 py-2 text-xs font-semibold rounded-xl 
                               bg-gradient-to-r from-rose-600 to-rose-500 text-white">
                    Return
                </button>
            </form>

        </div>

    @endif


    {{-- HINT --}}
    @if($project->requires_clearance)

        <div class="text-[10px] border-t pt-2
            @if($status === 'uploaded') text-purple-600 border-purple-200
            @elseif($status === 'verified') text-emerald-600 border-emerald-200
            @elseif($status === 'rejected') text-rose-600 border-rose-200
            @else text-slate-400 border-slate-200
            @endif
        ">
            @if($status === 'uploaded')
                Review the uploaded clearance before approval
            @elseif($status === 'verified')
                Clearance has been successfully verified
            @elseif($status === 'rejected')
                Clearance was returned and needs revision
            @else
                Waiting for project head to upload clearance
            @endif
        </div>

    @endif

</div>


{{-- ========================= --}}
{{-- REQUIRE MODAL --}}
{{-- ========================= --}}
<div x-show="showRequireModal"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <div class="w-full max-w-md rounded-2xl bg-white shadow-xl border p-6 space-y-4">

        <div class="flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-amber-600 mt-1"></i>

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Require Clearance
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    This will require the project head to submit an off-campus clearance document before proceeding.
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button @click="showRequireModal = false"
                    class="px-3 py-1.5 text-xs border rounded-lg">
                Cancel
            </button>

            <form method="POST" action="{{ route('admin.projects.require-clearance', $project) }}">
                @csrf
                <button class="px-3 py-1.5 text-xs bg-amber-600 text-white rounded-lg">
                    Confirm
                </button>
            </form>
        </div>

    </div>
</div>


{{-- ========================= --}}
{{-- RETRACT MODAL --}}
{{-- ========================= --}}
<div x-show="showRetractModal"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <div class="w-full max-w-md rounded-2xl bg-white shadow-xl border p-6 space-y-4">

        <div class="flex items-start gap-3">
            <i data-lucide="undo-2" class="w-5 h-5 text-rose-600 mt-1"></i>

            <div>
                <div class="text-sm font-semibold text-slate-900">
                    Undo Clearance Requirement
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    This will remove the current clearance requirement and any submitted clearance.
                    If enabled again, the project head will need to submit a new clearance.
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button @click="showRetractModal = false"
                    class="px-3 py-1.5 text-xs border rounded-lg">
                Cancel
            </button>

            <form method="POST" action="{{ route('admin.projects.retract-clearance', $project) }}">
                @csrf
                <button class="px-3 py-1.5 text-xs bg-rose-600 text-white rounded-lg">
                    Confirm
                </button>
            </form>
        </div>

    </div>
</div>

</div>