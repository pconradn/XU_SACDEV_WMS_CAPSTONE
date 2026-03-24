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
                <div style="margin-top:10px; font-size:11px; color:#6b7280;">
                    Digitally Approved via SACDEV System<br>
                    Status: Pending
                </div>
            ';
        }

        return '
            <div style="
                margin-top:10px;
                border-top:1px dashed #000;
                padding-top:6px;
                font-size:11px;
                line-height:1.4;
            ">
                <div><strong>Digitally Approved via SACDEV System</strong></div>
                <div>User: '.$s->user?->name.'</div>
                <div>Role: '.ucwords(str_replace('_',' ', $s->role)).'</div>
                <div>Timestamp: '.$s->signed_at?->format('F d, Y h:i A').'</div>
            </div>
        ';
    }

@endphp

<div style="border:1px solid #000; border-top:none; margin-top:10px;">

        {{-- ================= PROJECT REFERENCE ================= --}}
    <div style="border:1px solid #000; border-bottom:none; padding:8px;">

        <div style="text-align:center; font-weight:bold; font-size:13px;">
            {{ $project->title }}
        </div>

        <div style="text-align:center; font-size:11px; margin-top:2px;">
            {{ $proposal->start_date 
                ? \Carbon\Carbon::parse($proposal->start_date)->format('M d, Y') 
                : '—' }}
            —
            {{ $proposal->end_date 
                ? \Carbon\Carbon::parse($proposal->end_date)->format('M d, Y') 
                : '—' }}
        </div>

    </div>
    {{-- HEADER --}}
    <div style="background:#2f6fb3; color:#fff; text-align:center; font-weight:bold; padding:6px;">
        SIGNATORIES
        <div style="font-size:10px; font-weight:normal;">
            (System Approval Certification)
        </div>
    </div>

    <table style="width:100%; border-collapse:collapse;">

        <tr>

            {{-- PREPARED --}}
            <td style="border:1px solid #000; padding:10px; width:50%; vertical-align:top;">

                <strong>Prepared by:</strong>

                <div style="margin-top:30px; font-weight:bold;">
                    {{ sig('project_head', $sigs)?->user?->name ?? '—' }}
                </div>

                <div style="font-size:12px;">
                    Project Head
                </div>

                {!! digitalApproval('project_head', $sigs) !!}

            </td>


            {{-- ENDORSED --}}
            <td style="border:1px solid #000; padding:0; width:50%;">

                <div style="padding:10px;">
                    <strong>Endorsed by:</strong>
                </div>

                <table style="width:100%; border-collapse:collapse;">

                    <tr>
                        <td style="border:1px solid #000; padding:10px;">

                            <div style="margin-top:20px; font-weight:bold;">
                                {{ sig('auditor', $sigs)?->user?->name ?? '—' }}
                            </div>

                            <div style="font-size:12px;">
                                Budget and Finance Officer
                            </div>

                            {!! digitalApproval('auditor', $sigs) !!}

                        </td>

                        <td style="border:1px solid #000; padding:10px;">

                            <div style="margin-top:20px; font-weight:bold;">
                                {{ sig('treasurer', $sigs)?->user?->name ?? '—' }}
                            </div>

                            <div style="font-size:12px;">
                                Treasurer
                            </div>

                            {!! digitalApproval('treasurer', $sigs) !!}

                        </td>
                    </tr>

                    <tr>
                        <td style="border:1px solid #000; padding:10px;">

                            <div style="margin-top:20px; font-weight:bold;">
                                {{ sig('president', $sigs)?->user?->name ?? '—' }}
                            </div>

                            <div style="font-size:12px;">
                                President
                            </div>

                            {!! digitalApproval('president', $sigs) !!}

                        </td>

                        <td style="border:1px solid #000; padding:10px;">

                            <div style="margin-top:20px; font-weight:bold;">
                                {{ sig('moderator', $sigs)?->user?->name ?? '—' }}
                            </div>

                            <div style="font-size:12px;">
                                Moderator
                            </div>

                            {!! digitalApproval('moderator', $sigs) !!}

                        </td>
                    </tr>

                </table>

            </td>

        </tr>


        {{-- APPROVAL --}}
        <tr>

            <td style="border:1px solid #000; padding:10px; vertical-align:top;">

                <strong>Approved by:</strong>

                <div style="margin-top:30px; font-weight:bold;">
                    {{ sig('sacdev_admin', $sigs)?->user?->name ?? '—' }}
                </div>

                <div style="font-size:12px;">
                    OSA-SACDEV
                </div>

                {!! digitalApproval('sacdev_admin', $sigs) !!}

            </td>

            <td style="border:1px solid #000; padding:10px; vertical-align:top;">

                <strong>OSA-SACDEV Remarks</strong>

                <div style="margin-top:15px;">
                    {{ $document->remarks ?? '—' }}
                </div>

            </td>

        </tr>

    </table>

    {{-- DOCUMENT ID --}}
    <div style="font-size:10px; text-align:right; padding:5px;">
        Document ID: PP-{{ $document->id }}
    </div>

</div>

@endif