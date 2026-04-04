<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

    {{-- ================= LEFT: TITLE ================= --}}
    <div class="flex-1 space-y-1">

        <h2 class="text-xl md:text-2xl font-semibold text-slate-900 leading-tight flex items-center gap-2">
            <i data-lucide="building-2" class="w-5 h-5 text-slate-400"></i>
            {{ $organization->name }}
        </h2>

        <div class="text-xs text-slate-500">
            Re-Registration Submissions
        </div>

    </div>


    {{-- ================= RIGHT PANEL ================= --}}
    <div class="flex flex-col gap-3 w-full lg:w-[380px]">

        {{-- ================= SY SELECT ================= --}}
        <form method="POST" action="{{ route('rereg.setSy') }}" class="flex gap-2">
            @csrf

            <select name="encode_school_year_id"
                onchange="this.form.submit()"
                class="flex-1 h-9 rounded-xl border border-slate-200 bg-white px-3 text-xs text-slate-700 shadow-sm
                       focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition">

                <option disabled selected>Select SY...</option>

                @foreach($schoolYears as $sy)
                    <option value="{{ $sy->id }}" @selected($encodeSyId == $sy->id)>
                        {{ $sy->name }}
                    </option>
                @endforeach

            </select>
        </form>


        {{-- ================= STATUS CARD ================= --}}
        @php
            $statusClasses = !$encodeSyId
                ? 'border-slate-200 bg-slate-50'
                : ($alreadyActivated
                    ? 'border-blue-200 border-l-4 border-l-blue-500 bg-blue-50/50'
                    : ($allApproved
                        ? 'border-emerald-200 border-l-4 border-l-emerald-500 bg-emerald-50/50'
                        : 'border-amber-200 border-l-4 border-l-amber-400 bg-amber-50/50'
                    )
                );
        @endphp

        <div class="flex items-center justify-between rounded-xl border px-4 py-3 shadow-sm transition {{ $statusClasses }}">

            {{-- STATUS TEXT --}}
            <div>

                @if(!$encodeSyId)

                    <span class="text-[11px] text-slate-400">
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

                @endif

            </div>


            {{-- ACTION BUTTON --}}
            @if($encodeSyId && $allApproved && !$alreadyActivated)
                <button
                    onclick="window.dispatchEvent(new Event('open-activate-modal'))"
                    class="ml-2 inline-flex items-center gap-1 rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white
                           hover:bg-slate-800 transition hover:scale-[1.03]">

                    <i data-lucide="zap" class="w-3 h-3"></i>
                    Activate
                </button>
            @endif

        </div>

    </div>

</div>