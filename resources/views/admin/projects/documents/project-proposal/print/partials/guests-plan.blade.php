@php
    use Carbon\Carbon;

    $hasGuests = $proposal->has_guest_speakers ?? false;

    $guests = $proposal->guests ?? collect();
    $plans  = $proposal->planOfActions ?? collect();
@endphp


<div style="border:1px solid #000; border-top:none; margin-bottom:10px;">

    {{-- ================= GUESTS ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>
            <td style="border:1px solid #000; padding:6px; width:50%;">
                <strong>Participation of Guests/Speakers/Dignitaries Required?</strong><br>

                <div style="margin-top:6px;">
                    {{ $hasGuests ? '☑' : '☐' }} Yes
                    &nbsp;&nbsp;&nbsp;
                    {{ !$hasGuests ? '☑' : '☐' }} No
                </div>
            </td>

            <td style="border:1px solid #000; padding:6px; width:50%; vertical-align:top;">
                <strong>If yes, please list down guests from inside and outside XU:</strong><br>
                <span style="font-size:11px;">
                    Full Name, Affiliation, and Designation
                </span>

                <div style="margin-top:6px;">

                    @forelse($guests as $g)
                        <div style="margin-bottom:4px;">
                            • {{ $g->full_name }}
                            @if($g->affiliation) — {{ $g->affiliation }} @endif
                            @if($g->designation) ({{ $g->designation }}) @endif
                        </div>
                    @empty
                        —
                    @endforelse

                </div>

            </td>
        </tr>

    </table>


    {{-- ================= FOOTNOTE ================= --}}
    <div style="font-size:10px; padding:6px;">
        This form is a property of the Office of Student Affairs – Student Activities and Leadership Development,
        Xavier University – Ateneo de Cagayan. No part of this form may be copied, reproduced, disseminated or
        published without prior written permission from the office.
    </div>


    {{-- ================= PLAN OF ACTION TITLE ================= --}}
    <div style="text-align:center; font-weight:bold; padding:6px; border-top:1px solid #000;">
        PLAN OF ACTION
    </div>

    <div style="font-size:10px; padding:6px;">
        Note: For on-campus activities, please state the program flow. For off-campus activities organized by your org,
        please state the itinerary first and then the program flow. For participation in off-campus activities organized
        by other entities, please state the itinerary and attach the invitation and program flow from the organizers.
    </div>


    {{-- ================= PLAN TABLE ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        {{-- HEADER --}}
        <tr style="background:#2f6fb3; color:#fff; text-align:center; font-weight:bold;">
            <td style="border:1px solid #000; padding:6px;">Date</td>
            <td style="border:1px solid #000; padding:6px;">Time</td>
            <td style="border:1px solid #000; padding:6px;">Activity/Particulars</td>
            <td style="border:1px solid #000; padding:6px;">Venue</td>
        </tr>

        {{-- DATA --}}
        @forelse($plans as $p)
            <tr>
                <td style="border:1px solid #000; padding:6px;">
                    {{ $p->date ? Carbon::parse($p->date)->format('M d, Y') : '—' }}
                </td>

                <td style="border:1px solid #000; padding:6px;">
                    {{ $p->time ? Carbon::parse($p->time)->format('h:i A') : '—' }}
                </td>

                <td style="border:1px solid #000; padding:6px;">
                    {{ $p->activity ?? '—' }}
                </td>

                <td style="border:1px solid #000; padding:6px;">
                    {{ $p->venue ?? '—' }}
                </td>
            </tr>
        @empty

            {{-- EMPTY ROWS FOR PRINT LOOK --}}
            @for($i = 0; $i < 3; $i++)
                <tr>
                    <td style="border:1px solid #000; padding:12px;">&nbsp;</td>
                    <td style="border:1px solid #000;"></td>
                    <td style="border:1px solid #000;"></td>
                    <td style="border:1px solid #000;"></td>
                </tr>
            @endfor

        @endforelse

    </table>

</div>