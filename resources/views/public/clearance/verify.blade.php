<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Clearance Verification</title>

<style>
body{
    font-family:"Segoe UI", Arial;
    background:#f8fafc;
    margin:0;
    padding:40px;
}

.container{
    max-width:750px;
    margin:auto;
    background:#fff;
    border-radius:14px;
    padding:28px;
    box-shadow:0 12px 30px rgba(0,0,0,0.06);
}

.header{
    text-align:center;
    margin-bottom:20px;
}

.title{
    font-size:20px;
    font-weight:700;
}

.subtitle{
    font-size:12px;
    color:#64748b;
}

.status{
    padding:12px;
    border-radius:10px;
    margin-bottom:20px;
    font-size:13px;
    font-weight:600;
}

.valid{
    background:#ecfdf5;
    color:#065f46;
}

.invalid{
    background:#fef2f2;
    color:#991b1b;
}

.revoked{
    background:#fff7ed;
    color:#9a3412;
}

.section{
    margin-top:20px;
    border-top:1px solid #e2e8f0;
    padding-top:15px;
}

.row{
    margin-bottom:8px;
    font-size:13px;
}

.label{
    font-weight:600;
    color:#334155;
}

.value{
    margin-left:4px;
    color:#0f172a;
}

.footer{
    margin-top:25px;
    font-size:11px;
    color:#64748b;
    text-align:center;
}

.badge{
    display:inline-block;
    padding:3px 8px;
    border-radius:6px;
    font-size:11px;
    margin-left:6px;
}

.badge-green{ background:#dcfce7; color:#166534; }
.badge-red{ background:#fee2e2; color:#991b1b; }
.badge-orange{ background:#ffedd5; color:#9a3412; }
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <div class="title">Off-Campus Clearance Verification</div>
        <div class="subtitle">
            Xavier University – Ateneo de Cagayan
        </div>
    </div>

    {{-- STATUS --}}
    @if(!$isValid)
        <div class="status invalid">
            INVALID CLEARANCE — Data mismatch or tampered document.
        </div>
    @elseif($isRevoked)
        <div class="status revoked">
            REVOKED CLEARANCE — This clearance is no longer valid.
        </div>
    @elseif($isReplaced)
        <div class="status revoked">
            REPLACED CLEARANCE — A newer clearance has been issued.
        </div>
    @else
        <div class="status valid">
            VALID CLEARANCE — Verified against official university records.
        </div>
    @endif


    {{-- CLEARANCE DETAILS --}}
    <div class="section">

        <div class="row">
            <span class="label">Reference:</span>
            <span class="value">{{ $snapshot['reference'] ?? '—' }}</span>
        </div>

        <div class="row">
            <span class="label">Organization:</span>
            <span class="value">{{ $snapshot['organization'] ?? '—' }}</span>
        </div>

        <div class="row">
            <span class="label">Project Title:</span>
            <span class="value">{{ $snapshot['title'] ?? '—' }}</span>
        </div>

        <div class="row">
            <span class="label">Activity Date:</span>
            <span class="value">
                {{ $snapshot['start_date'] 
                    ? \Carbon\Carbon::parse($snapshot['start_date'])->format('F d, Y') 
                    : '—' }}
                —
                {{ $snapshot['end_date'] 
                    ? \Carbon\Carbon::parse($snapshot['end_date'])->format('F d, Y') 
                    : '—' }}
            </span>
        </div>

        <div class="row">
            <span class="label">Time:</span>
            <span class="value">
                {{ $snapshot['start_time'] && $snapshot['end_time']
                    ? \Carbon\Carbon::parse($snapshot['start_time'])->format('h:i A') . ' - ' .
                      \Carbon\Carbon::parse($snapshot['end_time'])->format('h:i A')
                    : '—' }}
            </span>
        </div>

        <div class="row">
            <span class="label">Off-Campus Venue:</span>
            <span class="value">{{ $snapshot['off_campus_venue'] ?? '—' }}</span>
        </div>
        <div class="row">
            <span class="label">Total Budget:</span>
            <span class="value">
                {{ isset($snapshot['total_budget']) 
                    ? '₱ ' . number_format($snapshot['total_budget'], 2) 
                    : '—' }}
            </span>
        </div>

    </div>


    {{-- SIGNATORIES --}}
    <div class="section">

        <div class="row">
            <span class="label">Approved By:</span>
        </div>

        @if(!empty($snapshot['signatories']))

            @foreach($snapshot['signatories'] as $sig)

                @if($sig['status'] === 'signed')

                    <div class="row">
                        <span class="value">
                            {{ strtoupper(str_replace('_',' ', $sig['role'])) }} —
                            {{ $sig['name'] }}

                            <span style="color:#64748b; font-size:11px;">
                                ({{ $sig['signed_at'] 
                                    ? \Carbon\Carbon::parse($sig['signed_at'])->format('M d, Y h:i A') 
                                    : '—' }})
                            </span>
                        </span>
                    </div>

                @endif

            @endforeach

        @else

            <div class="row">
                <span class="value">— No signatories recorded</span>
            </div>

        @endif

    </div>


    {{-- STATUS INFO --}}
    <div class="section">

        <div class="row">
            <span class="label">Clearance Status:</span>

            @if($isValid && !$isRevoked && !$isReplaced)
                <span class="badge badge-green">VALID</span>
            @elseif($isRevoked)
                <span class="badge badge-orange">REVOKED</span>
            @elseif($isReplaced)
                <span class="badge badge-orange">REPLACED</span>
            @else
                <span class="badge badge-red">INVALID</span>
            @endif

        </div>

        <div class="row">
            <span class="label">Issued At:</span>
            <span class="value">
                {{ $snapshot['issued_at']
                    ? \Carbon\Carbon::parse($snapshot['issued_at'])->format('F d, Y h:i A')
                    : '—' }}
            </span>
        </div>

    </div>


    {{-- INTEGRITY --}}
    <div class="section">

        <div class="row">
            <span class="label">Verification Method:</span>
            <span class="value">
                HMAC-SHA256 Secure Token Validation
            </span>
        </div>

        <div class="row">
            <span class="label">Token:</span><br>
            <small>{{ $token ?? '—' }}</small>
        </div>

        <div class="row" style="margin-top:8px;">
            This verification confirms that the clearance details match the officially issued record stored in the system.
        </div>

    </div>


    <div class="footer">
        Generated by SACDEV Clearance Verification System <br>
        This page serves as the official verification of document authenticity.
    </div>

</div>

</body>
</html>