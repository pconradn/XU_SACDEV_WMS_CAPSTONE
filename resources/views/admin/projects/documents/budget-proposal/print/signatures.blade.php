@if($document && $document->signatures && $document->signatures->count())

@php
    $sigs = $document->signatures->keyBy('role');

    function sig($role, $sigs) {
        return $sigs[$role] ?? null;
    }

    function digitalApproval($role, $sigs) {
        $s = $sigs[$role] ?? null;

        if (!$s || $s->status !== 'signed') {
            return '
                <div style="margin-top:8px; font-size:10px; color:#6b7280;">
                    Digitally Approved via SACDEV System<br>
                    Status: Pending
                </div>
            ';
        }

        return '
            <div style="
                margin-top:8px;
                border-top:1px dashed #000;
                padding-top:5px;
                font-size:10px;
                line-height:1.4;
            ">
                <div><strong>Digitally Approved via SACDEV System</strong></div>
                <div>'.$s->user?->name.'</div>
                <div>'.ucwords(str_replace('_',' ', $s->role)).'</div>
                <div>'.$s->signed_at?->format('M d, Y h:i A').'</div>
            </div>
        ';
    }
@endphp


<div style="margin-top:20px;">

    {{-- HEADER NOTE --}}
    <div style="font-size:11px; margin-bottom:10px;">
        <em>Original Signatures Required:</em>
    </div>

    <table style="width:100%; border-collapse:collapse; font-size:11px;">

        {{-- ROW 1 --}}
        <tr>

            {{-- PREPARED --}}
            <td style="width:50%; padding:15px; vertical-align:top;">

                <div>Prepared by:</div>

                <div style="margin-top:40px; font-weight:bold;">
                    SIGNATURE OVER FULL NAME
                </div>

                <div>
                    Project Head
                </div>

                {!! digitalApproval('project_head', $sigs) !!}

            </td>


            {{-- ENDORSED --}}
            <td style="width:50%; padding:15px; vertical-align:top;">

                <div>Endorsed by:</div>

                {{-- PRESIDENT --}}
                <div style="margin-top:30px; font-weight:bold;">
                    SIGNATURE OVER FULL NAME
                </div>

                <div>President</div>

                {!! digitalApproval('president', $sigs) !!}

            </td>

        </tr>


        {{-- ROW 2 --}}
        <tr>

            {{-- TREASURER (BUDGET & FINANCE) --}}
            <td style="padding:15px; vertical-align:top;">

                <div>Endorsed by:</div>

                <div style="margin-top:30px; font-weight:bold;">
                    SIGNATURE OVER FULL NAME
                </div>

                <div>Budget and Finance Officer (Treasurer)</div>

                {!! digitalApproval('treasurer', $sigs) !!}

            </td>


            {{-- MODERATOR --}}
            <td style="padding:15px; vertical-align:top;">

                <div>&nbsp;</div>

                <div style="margin-top:30px; font-weight:bold;">
                    SIGNATURE OVER FULL NAME
                </div>

                <div>Moderator</div>

                {!! digitalApproval('moderator', $sigs) !!}

            </td>

        </tr>


        {{-- ROW 3 --}}
        <tr>

            {{-- APPROVED --}}
            <td style="padding:15px; vertical-align:top;">

                <div>Approved by:</div>

                <div style="margin-top:30px; font-weight:bold;">
                    FULL NAME OVER SIGNATURE
                </div>

                <div>OSA-SACDEV</div>

                {!! digitalApproval('sacdev_admin', $sigs) !!}

            </td>


            {{-- REMARKS --}}
            <td style="padding:15px; vertical-align:top;">

                <div><strong>Remarks:</strong></div>

                <div style="margin-top:20px;">
                    {{ $document->remarks ?? '—' }}
                </div>

            </td>

        </tr>

    </table>

</div>

@endif