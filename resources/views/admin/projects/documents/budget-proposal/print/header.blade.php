<div style="margin-bottom:15px;">

    {{-- TOP LEFT NOTES --}}
    <div style="display:flex; justify-content:space-between; align-items:flex-start; padding:6px 10px; font-size:11px;">

        {{-- LEFT --}}
        <div style="font-size:11px; margin-bottom:10px;">
            <em></em><br>
            <em>Please use A4 paper.</em>
        </div>

        {{-- RIGHT (STACKED) --}}
        <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">

            {{-- QR --}}
            @if($document->verification_url)
                <div style="text-align:center;">
                    {!! QrCode::size(70)->generate($document->verification_url) !!}

                    <div style="font-size:9px; margin-top:2px; color:#555;">
                        Scan to verify
                    </div>
                </div>
            @endif

        </div>

    </div>

    {{-- TITLE --}}
    <div style="text-align:center; margin-bottom:6px; margin-top:-20px">
        <div style="font-size:20px; font-weight:bold; letter-spacing:0.5px;">
            BUDGET PROPOSAL
        </div>
    </div>

    {{-- INSTRUCTIONS --}}
    <div style="text-align:center; font-size:11px; color:#1d4ed8; margin-bottom:10px;">
        <em>
            Project details are contained in Form A1 Project Proposal. Please attach this form to Form A1.<br>
            Insert rows on the appropriate section and verify/adjust formulas on "E" and "F" as may be necessary.
        </em>
    </div>

    {{-- INFO TABLE --}}
    <table style="width:100%; border-collapse:collapse; font-size:12px;">

        <tr>
            <td style="width:180px; background:#1f6fb2; color:#fff; font-weight:bold; padding:6px;">
                Name of Project:
            </td>
            <td style="border:1px solid #1f6fb2; padding:6px;">
                {{ $project->title }}
            </td>
        </tr>

        <tr>
            <td style="background:#1f6fb2; color:#fff; font-weight:bold; padding:6px;">
                Implementation Date:
            </td>
            <td style="border:1px solid #1f6fb2; padding:6px;">
                {{ $project->implementation_date_display ?? '—' }}
            </td>
        </tr>

        <tr>
            <td style="background:#1f6fb2; color:#fff; font-weight:bold; padding:6px;">
                Venue:
            </td>
            <td style="border:1px solid #1f6fb2; padding:6px;">
                {{ $project->implementation_venue ?? '—' }}
            </td>
        </tr>

    </table>

</div>