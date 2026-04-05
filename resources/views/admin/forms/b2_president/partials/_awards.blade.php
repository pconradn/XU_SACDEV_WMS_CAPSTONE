@php
    $rows = $awards ?? collect();
@endphp

@if($rows->count())

<div class="rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50 to-white shadow-sm overflow-hidden">

    {{-- HEADER --}}
    <div class="px-5 py-3 border-b border-slate-200 flex items-start justify-between gap-4">

        <div>
            <h3 class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">
                Awards and Recognitions
            </h3>

            <p class="mt-1 text-xs text-slate-500 max-w-md">
                Awards received by the student.
            </p>
        </div>

        <div class="flex items-center gap-2 text-[11px] font-medium">

            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            </span>

            <span class="text-emerald-700">
                {{ $rows->count().' record'.($rows->count() > 1 ? 's' : '') }}
            </span>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="min-w-full text-xs">

            <thead class="text-[11px] uppercase text-slate-500 border-b border-slate-200 bg-white">
                <tr>
                    <th class="py-2.5 px-4 font-medium">Award</th>
                    <th class="py-2.5 px-4 font-medium">Organization</th>
                    <th class="py-2.5 px-4 font-medium text-right">Date</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 bg-white">

                @foreach($rows as $award)

                    <tr class="hover:bg-slate-50 transition">

                        <td class="py-2.5 px-4 text-slate-900 font-medium">
                            {{ $award->award_name ?? '—' }}
                        </td>

                        <td class="py-2.5 px-4 text-slate-700">
                            {{ $award->organization ?? '—' }}
                        </td>

                        <td class="py-2.5 px-4 text-right text-slate-700">
                            @if(!empty($award->date_received))
                                {{ \Carbon\Carbon::parse($award->date_received)->format('M d, Y') }}
                            @else
                                —
                            @endif
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endif