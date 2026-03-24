@php
    $baseFunds = [
        'aeco' => 0,
        'membership_fee' => 0,
        'org_funds' => 0,
        'pta' => 0,
        'raised_funds' => 0,
    ];

    $otherFunds = [];

    foreach ($submission->fundSources as $fs) {
        if (array_key_exists($fs->type, $baseFunds)) {
            $baseFunds[$fs->type] += (float) $fs->amount;
        } else {
            $otherFunds[] = $fs;
        }
    }

    function niceFund($key) {
        return [
            'aeco' => 'AECO',
            'membership_fee' => 'Membership Fee',
            'org_funds' => 'Organization Funds',
            'pta' => 'PTA',
            'raised_funds' => 'Raised Funds',
        ][$key] ?? $key;
    }

    $fundTotal = array_sum($baseFunds) + collect($otherFunds)->sum('amount');
@endphp


<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Sources of Funds</h2>
            <p class="text-sm text-slate-500 mt-1">
                Breakdown of funding sources for this strategic plan.
            </p>
        </div>

        <div class="text-right">
            <div class="text-xs text-slate-500">Total Funds</div>
            <div class="text-lg font-semibold text-slate-900">
                ₱{{ number_format((float)$fundTotal, 2) }}
            </div>
        </div>
    </div>


    {{-- SYSTEM FUNDS (GRID 🔥) --}}
    <div>
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">
            Core Sources
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

            @foreach($baseFunds as $key => $amount)
                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <div class="text-xs text-slate-500">
                        {{ niceFund($key) }}
                    </div>

                    <div class="mt-1 font-semibold text-slate-900">
                        ₱{{ number_format($amount, 2) }}
                    </div>
                </div>
            @endforeach

        </div>
    </div>


    {{-- OTHER SOURCES --}}
    @if(count($otherFunds))

    <div>
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">
            Other Sources
        </h3>

        <div class="overflow-x-auto rounded-xl border border-slate-200">

            <table class="min-w-full text-sm">

                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Source</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @foreach($otherFunds as $fs)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-slate-700">
                                {{ $fs->label ?: '—' }}
                            </td>
                            <td class="px-4 py-3 text-right font-medium text-slate-900">
                                ₱{{ number_format((float)$fs->amount, 2) }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
    </div>

    @endif


    {{-- VALIDATION (SUBTLE, NOT HARSH) --}}
    <div class="rounded-xl border px-4 py-3 text-sm flex items-center justify-between
        @if(abs(((float)$fundTotal) - ((float)$submission->total_overall)) < 0.005)
            border-emerald-200 bg-emerald-50 text-emerald-700
        @else
            border-amber-200 bg-amber-50 text-amber-700
        @endif
    ">
        <span>
            Funds vs Overall Budget difference
        </span>

        <span class="font-semibold">
            ₱{{ number_format(((float)$fundTotal) - ((float)$submission->total_overall), 2) }}
        </span>
    </div>

</div>