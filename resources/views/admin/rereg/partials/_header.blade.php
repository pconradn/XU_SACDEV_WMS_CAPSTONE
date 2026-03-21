<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">


    <div class="flex-1">

        {{-- TITLE --}}
        <h2 class="text-2xl font-bold text-slate-900 leading-tight">
            {{ $organization->name }}
        </h2>

    
        <div class="mt-1 text-sm text-slate-500">
            Re-Registration Submissions
        </div>

      
        <div class="mt-2 text-xs text-slate-400">
         
        </div>

    </div>




    <div class="flex flex-col gap-3 w-full lg:w-[380px]">

 
        <form method="POST" action="{{ route('rereg.setSy') }}" class="flex gap-2">

            @csrf

            <select name="encode_school_year_id"
                onchange="this.form.submit()"
                class="flex-1 h-9 rounded-lg border border-slate-300 px-3 text-sm bg-white text-slate-700
                       focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">

                <option disabled selected>Select SY...</option>

                @foreach($schoolYears as $sy)
                    <option value="{{ $sy->id }}" @selected($encodeSyId == $sy->id)>
                        {{ $sy->name }}
                    </option>
                @endforeach

            </select>

        </form>

        <div class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 bg-slate-50">

            @if(!$encodeSyId)

                <span class="text-xs text-slate-400">
                    Select SY to check status
                </span>

            @else

                @if($alreadyActivated)

                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-blue-700">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        Already Registered
                    </span>

                @elseif($allApproved)

                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Ready for Activation
                    </span>

                @else

                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-600">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        Pending Requirements
                    </span>

                @endif


                @if($encodeSyId && $allApproved && !$alreadyActivated)
                    <button
                        onclick="window.dispatchEvent(new Event('open-activate-modal'))"
                        class="ml-2 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800 transition"
                    >
                        Activate
                    </button>
                @endif

            @endif

        </div>

    </div>

</div>