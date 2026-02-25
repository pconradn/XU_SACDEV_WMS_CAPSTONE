@php
    $rows = $awards ?? collect();
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm mb-4">

    <div class="flex items-start justify-between gap-3">

        <div>

            <h3 class="text-base font-semibold text-slate-900">
                Awards and Recognitions
            </h3>

            <p class="mt-1 text-sm text-slate-600">
                Awards received by the student.
            </p>

        </div>


        {{-- Status --}}
        <div class="flex items-center gap-2 text-sm font-medium text-slate-800">

            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">

                <span class="h-2.5 w-2.5 rounded-full
                    {{ $rows->count() ? 'bg-emerald-500' : 'bg-slate-400' }}">
                </span>

            </span>

            <span>
                {{ $rows->count() ? $rows->count().' record(s)' : 'No records' }}
            </span>

        </div>

    </div>



    @if($rows->count())

        <div class="mt-4 overflow-x-auto">

            <table class="min-w-full text-left text-sm">

                <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">

                    <tr>
                        <th class="py-2 pr-3">Award</th>
                        <th class="py-2 pr-3">Organization</th>
                        <th class="py-2 pr-3">Date Received</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach($rows as $award)

                        <tr>

                            <td class="py-2 pr-3 text-slate-900">
                                {{ $award->award_name ?? '—' }}
                            </td>

                            <td class="py-2 pr-3 text-slate-700">
                                {{ $award->organization ?? '—' }}
                            </td>

                            <td class="py-2 pr-3 text-slate-700">

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

    @else

        <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">

            No awards were submitted.

        </div>

    @endif

</div>