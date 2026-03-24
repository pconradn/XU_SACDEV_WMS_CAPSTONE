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
               class="px-3 py-2 text-xs font-medium rounded-md
               
               @if($header['proposal_action']['type'] === 'create')
                    bg-indigo-600 text-white hover:bg-indigo-700

               @elseif($header['proposal_action']['type'] === 'edit')
                    bg-amber-100 text-amber-800 hover:bg-amber-200

               @else
                    bg-slate-200 text-slate-700 hover:bg-slate-300
               @endif
               ">

                {{ $header['proposal_action']['label'] }}

            </a>

        @endif

    </div>

</div>