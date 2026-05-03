<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Off-Campus Clearance</title>

<style>
body{
    font-family:"Times New Roman", serif;
    font-size:12px;
    color:#000;
}

.page{
    width:100%;
    max-width:800px;
    margin:auto;
}

.form-container{
    border:2px solid #2f6fb3;
}

.row{
    border-bottom:1px solid #2f6fb3;
    padding:5px 8px;
}

.row:last-child{
    border-bottom:none;
}

.header{
    text-align:center;
    margin-top:40px;
}

.title{
    font-size:18px;
    font-weight:bold;
}

.sub{
    font-size:11px;
}

.blue{
    background:#2f6fb3;
    color:#fff;
    font-weight:bold;
    text-align:center;
    padding:5px;
}

.grid-2{
    display:grid;
    grid-template-columns:1fr 1fr;
}

.grid-3{
    display:grid;
    grid-template-columns:1fr 1fr 1fr;
}

.grid-4{
    display:grid;
    grid-template-columns:1fr 1fr 1fr 1fr;
}

.bold{
    font-weight:bold;
}

.center{
    text-align:center;
}

.signature-grid{
    margin-top:30px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:30px 40px;
}

.sig-box{
    text-align:center;
}

.line{
    border-top:1px solid #000;
    margin-top:50px;
}

.sig-label{
    margin-top:5px;
    font-size:11px;
}

.verify-box{
    margin-top:20px;
    border:1px dashed #2f6fb3;
    padding:10px;
    text-align:center;
}

.footer{
    margin-top:20px;
    font-size:10px;
    text-align:center;
}

.print-bar{
    max-width:800px;
    margin:0 auto 16px auto;
    display:flex;
    justify-content:flex-end;
}

.print-button{
    border:1px solid #2f6fb3;
    background:#2f6fb3;
    color:#fff;
    padding:8px 14px;
    font-size:12px;
    font-weight:bold;
    cursor:pointer;
    border-radius:4px;
}

@media print{
    *{
        -webkit-print-color-adjust:exact !important;
        print-color-adjust:exact !important;
    }

    .print-bar{
        display:none !important;
    }
}
</style>
</head>

<body>

<div class="print-bar">
    <button type="button" onclick="window.print()" class="print-button">
        Print Clearance
    </button>
</div>

<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <div>Xavier University – Ateneo de Cagayan</div>
        <div class="title">OFF-CAMPUS ACTIVITY CLEARANCE</div>
        <div class="sub">Reference: {{ $project->clearance_reference }}</div>
    </div>

    <div class="form-container">

        {{-- PROJECT INFO --}}
        <div class="blue">PROJECT INFORMATION</div>

        <div class="row">
            <span class="bold">Organization:</span>
            {{ $snapshot['organization'] ?? '—' }}
        </div>

        <div class="row">
            <span class="bold">Project Title:</span>
            {{ $snapshot['title'] ?? '—' }}
        </div>

        <div class="row grid-2">
            <div>
                <span class="bold">Start Date:</span><br>
                {{ $snapshot['start_date'] 
                    ? \Carbon\Carbon::parse($snapshot['start_date'])->format('M d, Y') 
                    : '—' }}
            </div>
            <div>
                <span class="bold">End Date:</span><br>
                {{ $snapshot['end_date'] 
                    ? \Carbon\Carbon::parse($snapshot['end_date'])->format('M d, Y') 
                    : '—' }}
            </div>
        </div>

        <div class="row">
            <span class="bold">Time:</span>
            {{ $snapshot['start_time'] && $snapshot['end_time']
                ? \Carbon\Carbon::parse($snapshot['start_time'])->format('h:i A') . ' - ' .
                  \Carbon\Carbon::parse($snapshot['end_time'])->format('h:i A')
                : '—' }}
        </div>

        <div class="row grid-2">
            <div>
                <span class="bold">On-Campus Venue:</span><br>
                {{ $snapshot['on_campus_venue'] ?? '—' }}
            </div>
            <div>
                <span class="bold">Off-Campus Venue:</span><br>
                {{ $snapshot['off_campus_venue'] ?? '—' }}
            </div>
        </div>

        <div class="row">
            <span class="bold">Description:</span><br>
            {{ $proposal->description ?? '—' }}
        </div>


        <div class="row center" style="font-size:15px; font-weight:bold;">
            TOTAL PROJECT BUDGET: 
            ₱ {{ number_format(
                $proposal->budgetDocument->budgetproposalData->total_expenses ?? 0,
                2
            ) }}
        </div>

        {{-- CLEARANCE --}}
        <div class="blue">CLEARANCE CONFIRMATION</div>

        <div class="row" style="text-align:justify;">
            This is to certify that the above-mentioned organization has complied with all required 
            documentation and approval processes for the conduct of the off-campus activity. 
            The University hereby grants clearance for implementation, subject to institutional 
            policies and safety guidelines.
        </div>



        <div class="blue">PROPOSAL APPROVAL STATUS</div>

        @php
        $signatories = collect($snapshot['signatories'] ?? []);

        function sig($role, $signatories) {
            return $signatories->firstWhere('role', $role);
        }

        function sigName($sig) {
            return $sig['name'] ?? '—';
        }

        function sigDate($sig) {
            return !empty($sig['signed_at'])
                ? \Carbon\Carbon::parse($sig['signed_at'])->format('M d, Y h:i A')
                : '—';
        }
        @endphp

        <div class="row" style="font-size:11px; line-height:1.4;">

            <div style="margin-bottom:6px; text-align:justify;">
                This certifies that the project proposal for the above-mentioned activity has been duly reviewed and approved within the system by the following authorized signatories:
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; column-gap:20px;">

                <div>
                    @php $s = sig('project_head', $signatories); @endphp
                    <strong>Project Head:</strong> {{ sigName($s) }} <br>
                    <span style="font-size:10px;">Approved on: {{ sigDate($s) }}</span>

                    <br><br>

                    @php $s = sig('moderator', $signatories); @endphp
                    <strong>Moderator:</strong> {{ sigName($s) }} <br>
                    <span style="font-size:10px;">Approved on: {{ sigDate($s) }}</span>
                </div>

                <div>
                    @php $s = sig('president', $signatories); @endphp
                    <strong>Organization President:</strong> {{ sigName($s) }} <br>
                    <span style="font-size:10px;">Approved on: {{ sigDate($s) }}</span>

                    <br><br>

                    @php $s = sig('sacdev_admin', $signatories); @endphp
                    <strong>SACDEV Head:</strong> {{ sigName($s) }} <br>
                    <span style="font-size:10px;">Approved on: {{ sigDate($s) }}</span>
                </div>

            </div>

            <div style="margin-top:6px; text-align:justify;">
                All approvals were recorded digitally and are included as part of the official system-generated clearance record.
            </div>

        </div>



    
        <div class="blue">CLEARANCE APPROVAL</div>

        <div class="row">
            <div class="signature-grid">

                <div class="sig-box">
                    <div class="line"></div>
                    <div style="font-size:11px; font-weight:bold;"></div>
                    <div class="sig-label">Department Chair / Dean</div>
                </div>

            </div>
        </div>






    </div>

    {{-- VERIFICATION --}}
    <div class="verify-box">

        <div style="font-weight:bold; margin-bottom:5px;">
            DIGITAL VERIFICATION
        </div>

        <div style="font-size:11px; margin-bottom:10px;">
            This document is system-generated and digitally verifiable.
            Scan the QR code below to confirm authenticity.
        </div>

        {!! QrCode::size(110)->generate($verificationUrl) !!}

        <div style="font-size:10px; margin-top:6px;">
            {{ $verificationUrl }}
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Reference: {{ $project->clearance_reference }} <br>
        Issued: {{ $snapshot['issued_at'] 
            ? \Carbon\Carbon::parse($snapshot['issued_at'])->format('F d, Y h:i A') 
            : '—' }}
    </div>

</div>

</body>
</html>