<div style="border:1px solid #000; border-top:none; margin-bottom:10px;">

    {{-- ================= DESCRIPTION ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>
            <td style="border:1px solid #000; padding:6px;">

                <strong>
                    Brief Description of the Project
                </strong>

                <span style="font-size:11px;">
                    (In 1-2 sentences, please describe the nature or intent of the project.)
                </span>

            </td>
        </tr>

        <tr>
            <td style="border:1px solid #000; padding:10px; height:60px; vertical-align:top;">

                {{ $proposal->description ?? '—' }}

            </td>
        </tr>

    </table>


    {{-- ================= ORG LINK ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>
            <td style="border:1px solid #000; padding:6px;">

                <strong>
                    Link of the Project with the Organization
                </strong>

                <span style="font-size:11px;">
                    (State below the link of the project with the mission/purpose of the organization and the UAP.)
                </span>

            </td>
        </tr>

        <tr>
            <td style="border:1px solid #000; padding:10px; height:70px; vertical-align:top;">

                {{ $proposal->org_link ?? '—' }}

            </td>
        </tr>

    </table>

</div>