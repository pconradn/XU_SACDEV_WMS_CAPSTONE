<div class="bg-white border rounded-2xl p-6 shadow-sm flex flex-col lg:flex-row justify-between gap-6">

    {{-- LEFT --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            {{ $header['title'] }}
        </h1>

        <p class="text-sm text-slate-500 mt-1">
            {{ $header['org'] ?? '—' }} • {{ $header['school_year'] ?? '' }}
        </p>

        <p class="text-sm text-slate-600 mt-3">
            Project Head:
            <span class="font-medium">
                {{ $header['project_head'] ?? '—' }}
            </span>
        </p>
    </div>

    {{-- RIGHT --}}
    <div class="flex flex-col items-start lg:items-end gap-3">

        {{-- STATUS --}}
        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full ring-1 {{ $header['status_class'] }}">
            {{ $header['status_label'] }}
        </span>
        {{-- PROPOSAL CTA --}}
        @if($header['proposal_action'])

            <a href="{{ $header['proposal_action']['url'] }}"
            class="px-3 py-2 text-xs font-medium rounded-md transition shadow-sm
            
            @if($header['proposal_action']['type'] === 'create')
                    bg-indigo-600 text-white hover:bg-indigo-700 shadow-indigo-200

            @elseif($header['proposal_action']['type'] === 'edit')
                    bg-amber-500 text-white hover:bg-amber-600 shadow-amber-200

            @else
                    bg-slate-600 text-white hover:bg-slate-700 shadow-slate-200
            @endif
            ">

                {{ $header['proposal_action']['label'] }}

            </a>

        @endif


        {{-- AGREEMENT BUTTON --}}
        <button 
            @click="openAgreement = true"
            class="px-3 py-2 text-xs font-medium rounded-md transition shadow-sm
            {{ $needsAgreement 
                ? 'bg-rose-600 text-white hover:bg-rose-700 shadow-rose-200' 
                : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-emerald-200' }}"
        >
            {{ $needsAgreement ? 'Complete Agreement' : 'View Agreement' }}
        </button>

    </div>

</div>