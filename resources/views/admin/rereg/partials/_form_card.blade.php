<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

    @php
        $badge = $form['badge'] ?? null;

        $status = $badge['text'] ?? 'No submission';

        $dot =
            !$badge ? 'bg-slate-400' :
            (str_contains(strtolower($status),'approved') ? 'bg-emerald-500' :
            (str_contains(strtolower($status),'returned') ? 'bg-rose-500' :
            (str_contains(strtolower($status),'submitted') ? 'bg-amber-500' :
            (str_contains(strtolower($status),'forwarded') ? 'bg-blue-500' :
            'bg-slate-400'))));
    @endphp


    {{-- Header --}}
    <div class="flex items-start justify-between gap-3">

        <div>

            {{-- Label --}}
            <div class="font-semibold text-slate-900">
                {{ $form['label'] ?? 'Form' }}
            </div>


            {{-- Status --}}
            <div class="mt-1 flex items-center gap-2 text-sm text-slate-700">

                <span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span>

                <span>
                    {{ $status }}
                </span>

            </div>


            {{-- Submitted --}}
            @if(!empty($form['meta']['submitted_at']))
                <div class="text-xs text-slate-500 mt-1">
                    Submitted: {{ $form['meta']['submitted_at'] }}
                </div>
            @endif


            {{-- Reviewed --}}
            @if(!empty($form['meta']['reviewed_at']))
                <div class="text-xs text-slate-500">
                    Reviewed: {{ $form['meta']['reviewed_at'] }}
                </div>
            @endif

        </div>


        {{-- Actions --}}
        <div class="flex flex-col items-end gap-2">


            {{-- View --}}
            @if(!empty($form['viewRoute']))
                <a href="{{ route($form['viewRoute'], $form['routeParams'] ?? []) }}"
                   class="inline-flex items-center rounded-md border border-slate-200
                          px-2.5 py-1 text-xs font-semibold text-slate-700
                          hover:bg-slate-50">

                    View

                </a>
            @endif


            {{-- Approve B6 --}}
            @if(
                isset($key) &&
                $key === 'b6' &&
                ($status !== 'Approved') &&
                !empty($form['routeParams']['submission'])
            )

                <form method="POST"
                      action="{{ route('admin.constitution.approve', $form['routeParams']['submission']) }}">

                    @csrf

                    <button type="submit"
                        class="inline-flex items-center gap-1 rounded-md
                               bg-emerald-600 px-2 py-1 text-xs font-semibold
                               text-white hover:bg-emerald-700">

                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M5 13l4 4L19 7"/>
                        </svg>

                        Approve

                    </button>

                </form>

            @endif


        </div>

    </div>


    {{-- Remarks --}}
    @if(!empty($form['remarksPreview']))

        <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-3">

            <div class="text-xs font-semibold text-slate-700">
                Latest remarks
            </div>

            <div class="text-sm text-slate-700 mt-1">
                {{ $form['remarksPreview'] }}
            </div>

        </div>

    @endif


</div>