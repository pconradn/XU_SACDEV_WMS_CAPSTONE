@php
$sections = [
    'cash_advance'       => 'A. For Cash Advance (Finance Office)',
    'fund_transfer'      => 'B. For Fund Transfer/Direct Payment to Supplier',
    'xucmpc'             => 'C. For XUCMPC',
    'bookstore'          => 'D. For Bookstore',
    'central_purchasing' => 'E. For Central Purchasing Unit',
    'counterpart'        => 'F. Counterpart'
];
@endphp

<table style="width:100%; border-collapse:collapse; font-size:11px;">

    {{-- HEADER --}}
    <tr style="background:#1f6fb2; color:#fff; font-weight:bold;">
        <th style="border:1px solid #1f6fb2; padding:4px;">Qty</th>
        <th style="border:1px solid #1f6fb2; padding:4px;">Unit</th>
        <th style="border:1px solid #1f6fb2; padding:4px;">Particulars</th>
        <th style="border:1px solid #1f6fb2; padding:4px;">Price/Unit</th>
        <th style="border:1px solid #1f6fb2; padding:4px;">Amount</th>
        <th style="border:1px solid #1f6fb2; padding:4px;">Subtotal</th>
    </tr>

    @foreach($sections as $code => $label)

    @php
        $items = $budget->items->where('section', $code);
        $rowCount = max(count($items), 5); // ensure at least 5 rows
        $subtotal = $budget->section_totals[$code] ?? 0;
    @endphp

    {{-- SECTION HEADER --}}
    <tr style="background:#d9d9d9; font-weight:bold;">
        <td colspan="6" style="border:1px solid #1f6fb2; padding:4px;">
            {{ $label }}
        </td>
    </tr>

    {{-- ROWS --}}
    @for($i = 0; $i < $rowCount; $i++)

    @php
        $item = $items->values()[$i] ?? null;
    @endphp

    <tr>
        <td style="border:1px dotted #1f6fb2; text-align:center;">
            {{ $item->qty ?? '' }}
        </td>
        <td style="border:1px dotted #1f6fb2; text-align:center;">
            {{ $item->unit ?? '' }}
        </td>
        <td style="border:1px dotted #1f6fb2;">
            {{ $item->particulars ?? '' }}
        </td>
        <td style="border:1px dotted #1f6fb2; text-align:right;">
            {{ $item ? number_format($item->price_per_unit, 2) : '' }}
        </td>
        <td style="border:1px dotted #1f6fb2; text-align:right;">
            {{ $item ? number_format($item->amount, 2) : '' }}
        </td>

        {{-- SUBTOTAL COLUMN --}}
        @if($i === 0)
        <td rowspan="{{ $rowCount }}"
            style="border:1px solid #1f6fb2; text-align:right; vertical-align:middle; font-weight:bold;">
            {{ number_format($subtotal, 2) }}
        </td>
        @endif
    </tr>

    @endfor

    @endforeach

    {{-- GRAND TOTAL --}}
    <tr style="background:#1f6fb2; color:#fff; font-weight:bold;">
        <td colspan="5" style="padding:5px; text-align:left;">
            Grand Total:
        </td>
        <td style="padding:5px; text-align:right;">
            {{ number_format($budget->total_expenses, 2) }}
        </td>
    </tr>

</table>