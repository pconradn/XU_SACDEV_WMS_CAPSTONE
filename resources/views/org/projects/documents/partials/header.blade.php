<div class="mb-6">

    <div class="flex items-start justify-between gap-6">

        {{-- LEFT SIDE --}}
        <div>

            <h2 class="text-xl font-semibold text-slate-900">
                {{ $project->title }}
            </h2>

            <div class="text-sm text-slate-600 mt-1">
                Project Documents Hub
            </div>

            <div class="text-xs text-slate-500 mt-1 space-x-2">



                <span class="text-slate-300">|</span>

                <span>
                    Project Head:
                    @if($projectHead)
                        <span class="font-semibold text-slate-800">
                            {{ $projectHead->name }}
                        </span>
                    @else
                        <span class="font-semibold text-rose-600">
                            Not assigned
                        </span>
                    @endif
                </span>

            </div>


            {{-- PROJECT DATE DISPLAY --}}
            @if($proposalData)

                <div class="mt-2 text-xs text-slate-600">

                    <span>
                        Start:
                        <span class="font-medium text-slate-800">
                            {{ \Carbon\Carbon::parse($proposalData->start_date)->format('M d, Y') }}
                        </span>
                    </span>

                    <span class="mx-2 text-slate-300">|</span>

                    <span>
                        End:
                        <span class="font-medium text-slate-800">
                            {{ \Carbon\Carbon::parse($proposalData->end_date)->format('M d, Y') }}
                        </span>
                    </span>

                </div>

            @endif

        </div>


        {{-- RIGHT SIDE --}}
        <div class="flex items-center gap-3">


            {{-- CHANGE DATE --}}
            @if($proposalDocument && $proposalDocument->status === 'approved_by_sacdev')

                <button
                    onclick="confirmPostponement()"
                    class="inline-flex items-center rounded-lg bg-amber-500 px-3 py-2 text-sm font-semibold text-white hover:bg-amber-600">

                    Change Date

                </button>

            @endif


            {{-- CANCEL PROJECT --}}
            @if($proposalDocument && $proposalDocument->status === 'approved_by_sacdev')

                <button
                    onclick="confirmCancellation()"
                    class="inline-flex items-center rounded-lg bg-rose-600 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-700">

                    Cancel Project

                </button>

            @endif


            {{-- BACK BUTTON --}}
            <a href="{{ route('org.projects.index') }}"
               class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50">

                Back to Projects

            </a>

        </div>

    </div>

</div>



{{-- SCRIPT --}}
<script>

function confirmPostponement(){

    if(confirm(
        "Changing the project date requires submitting a Notice of Postponement.\n\nContinue?"
    )){
        window.location.href = "{{ route('org.projects.postponement.create',$project) }}";
    }

}


function confirmCancellation(){

    if(confirm(
        "Cancelling the project will create a Notice of Cancellation.\n\nContinue?"
    )){
        window.location.href = "{{ route('org.projects.cancellation.create',$project) }}";
    }

}

</script>