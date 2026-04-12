<div 
    x-data="{
        showRequireModal: false,
        showRetractModal: false,
        showVerifyModal: false,
        showReturnModal: false,
        returnRemarks: ''
    }"
    class="w-full"
>

@php
    $isCoa = auth()->user()?->is_coa_officer ?? false;
    $status = $project->clearance_status;

    $cardStyles = match(true) {
        !$project->requires_clearance => 'border-slate-200',
        $status === 'uploaded' => 'border-purple-200',
        $status === 'approved' => 'border-emerald-200',
        $status === 'rejected' => 'border-rose-200',
        default => 'border-slate-200'
    };

    $statusLabel = match($status) {
        'required' => ['Waiting for upload', 'bg-amber-100 text-amber-700'],
        'uploaded' => ['Uploaded', 'bg-purple-100 text-purple-700'],
        'approved' => ['Approved', 'bg-emerald-100 text-emerald-700'],
        'rejected' => ['Returned', 'bg-rose-100 text-rose-700'],
        default => ['—', 'bg-slate-100 text-slate-500']
    };
@endphp


<div class="rounded-2xl border {{ $cardStyles }} bg-gradient-to-b from-slate-50 to-white shadow-sm">

    <div class="p-4 space-y-4">

        {{-- HEADER --}}
        <div class="flex items-start justify-between gap-3">

            <div class="space-y-1">
                <div class="text-[10px] uppercase tracking-wide text-slate-500">
                    Off-Campus Clearance
                </div>

                <div class="text-sm font-semibold text-slate-900">
                    @if(!$project->requires_clearance)
                        Not Required
                    @else
                        <span class="text-slate-500">Reference:</span>
                        <span class="font-mono text-purple-700">
                            {{ $project->clearance_reference }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex items-center gap-2">

                @if(!$project->requires_clearance && !$isCoa)

                    <button
                        @click="showRequireModal = true"
                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                            border border-purple-200 bg-purple-50 text-purple-700
                            hover:bg-purple-100 transition">
                        <i data-lucide="shield-plus" class="w-3 h-3"></i>
                        Require Clearance
                    </button>

                @elseif($project->requires_clearance && !$isCoa)

                    <button
                        @click="showRetractModal = true"
                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                            border border-rose-200 bg-rose-50 text-rose-700
                            hover:bg-rose-100 transition">
                        <i data-lucide="undo-2" class="w-3 h-3"></i>
                        Remove Requirement
                    </button>

                @else

                    <button disabled
                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium rounded-lg
                            border border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed">
                        <i data-lucide="lock" class="w-3 h-3"></i>
                        Restricted
                    </button>

                @endif

            </div>

        </div>


        {{-- STATUS --}}
        @if($project->requires_clearance)

        <div class="flex items-center justify-between pt-2 border-t border-slate-200">
            <div class="text-[11px] text-slate-500">
                Clearance Status
            </div>

            <span class="px-2.5 py-1 text-[10px] font-semibold rounded-full {{ $statusLabel[1] }}">
                {{ $statusLabel[0] }}
            </span>
        </div>

        @endif


        {{-- ACTION GROUP --}}
        @if($project->requires_clearance && $status === 'uploaded')

        <div class="pt-2 border-t border-purple-200">

            <div class="flex flex-wrap gap-2">

                @if($project->clearance_file_path)
                <a href="{{ asset('storage/' . $project->clearance_file_path) }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg
                        border border-slate-200 bg-white text-slate-700
                        hover:bg-slate-50 transition">
                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                    View
                </a>
                @endif

                @if(!$isCoa)
                <button
                    type="button"
                    @click="showVerifyModal = true"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg
                        border border-emerald-200 bg-emerald-50 text-emerald-700
                        hover:bg-emerald-100 transition">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                    Approve
                </button>

                <button
                    type="button"
                    @click="showReturnModal = true"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg
                        border border-rose-200 bg-rose-50 text-rose-700
                        hover:bg-rose-100 transition">
                    <i data-lucide="corner-up-left" class="w-3.5 h-3.5"></i>
                    Return
                </button>
                @endif

            </div>

        </div>

        @endif


        {{-- HINT --}}
        @if($project->requires_clearance)

        <div class="text-[10px] pt-2 border-t
            @if($status === 'uploaded') text-purple-600 border-purple-200
            @elseif($status === 'approved') text-emerald-600 border-emerald-200
            @elseif($status === 'rejected') text-rose-600 border-rose-200
            @else text-slate-400 border-slate-200
            @endif
        ">
            @if($status === 'uploaded')
                Review the uploaded clearance before approval
            @elseif($status === 'approved')
                Clearance approved and verified
            @elseif($status === 'rejected')
                Clearance returned for revision
            @else
                Waiting for clearance upload
            @endif
        </div>

        @endif

    </div>

</div>


{{-- REQUIRE MODAL --}}
<div x-show="showRequireModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white shadow-sm p-5 space-y-4">

        <div class="flex gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-amber-600 mt-0.5"></i>
            <div>
                <div class="text-sm font-semibold text-slate-900">Require Clearance</div>
                <p class="text-xs text-slate-500 mt-1">
                    Require project head to submit clearance before proceeding.
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button @click="showRequireModal = false"
                class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 hover:bg-slate-50">
                Cancel
            </button>

            <form method="POST" action="{{ route('admin.projects.require-clearance', $project) }}">
                @csrf
                <button class="px-3 py-1.5 text-xs rounded-lg bg-amber-600 text-white hover:bg-amber-700">
                    Confirm
                </button>
            </form>
        </div>

    </div>
</div>


{{-- RETRACT MODAL --}}
<div x-show="showRetractModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white shadow-sm p-5 space-y-4">

        <div class="flex gap-3">
            <i data-lucide="undo-2" class="w-5 h-5 text-rose-600 mt-0.5"></i>
            <div>
                <div class="text-sm font-semibold text-slate-900">Undo Requirement</div>
                <p class="text-xs text-slate-500 mt-1">
                    Removes requirement and any uploaded clearance.
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button @click="showRetractModal = false"
                class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 hover:bg-slate-50">
                Cancel
            </button>

            <form method="POST" action="{{ route('admin.projects.retract-clearance', $project) }}">
                @csrf
                <button class="px-3 py-1.5 text-xs rounded-lg bg-rose-600 text-white hover:bg-rose-700">
                    Confirm
                </button>
            </form>
        </div>

    </div>
</div>


{{-- VERIFY MODAL --}}
<div x-show="showVerifyModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white shadow-sm p-5 space-y-4">

        <div class="flex gap-3">
            <i data-lucide="shield-check" class="w-5 h-5 text-emerald-600 mt-0.5"></i>
            <div>
                <div class="text-sm font-semibold text-slate-900">Approve Clearance</div>
                <p class="text-xs text-slate-500 mt-1">
                    This action cannot be undone.
                </p>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button @click="showVerifyModal = false"
                class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 hover:bg-slate-50">
                Cancel
            </button>

            <form method="POST" action="{{ route('admin.projects.clearance.verify', $project) }}">
                @csrf
                <button class="px-3 py-1.5 text-xs rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                    Confirm
                </button>
            </form>
        </div>

    </div>
</div>


{{-- RETURN MODAL --}}
<div x-show="showReturnModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white shadow-sm p-5 space-y-4">

        <div class="flex gap-3">
            <i data-lucide="corner-up-left" class="w-5 h-5 text-rose-600 mt-0.5"></i>
            <div>
                <div class="text-sm font-semibold text-slate-900">Return Clearance</div>
                <p class="text-xs text-slate-500 mt-1">
                    Remarks are required.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.projects.clearance.return', $project) }}" class="space-y-4">
            @csrf

            <textarea
                name="remarks"
                x-model="returnRemarks"
                rows="4"
                required
                class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-rose-500 focus:ring focus:ring-rose-100"
                placeholder="Enter remarks"></textarea>

            <div class="flex justify-end gap-2">
                <button @click="showReturnModal = false" type="button"
                    class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 hover:bg-slate-50">
                    Cancel
                </button>

                <button
                    type="submit"
                    :disabled="!returnRemarks.trim()"
                    :class="{'opacity-50 cursor-not-allowed': !returnRemarks.trim()}"
                    class="px-3 py-1.5 text-xs rounded-lg bg-rose-600 text-white hover:bg-rose-700">
                    Confirm
                </button>
            </div>

        </form>

    </div>
</div>

</div>