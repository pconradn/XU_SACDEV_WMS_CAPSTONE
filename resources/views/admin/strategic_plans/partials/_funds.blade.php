        {{-- FUND SOURCES --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h2 class="text-base font-semibold text-slate-900">Sources of Funds</h2>
            <p class="text-sm text-slate-500 mt-1">All fund entries including other sources.</p>

            <div class="mt-4 overflow-x-auto rounded-lg border border-slate-200">
                <table class="min-w-[900px] w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                    <tr class="text-left">
                        <th class="px-3 py-2 w-56">Type</th>
                        <th class="px-3 py-2">Label</th>
                        <th class="px-3 py-2 w-48 text-right">Amount</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                    @php
                        $fundTotal = 0;
                    @endphp

                    @forelse($submission->fundSources as $fs)
                        @php $fundTotal += (float) $fs->amount; @endphp
                        <tr>
                            <td class="px-3 py-2 text-slate-700">{{ $fs->type }}</td>
                            <td class="px-3 py-2 text-slate-700">{{ $fs->label ?: '—' }}</td>
                            <td class="px-3 py-2 text-slate-700 text-right">{{ number_format((float)$fs->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-3 py-4 text-slate-500">No fund sources.</td>
                        </tr>
                    @endforelse
                    </tbody>

                    <tfoot class="bg-slate-50">
                    <tr>
                        <td colspan="2" class="px-3 py-2 text-slate-700 font-semibold">Total Funds</td>
                        <td class="px-3 py-2 text-right text-slate-900 font-semibold">{{ number_format((float)$fundTotal, 2) }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-3 rounded-lg border px-4 py-3 text-sm
                @if(abs(((float)$fundTotal) - ((float)$submission->total_overall)) < 0.005)
                    border-emerald-200 bg-emerald-50 text-emerald-700
                @else
                    border-rose-200 bg-rose-50 text-rose-700
                @endif
            ">
                Funds vs Overall Budget difference:
                <span class="font-semibold">
                    {{ number_format(((float)$fundTotal) - ((float)$submission->total_overall), 2) }}
                </span>
            </div>
        </div>