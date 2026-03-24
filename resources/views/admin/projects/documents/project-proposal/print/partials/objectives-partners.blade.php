<div style="border:1px solid #000; border-top:none; margin-bottom:10px;">

    {{-- ================= ORG CLUSTER ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>
            <td style="border:1px solid #000; padding:6px; width:30%;">
                <strong>Your Org Cluster:</strong>
            </td>

            <td style="border:1px solid #000; padding:6px;">
                {{ $proposal->org_cluster ?? '—' }}
            </td>
        </tr>

    </table>


    {{-- ================= OBJECTIVES + INDICATORS ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>

            {{-- OBJECTIVES --}}
            <td style="border:1px solid #000; padding:6px; width:50%; vertical-align:top;">

                <strong>Objectives:</strong><br>
                <span style="font-size:11px;">
                    By the end of the project…
                </span>

                <ol style="margin-top:6px; padding-left:18px;">
                    @forelse($proposal->objectives as $obj)
                        <li style="margin-bottom:4px;">
                            {{ $obj->objective }}
                        </li>
                    @empty
                        <li>—</li>
                    @endforelse
                </ol>

            </td>


            {{-- INDICATORS --}}
            <td style="border:1px solid #000; padding:6px; width:50%; vertical-align:top;">

                <strong>Targets/Success Indicators:</strong><br>
                <span style="font-size:11px;">
                    (What will determine whether you have achieved your objectives or not)
                </span>

                <ol style="margin-top:6px; padding-left:18px;">
                    @forelse($proposal->indicators as $ind)
                        <li style="margin-bottom:4px;">
                            {{ $ind->indicator }}
                        </li>
                    @empty
                        <li>—</li>
                    @endforelse
                </ol>

            </td>

        </tr>

    </table>


    {{-- ================= PARTNERS + ROLES ================= --}}
    <table style="width:100%; border-collapse:collapse;">

        <tr>

            {{-- PARTNERS --}}
            <td style="border:1px solid #000; padding:6px; width:50%; vertical-align:top;">

                <strong>Target Partners/Sponsors:</strong>

                <ol style="margin-top:6px; padding-left:18px;">
                    @forelse($proposal->partners as $p)
                        <li style="margin-bottom:4px;">
                            {{ $p->name }}
                        </li>
                    @empty
                        <li>—</li>
                    @endforelse
                </ol>

            </td>


            {{-- ROLES --}}
            <td style="border:1px solid #000; padding:6px; width:50%; vertical-align:top;">

                <strong>Role Specific to the Project:</strong>

                <ol style="margin-top:6px; padding-left:18px;">
                    @forelse($proposal->roles as $r)
                        <li style="margin-bottom:4px;">
                            {{ $r->role_name }}
                        </li>
                    @empty
                        <li>—</li>
                    @endforelse
                </ol>

            </td>

        </tr>

    </table>

</div>