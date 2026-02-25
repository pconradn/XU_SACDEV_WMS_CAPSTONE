@php
    $rows = $leaderships ?? collect();
@endphp

<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex items-start justify-between">

        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Leadership Involvement
            </h3>

            <p class="mt-1 text-sm text-slate-600">
                Leadership roles held by the organization president.
            </p>
        </div>

        <div class="flex items-center gap-2 text-sm font-medium text-slate-800">

            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100">
                <span class="h-2.5 w-2.5 rounded-full
                    {{ $rows->count() > 0 ? 'bg-emerald-500' : 'bg-slate-400' }}">
                </span>
            </span>

            <span>
                {{ $rows->count() > 0
                    ? $rows->count().' leadership record'.($rows->count() > 1 ? 's' : '')
                    : 'No leadership records'
                }}
            </span>

        </div>

    </div>



    <div class="mt-5 overflow-x-auto">

        @if($rows->count() > 0)

            <table class="min-w-full text-left text-sm">

                <thead class="text-xs uppercase text-slate-500 border-b border-slate-200">

                    <tr>
                        <th class="py-2 pr-4">Organization</th>
                        <th class="py-2 pr-4">Position</th>
                        <th class="py-2 pr-4">Address</th>
                        <th class="py-2 pr-4">Inclusive Years</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach($rows as $row)

                        <tr>

                            <td class="py-3 pr-4">
                                <div class="text-sm font-medium text-slate-900">
                                    {{ $row->organization_name ?: '—' }}
                                </div>
                            </td>

                            <td class="py-3 pr-4">
                                <div class="text-sm text-slate-700">
                                    {{ $row->position ?: '—' }}
                                </div>
                            </td>

                            <td class="py-3 pr-4">
                                <div class="text-sm text-slate-700">
                                    {{ $row->organization_address ?: '—' }}
                                </div>
                            </td>

                            <td class="py-3 pr-4">
                                <div class="text-sm text-slate-700">
                                    {{ $row->inclusive_years ?: '—' }}
                                </div>
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center">

                <div class="text-sm text-slate-500">
                    No leadership involvement records were submitted.
                </div>

            </div>

        @endif

    </div>

</div>