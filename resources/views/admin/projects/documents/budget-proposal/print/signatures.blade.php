@if($document && $document->signatures && $document->signatures->count())

@php
    $sigs = $document->signatures->keyBy('role');

    function sig($role, $sigs) {
        return $sigs[$role] ?? null;
    }

    function approvalLine($role, $sigs) {
        $s = $sigs[$role] ?? null;

        if (!$s || $s->status !== 'signed') {
            return '<div style="font-size:10px; color:#9ca3af;">Pending Approval</div>';
        }

        return '
            <div style="font-size:10px; color:#2f6fb3; font-weight:600;">
                ✔ Approved · '.$s->signed_at?->format('M d, Y h:i A').'
            </div>
        ';
    }
@endphp


<div style="border:1px solid #2f6fb3; margin-top:15px;">

    {{-- HEADER --}}
    <div style="
        background:#2f6fb3;
        color:#fff;
        text-align:center;
        font-weight:600;
        font-size:12px;
        padding:5px;
    ">
        SIGNATORIES
        <div style="font-size:10px; font-weight:400;">
            (System Approval Certification)
        </div>
    </div>


    {{-- GRID --}}
    <div style="display:grid; grid-template-columns:1fr 1fr;">

        {{-- PREPARED --}}
        <div style="border-right:1px solid #2f6fb3; padding:8px; page-break-inside: avoid;
break-inside: avoid;">

            <div style="font-size:11px;">
                <strong>Prepared by:</strong>
            </div>

            {!! approvalLine('project_head', $sigs) !!}

            <div style="margin-top:8px; font-weight:600;">
                {{ sig('project_head', $sigs)?->user?->name ?? '—' }}
            </div>

            <div style="font-size:11px;">
                Project Head
            </div>

        </div>


        {{-- PRESIDENT --}}
        <div style="padding:8px; page-break-inside: avoid;
break-inside: avoid;">

            <div style="font-size:11px;">
                <strong>Endorsed by:</strong>
            </div>

            {!! approvalLine('president', $sigs) !!}

            <div style="margin-top:8px; font-weight:600;">
                {{ sig('president', $sigs)?->user?->name ?? '—' }}
            </div>

            <div style="font-size:11px;">
                President
            </div>

        </div>

    </div>


    {{-- SECOND ROW --}}
    <div style="
        display:grid;
        grid-template-columns:1fr 1fr;
        border-top:1px solid #2f6fb3;
    ">

        {{-- TREASURER --}}
        <div style="padding:8px; border-right:1px solid #2f6fb3; page-break-inside: avoid;
break-inside: avoid;">

            {!! approvalLine('treasurer', $sigs) !!}

            <div style="margin-top:6px; font-weight:600;">
                {{ sig('treasurer', $sigs)?->user?->name ?? '—' }}
            </div>

            <div style="font-size:11px;">
                Budget and Finance Officer (Treasurer)
            </div>

        </div>


        {{-- MODERATOR --}}
        <div style="padding:8px; page-break-inside: avoid;
break-inside: avoid;">

            {!! approvalLine('moderator', $sigs) !!}

            <div style="margin-top:6px; font-weight:600;">
                {{ sig('moderator', $sigs)?->user?->name ?? '—' }}
            </div>

            <div style="font-size:11px;">
                Moderator
            </div>

        </div>

    </div>


    {{-- FINAL ROW --}}
    <div style="
        display:grid;
        grid-template-columns:1fr 1fr;
        border-top:1px solid #2f6fb3;
    ">

        {{-- SACDEV --}}
        <div style="padding:8px; border-right:1px solid #2f6fb3; page-break-inside: avoid;
break-inside: avoid;">

            <strong style="font-size:11px;">Approved by:</strong>

            {!! approvalLine('sacdev_admin', $sigs) !!}

            <div style="margin-top:6px; font-weight:600;">
                {{ sig('sacdev_admin', $sigs)?->user?->name ?? '—' }}
            </div>

            <div style="font-size:11px;">
                OSA-SACDEV
            </div>

        </div>


        {{-- REMARKS --}}
        <div style="padding:8px;">

            <strong style="font-size:11px;">Remarks:</strong>

            <div style="margin-top:6px; font-size:12px;">
                {{ $document->remarks ?? '—' }}
            </div>

        </div>

    </div>

</div>

@endif