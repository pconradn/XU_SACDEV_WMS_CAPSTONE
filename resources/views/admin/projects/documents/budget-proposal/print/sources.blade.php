@php
    $grandTotal = $budget->total_expenses ?? 0;

    $counterpart = ($budget->counterpart_amount_per_pax ?? 0) * ($budget->counterpart_pax ?? 0);

    $pta = $budget->pta_amount ?? 0;
    $raised = $budget->raised_funds ?? 0;

    $orgTotal = $grandTotal - ($counterpart + $pta + $raised);
@endphp

<div style="margin-top:10px;">

    <table style="width:100%; border-collapse:collapse; font-size:11px;">

        {{-- HEADER --}}
        <tr style="background:#1f6fb2; color:#fff; font-weight:bold;">
            <td style="padding:4px; border:1px solid #1f6fb2;">
                Less (Source of funds rather than finance) 
            </td>
            <td style="padding:4px; border:1px solid #1f6fb2; text-align:center;">
                Amount/Pax
            </td>
            <td style="padding:4px; border:1px solid #1f6fb2; text-align:center;">
                Number of Pax
            </td>
            <td style="padding:4px; border:1px solid #1f6fb2; text-align:center;">
                Total
            </td>
        </tr>

        {{-- COUNTERPART --}}
        <tr>
            <td style="border:1px dotted #1f6fb2; padding:4px;">
                Less Counterpart:
                <span style="font-size:10px;">
                    (Amount/Person x Number of Persons)
                </span>
            </td>

            <td style="border:1px dotted #1f6fb2; text-align:right;">
                {{ number_format($budget->counterpart_amount_per_pax ?? 0, 2) }}
            </td>

            <td style="border:1px dotted #1f6fb2; text-align:center;">
                {{ $budget->counterpart_pax ?? 0 }}
            </td>

            <td style="border:1px dotted #1f6fb2; text-align:right;">
                {{ number_format($budget->counterpart_total ?? 0, 2) }}
            </td>
        </tr>

        {{-- PTA --}}
        <tr>
            <td style="border:1px dotted #1f6fb2; padding:4px;">
                PTA:
            </td>

            <td style="border:1px dotted #1f6fb2;"></td>
            <td style="border:1px dotted #1f6fb2;"></td>

            <td style="border:1px dotted #1f6fb2; text-align:right;">
                {{ number_format($budget->pta_amount ?? 0, 2) }}
            </td>
        </tr>

        {{-- RAISED FUNDS --}}
        <tr>
            <td style="border:1px dotted #1f6fb2; padding:4px;">
                Raised Funds:
                <span style="font-size:10px;">
                    (Solicitation, Selling, Ticket-Selling, etc)
                </span>
            </td>

            <td style="border:1px dotted #1f6fb2;"></td>
            <td style="border:1px dotted #1f6fb2;"></td>

            <td style="border:1px dotted #1f6fb2; text-align:right;">
                {{ number_format($budget->raised_funds ?? 0, 2) }}
            </td>
        </tr>

        {{-- TOTAL ORG --}}
        <tr style="font-weight:bold;">
            <td style="border:1px solid #1f6fb2; padding:4px;">
                Total Amount Charged to the Org:
                <span style="font-size:10px;">
                    (Grand Total - Other Sources)
                </span>
            </td>

            <td style="border:1px solid #1f6fb2;"></td>
            <td style="border:1px solid #1f6fb2;"></td>

            <td style="border:1px solid #1f6fb2; text-align:right;">
                {{ number_format($orgTotal, 2) }}
            </td>
        </tr>

    </table>

</div>

