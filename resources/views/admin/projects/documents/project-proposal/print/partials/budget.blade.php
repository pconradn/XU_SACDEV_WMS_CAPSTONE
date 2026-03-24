@php
    use Illuminate\Support\Str;

    // ================= SOURCES =================
    $sources = [
        'Finance Office',
        'PTA',
        'OSA-SACDEV',
        'Counterpart',
        'Solicitation',
        'Ticket-Selling',
        'Others',
    ];

    $existingFunds = $proposal?->fundSources
        ? $proposal->fundSources->pluck('amount', 'source_name')->toArray()
        : [];

    $total = array_sum($existingFunds);

    // ================= AUDIENCE =================
    $aud = $proposal->audience_type ?? null;

    $xuSubs = isset($proposal->xu_subtypes)
        ? explode(', ', $proposal->xu_subtypes)
        : [];
@endphp


<div style="border:1px solid #000; border-top:none; margin-bottom:10px;">

    {{-- ================= TOTAL BUDGET ================= --}}
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="border:1px solid #000; padding:6px;">
                <strong>Proposed Budget:</strong>
                <span style="font-size:11px;">(Total Amount)</span>
            </td>
        </tr>

        <tr>
            <td style="border:1px solid #000; padding:12px; text-align:center; font-size:16px; font-weight:bold;">
                Php {{ number_format($total, 2) }}
            </td>
        </tr>
    </table>


    {{-- ================= SOURCES + COUNTERPART ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>
            <td colspan="3" style="border:1px solid #000; padding:6px;">
                <strong>Sources of Funds:</strong>
                <span style="font-size:11px;">
                    (Breakdown) Please specify the total amount for each applicable category.
                </span>
            </td>
        </tr>

        <tr>

            {{-- COLUMN 1 --}}
            <td style="border:1px solid #000; padding:6px; width:33%; vertical-align:top;">
                @foreach(['Finance Office','PTA','OSA-SACDEV','Counterpart'] as $source)

                    @php $amount = $existingFunds[$source] ?? null; @endphp

                    <div style="margin-bottom:6px;">
                        {{ $amount !== null ? '☑' : '☐' }} {{ $source }}
                        @if($amount !== null)
                            — Php {{ number_format($amount, 2) }}
                        @endif
                    </div>

                @endforeach
            </td>

            {{-- COLUMN 2 --}}
            <td style="border:1px solid #000; padding:6px; width:33%; vertical-align:top;">
                @foreach(['Solicitation','Ticket-Selling','Others'] as $source)

                    @php $amount = $existingFunds[$source] ?? null; @endphp

                    <div style="margin-bottom:6px;">
                        {{ $amount !== null ? '☑' : '☐' }} {{ $source }}
                        @if($amount !== null)
                            — Php {{ number_format($amount, 2) }}
                        @endif
                    </div>

                @endforeach
            </td>

            {{-- COLUMN 3 --}}
            <td style="border:1px solid #000; padding:6px; width:34%; vertical-align:top;">

                <div style="font-size:11px; margin-bottom:6px;">
                    <strong>If with counterpart, how much are you collecting from each participant?</strong>
                </div>

                @php
                    $counterpartAmount = $existingFunds['Counterpart'] ?? null;
                @endphp

                <div>
                    {{ $counterpartAmount ? 'Php ' . number_format($counterpartAmount, 2) : '—' }}
                </div>

            </td>

        </tr>

    </table>


    {{-- ================= AUDIENCE + PARTICIPANTS ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>

            {{-- LEFT SIDE --}}
            <td style="border:1px solid #000; padding:6px; width:60%; vertical-align:top;">

                <strong>Target Audience/Beneficiaries:</strong>
                <span style="font-size:11px;">(Please tick all applicable.)</span>

                <div style="margin-top:6px;">

                    {{-- XU COMMUNITY --}}
                    <div>
                        {{ $aud === 'xu_community' ? '☑' : '☐' }} XU Community
                    </div>

                    <div style="margin-left:15px; font-size:12px;">
                        {{ in_array('Officers', $xuSubs) ? '☑' : '☐' }} Officers<br>
                        {{ in_array('Org Members', $xuSubs) ? '☑' : '☐' }} Org Members<br>
                        {{ in_array('Non-Org Members', $xuSubs) ? '☑' : '☐' }} Non-Org Members<br>
                        {{ in_array('Faculty/Staff', $xuSubs) ? '☑' : '☐' }} Faculty/Staff
                    </div>

                    {{-- NON XU --}}
                    <div style="margin-top:6px;">
                        {{ $aud === 'non_xu_community' ? '☑' : '☐' }} Non-XU Community
                    </div>

                    {{-- BENEFICIARIES --}}
                    <div style="margin-top:6px;">
                        {{ $aud === 'beneficiaries' ? '☑' : '☐' }} Beneficiaries
                    </div>

                    <div style="margin-top:6px; font-size:12px;">
                        {{ $proposal->audience_details ?? '' }}
                    </div>

                </div>

            </td>


            {{-- RIGHT SIDE --}}
            <td style="border:1px solid #000; padding:6px; width:40%; vertical-align:top;">

                <strong>Expected Number of Audience/Participants:</strong>

                <div style="margin-top:10px;">

                    <div style="margin-bottom:10px;">
                        ☑ XU Community — 
                        {{ $proposal->expected_xu_participants ?? '—' }}
                    </div>

                    <div>
                        ☑ Non-XU Community — 
                        {{ $proposal->expected_non_xu_participants ?? '—' }}
                    </div>

                </div>

            </td>

        </tr>

    </table>

</div>