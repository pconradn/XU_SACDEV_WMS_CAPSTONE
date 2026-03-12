<div class="border border-slate-300">


    <div class="flex justify-end px-4 pt-3">
        <div class="text-[12px] font-semibold text-slate-500 uppercase tracking-wide">
            
        </div>
    </div>


    <div class="px-4 pb-4 text-center">
        <h1 class="text-[24px] font-bold tracking-wide text-slate-900">
            BUDGET PROPOSAL
        </h1>
    </div>


    <div class="bg-slate-50 border-t border-b border-slate-300 px-4 py-1 text-center">
        <div class="text-[12px] font-semibold text-slate-900 tracking-wide">
            PROJECT FINANCIAL PLAN
        </div>
    </div>


    <div class="px-4 py-5">

        <div class="text-[12px] font-medium text-slate-700 mb-2">
            Name / Title of Project:
        </div>

        <div class="text-center">
            <h2 class="text-[18px] font-semibold text-slate-900">
                {{ $project->title }}
            </h2>
        </div>

    </div>


    <div class="border-t border-slate-300 px-4 py-4">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-[12px]">

            <div>
                <div class="text-[10px] text-slate-500 uppercase tracking-wide">
                    Implementation Date
                </div>

                @php
                $startDate = $project->proposalDocument?->proposalData?->start_date;
                @endphp

                <div class="mt-1 font-medium text-slate-900">
                    {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('F d, Y') : 'Not specified' }}
                </div>
            </div>

            @php
            //dd($project->proposalDocument->proposalData->start_date);
            @endphp

            <div>
                <div class="text-[10px] text-slate-500 uppercase tracking-wide">
                    Venue
                </div>

                <div class="mt-1 font-medium text-slate-900">
                    {{ $project->proposalDocument->proposalData->venue_name ?? 'Not specified' }}
                </div>
            </div>


            <div>
                <div class="text-[10px] text-slate-500 uppercase tracking-wide">
                    Organization
                </div>

                <div class="mt-1 font-medium text-slate-900">
                    {{ $project->organization->name ?? '—' }}
                </div>
            </div>


        </div>

    </div>

</div>