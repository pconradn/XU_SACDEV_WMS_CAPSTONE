<div style="border:1px solid #000; border-top:none; margin-bottom:10px;">

    {{-- ================= IMPLEMENTATION DATE ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>
            {{-- LEFT: DATE --}}
            <td style="border:1px solid #000; padding:6px; width:50%; vertical-align:top;">
                <strong>Proposed Implementation Date(s):</strong><br>

                <div style="font-size:11px; margin-top:4px;">
                    <span style="color:#2f6fb3;">Starting Date:</span><br>
                    {{ $proposal->start_date 
                        ? \Carbon\Carbon::parse($proposal->start_date)->format('M d, Y') 
                        : '—' }}
                </div>

                <div style="font-size:11px; margin-top:6px;">
                    <span style="color:#2f6fb3;">End Date:</span><br>
                    {{ $proposal->end_date 
                        ? \Carbon\Carbon::parse($proposal->end_date)->format('M d, Y') 
                        : '—' }}
                </div>
            </td>

            {{-- RIGHT: TIME --}}
            <td style="border:1px solid #000; padding:6px; width:50%; vertical-align:top;">
                <strong>Time:</strong>
                <span style="font-size:11px;">(Start and End)</span><br>

                <div style="margin-top:6px;">
                    {{ $proposal->start_time && $proposal->end_time 
                        ? \Carbon\Carbon::parse($proposal->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($proposal->end_time)->format('h:i A') 
                        : '—' }}
                </div>
            </td>
        </tr>

    </table>


    {{-- ================= VENUE ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>
            <td colspan="2" style="border:1px solid #000; padding:6px;">
                <strong>Proposed Venue:</strong><br>
                <span style="font-size:11px;">
                    For off-campus activities, please accomplish off-campus activity permit after approval of this proposal.
                </span>
            </td>
        </tr>

        <tr>
            <td style="border:1px solid #000; padding:6px; width:50%;">
                <span style="color:#2f6fb3;">On Campus:</span><br>
                {{ $proposal->on_campus_venue ?? '—' }}
            </td>

            <td style="border:1px solid #000; padding:6px; width:50%;">
                <span style="color:#2f6fb3;">Off-Campus:</span><br>
                {{ $proposal->off_campus_venue ?? '—' }}
            </td>
        </tr>

    </table>


    {{-- ================= ENGAGEMENT ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>

            {{-- LEFT: CHECKBOXES --}}
            <td style="border:1px solid #000; padding:6px; width:50%; vertical-align:top;">
                <strong>Nature of Engagement:</strong><br>

                <div style="margin-top:6px; font-size:12px;">

                    <label style="margin-right:12px;">
                        <input type="checkbox" disabled {{ $proposal->engagement_type === 'organizer' ? 'checked' : '' }}>
                        Organizer
                    </label>

                    <label style="margin-right:12px;">
                        <input type="checkbox" disabled {{ $proposal->engagement_type === 'partner' ? 'checked' : '' }}>
                        Partner
                    </label>

                    <label>
                        <input type="checkbox" disabled {{ $proposal->engagement_type === 'participant' ? 'checked' : '' }}>
                        Participant
                    </label>

                </div>
            </td>

            {{-- RIGHT: MAIN ORGANIZER --}}
            <td style="border:1px solid #000; padding:6px; width:50%; vertical-align:top;">
                <strong>If participant, state the main organizer below:</strong><br>

                <div style="margin-top:6px;">
                    {{ $proposal->main_organizer ?? '—' }}
                </div>
            </td>

        </tr>

    </table>

</div>