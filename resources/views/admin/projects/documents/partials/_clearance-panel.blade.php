@php
    $status = $project->clearance_status;

    $cardStyles = match(true) {
        !$project->requires_clearance => 'border-slate-200 bg-white',
        $status === 'uploaded' => 'border-amber-300 bg-gradient-to-br from-amber-50 to-white',
        $status === 'verified' => 'border-emerald-300 bg-gradient-to-br from-emerald-50 to-white',
        $status === 'rejected' => 'border-rose-300 bg-gradient-to-br from-rose-50 to-white',
        default => 'border-slate-200 bg-white'
    };

    $statusLabel = match($status) {
        'required' => ['Waiting for upload', 'bg-amber-100 text-amber-700'],
        'uploaded' => ['Uploaded', 'bg-blue-100 text-blue-700'],
        'verified' => ['Verified', 'bg-emerald-100 text-emerald-700'],
        'rejected' => ['Returned', 'bg-rose-100 text-rose-700'],
        default => ['—', 'bg-slate-100 text-slate-500']
    };
@endphp


<div class="w-full rounded-2xl border shadow-sm p-4 space-y-4 {{ $cardStyles }}">

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
                    <span class="font-mono text-indigo-700">
                        {{ $project->clearance_reference }}
                    </span>
                @endif

            </div>

        </div>


        {{-- ACTION --}}
        <div>

            @if(!$project->requires_clearance)

                <form method="POST"
                      action="{{ route('admin.projects.require-clearance', $project) }}">
                    @csrf

                    <button
                        class="px-3 py-2 text-xs font-semibold rounded-xl 
                               bg-gradient-to-r from-amber-600 to-amber-500 text-white 
                               hover:from-amber-700 hover:to-amber-600 transition shadow-sm">
                        Require Clearance
                    </button>

                </form>

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
    @if($project->requires_clearance && $status === 'uploaded')

        <div class="flex gap-2">

            <form method="POST"
                  action="{{ route('admin.projects.clearance.verify', $project) }}"
                  class="flex-1">
                @csrf

                <button class="w-full px-3 py-2 text-xs font-semibold rounded-xl 
                               bg-gradient-to-r from-emerald-600 to-emerald-500 text-white 
                               hover:from-emerald-700 hover:to-emerald-600 transition shadow-sm">
                    Verify Clearance
                </button>
            </form>

            <form method="POST"
                  action="{{ route('admin.projects.clearance.reject', $project) }}"
                  class="flex-1">
                @csrf

                <button class="w-full px-3 py-2 text-xs font-semibold rounded-xl 
                               bg-gradient-to-r from-rose-600 to-rose-500 text-white 
                               hover:from-rose-700 hover:to-rose-600 transition shadow-sm">
                    Return
                </button>
            </form>

        </div>

    @endif


    {{-- HINT --}}
    @if($project->requires_clearance)

        <div class="text-[10px] border-t pt-2
            @if($status === 'uploaded') text-slate-500 border-slate-200
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