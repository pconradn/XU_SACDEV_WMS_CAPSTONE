<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Application for Solicitation</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
        }

        .page {
            width: 100%;
            max-width: 700px;
            margin: auto;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 20px;
        }

        .header-left {
            display: flex;
            gap: 10px;
        }

        .logo-box {
            width: 50px;
            height: 50px;
            border: 1px solid #000;
        }

        .header-text {
            font-size: 11px;
            line-height: 1.4;
        }

        .header-text strong {
            font-size: 13px;
        }

        .form-code {
            background: #2f6fb3;
            color: #fff;
            font-weight: bold;
            padding: 4px 10px;
            font-size: 12px;
        }

        /* TITLE */
        .title {
            text-align: center;
            margin-top: 10px;
        }

        .title h2 {
            font-size: 15px;
            margin: 0;
            font-weight: bold;
        }

        .title span {
            font-size: 11px;
        }

        /* ROW SYSTEM */
        .row {
            border-bottom: 1px solid #2f6fb3;
            min-height: 35px;
            padding: 6px 8px;
        }

        .row:first-child {
            border-top: 1px solid #2f6fb3;
        }

        /* PRINT */
        @media print {
            body { margin: 0; }
            .print-btn { display: none; }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>


@php
    $activityName = old('activity_name', $document->activity_name ?? $project->title);
    $purpose = old('purpose', $document->solicitationData->purpose ?? '');
    $durationFrom = old('duration_from', $document->solicitationData->duration_from ?? '');
    $durationTo = old('duration_to', $document->solicitationData->duration_to ?? '');
    $targetAmount = old('target_amount', $document->solicitationData->target_amount ?? '');
    $letterCount = old('desired_letter_count', $document->solicitationData->desired_letter_count ?? '');
    $link = old('letter_draft_link', $document->solicitationData->letter_draft_link ?? '');
    $data = $document->solicitationData
@endphp
@php
    $batch = $document->solicitationBatches?->first();
@endphp


<body>

{{-- PRINT BUTTON --}}
<div style="display:flex; justify-content:flex-end; max-width:800px; margin:10px auto;">
    <button onclick="window.print()" class="print-btn"
        style="padding:8px 16px; background:#2f6fb3; color:white; font-size:12px; border:none; border-radius:6px;">
        Print Document
    </button>
</div>

<div class="page">

    {{-- HEADER --}}
<div class="header" style="display:flex; justify-content:space-between; align-items:flex-start;">

    {{-- LEFT --}}
    <div class="header-left" style="display:flex; align-items:center; gap:10px;">

        <div class="logo-box"></div>

        <div class="header-text">
            <strong>STUDENT ACTIVITIES AND LEADERSHIP DEVELOPMENT</strong><br>
            Office of Student Affairs, Xavier University – Ateneo de Cagayan<br>
            Rm 204, 2F Magis Student Complex (Tel) 853-9800 local 9245
        </div>

    </div>

    {{-- RIGHT (STACKED) --}}
    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">

        {{-- FORM CODE --}}
        <div class="form-code">
            Form A6 (2026)
        </div>

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
    <div class="title" style="margin-top: -30px">
        <h2>APPLICATION FOR SOLICITATION / SPONSORSHIP</h2>
        <span>(Please accomplish 2 copies.)</span>
    </div>

    {{-- FORM START --}}
    <div class="form-container" style="margin-top:15px;">

        
        {{-- ROW --}}
        <div class="row" style="padding:0; min-height:unset; border-bottom: 2px transparent;">

            <div style="
                display:grid;
                grid-template-columns: 30% 70%;
                width:100%;
            ">

                {{-- LABEL --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:1px 6px;
                ">
                    Name of Organization:
                </div>



            </div>

        </div>

        {{-- ROW --}}
        <div class="row" style="padding:0; min-height:unset;">

            <div style="
                display:grid;
                grid-template-columns: 100%;
                width:100%;
            ">

                {{-- VALUE --}}
                <div style="
                    padding:8px 12px;
                    font-size:13px;
                    color:#000;
                ">
                    {{ $project->organization->name ?? '—' }}
                </div>


            </div>

        </div>

        {{-- ROW --}}
        <div class="row" style="padding:0; min-height:unset; border-bottom: 2px transparent;">

            <div style="
                display:grid;
                grid-template-columns: 40% 60%;
                width:100%;
            ">

                {{-- LABEL --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:1px 6px;
                ">
                    Name of activity where solicitation is needed:
                </div>

                {{-- EMPTY (value column space placeholder) --}}
                <div></div>

            </div>

        </div>

        {{-- ROW --}}
        <div class="row" style="padding:0; min-height:unset;">

            <div style="
                padding:10px 12px;
                font-size:13px;
                color:#000;
            ">

                @if(!empty($document->proposalData->activity_name))
                    {{ $document->proposalData->activity_name }}
                @else
                    <span style="color:#000;">
                        {{ $activityName }}
                    </span>
                @endif

            </div>

        </div>


        {{-- ROW --}}
        <div class="row" style="padding:0; min-height:unset; border-bottom: 2px transparent;">

            <div style="
                display:grid;
                grid-template-columns: 30% 70%;
                width:100%;
            ">

                {{-- LEFT LABEL --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:1px 6px;
                ">
                    Purpose:
                </div>

                {{-- RIGHT INSTRUCTION --}}
                <div style="
                    font-size:11px;
                    color:#2f6fb3;
                    padding:1px 6px;
                    font-style:italic;
                ">
                    (Please state the reason/ justification why there is a need for solicitation.)
                </div>

            </div>

        </div>

        {{-- ROW --}}
        <div class="row" style="padding:0; min-height:unset;">

            <div style="
                display:grid;
                grid-template-columns: 100%;
                width:100%;
            ">

                
                

                {{-- VALUE --}}
                <div style="
                    padding:10px 12px;
                    font-size:13px;
                    color:#000;
                    min-height:20px;
                ">

                    @if(!empty($purpose))
                        {{ $purpose }}
                    @else
                        <span style="color:#000;">
                            —
                        </span>
                    @endif

                </div>


                

            </div>

        </div>


        <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent;">

            <div style="
                display:grid;
                grid-template-columns: 35% 25% 3% 37%;
                width:100%;
            ">

                {{-- COL 1 (LABEL) --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 6px;
                ">
                    Duration of Solicitation: (Inclusive Dates)
                </div>

                {{-- COL 2 (EMPTY VALUE SPACE) --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 6px;
                "></div>

                {{-- COL 3 (GAP / WHITE) --}}
                <div></div>

                {{-- COL 4 (LABEL) --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 6px;
                ">
                    Target amount to be raised:
                </div>

            </div>

        </div>

       
        <div class="row" style="padding:0; min-height:unset;">

            <div style="
                display:grid;
                grid-template-columns: 35% 25% 3% 1fr;
                width:100%;
            ">

                {{-- COL 1 (FROM) --}}
                <div style="padding:4px 12px;">

                    {{-- SUBTEXT --}}
                    <div style="
                        font-size:10px;
                        color:#2f6fb3;
                        margin-bottom:2px;
                    ">
                        From (dd/mm/yyyy)
                    </div>

                    {{-- VALUE --}}
                    <div style="font-size:12px;">
                        {{ $durationFrom ? \Carbon\Carbon::parse($durationFrom)->format('d/m/Y') : '' }}
                    </div>

                </div>

                {{-- COL 2 (TO) --}}
                <div style="padding:4px 12px;">

                    {{-- SUBTEXT --}}
                    <div style="
                        font-size:10px;
                        color:#2f6fb3;
                        margin-bottom:2px;
                    ">
                        To (dd/mm/yyyy)
                    </div>

                    {{-- VALUE --}}
                    <div style="font-size:12px;">
                        {{ $durationTo ? \Carbon\Carbon::parse($durationTo)->format('d/m/Y') : '' }}
                    </div>

                </div>

                {{-- GAP --}}
                <div></div>


                

                {{-- COL 4 (TARGET AMOUNT) --}}
                <div style="padding:6px 12px; font-size:12px;">

                    {{-- SUBTEXT --}}
                    <div style="
                        font-size:10px;
                        color:#2f6fb3;
                        margin-bottom:2px;
                    ">
                     
                    </div>

                    {{-- VALUE --}}
                    <div style="font-size:12px; margin-top:10px; margin-left:10px">
                         ₱ {{ number_format($targetAmount ?? 0, 2) }}
                    </div>


                   
                </div>

            </div>

        </div>

        <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent;">

            <div style="
                display:grid;
                grid-template-columns: 1fr 3% 1fr 3% 1fr;
                width:100%;
            ">

                {{-- COL 1 --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 6px;
                ">
                    Desired number of letters to be distributed:
                </div>

                {{-- GAP --}}
                <div></div>

                {{-- COL 3 --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 6px;
                ">
                    Approved number of letters to be distributed:
                </div>

                {{-- GAP --}}
                <div></div>

                {{-- COL 5 --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 6px;
                ">
                    Series (Control Numbers) Assigned to Released Letters:
                </div>

            </div>

        </div>


        <div class="row" style="padding:0; min-height:unset;">

            <div style="
                display:grid;
                grid-template-columns: 1fr 3% 1fr 3% 1fr;
                width:100%;
                align-items:center;
            ">

                <div style="
                    padding:6px 8px;
                    font-size:12px; text-align:center
                ">
                    {{ $letterCount ?? '' }}
                </div>

                {{-- GAP --}}
                <div></div>

                <div style="
                    padding:6px 8px;
                    font-size:12px;
                    align-items:center;
                    text-align:center
                ">
                    {{ $batch->approved_letter_count ?? '' }}
                </div>

                {{-- GAP --}}
                <div></div>

                {{-- COL 5 (SERIES RANGE) --}}
                <div style="
                    padding:6px 8px;
                    font-size:12px;
                    align-items:center;
                    text-align:center
                ">
                    {{ ($batch->control_series_start ?? '') && ($batch->control_series_end ?? '') 
                        ? $batch->control_series_start.' - '.$batch->control_series_end 
                        : '' }}
                </div>

            </div>

        </div>

        <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent;">

            <div style="
                display:grid;
                grid-template-columns: 40% 1fr;
                width:100%;
                align-items:center;
            ">

            

                {{-- RIGHT (LABEL) --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 8px;
                ">
                    Target Benefactors: <span style="font-weight:400;">(You may check more than one.)</span>
                </div>

                <div></div>

            </div>

        </div>


        <div class="row" style="padding:10px 8px;  min-height:unset;">

            @php
                $studentOrgs = $data->target_student_orgs ?? false;
                $xuOfficers = $data->target_xu_officers ?? false;
                $privateIndividuals = $data->target_private_individuals ?? false;
                $alumni = $data->target_alumni ?? false;
                $companies = $data->target_private_companies ?? false;
                $others = $data->target_others ?? false;
                $othersText = $data->target_others_specify ?? '';
            @endphp

            <div style="
                display:grid;
                grid-template-columns: 1fr 1fr;
                row-gap:6px;
                column-gap:40px;
                font-size:11px;
            ">

                {{-- LEFT COLUMN --}}
                <div>
                    <input type="checkbox" {{ $studentOrgs ? 'checked' : '' }}>
                    Student Organizations within XU
                </div>

                <div>
                    <input type="checkbox" {{ $xuOfficers ? 'checked' : '' }}>
                    Offices inside XU <span style="font-style:italic;">(not encouraged; case to case basis)</span>
                </div>

                <div>
                    <input type="checkbox" {{ $privateIndividuals ? 'checked' : '' }}>
                    Private Individuals / Relatives
                </div>

                <div>
                    <input type="checkbox" {{ $alumni ? 'checked' : '' }}>
                    Alumni
                </div>

                <div>
                    <input type="checkbox" {{ $companies ? 'checked' : '' }}>
                    Private Companies
                </div>

                {{-- OTHERS --}}
                <div>
                    <input type="checkbox" {{ $others ? 'checked' : '' }}>
                    Others: 
                    <span style="
                        display:inline-block;
                        border-bottom:1px solid #2f6fb3;
                        min-width:180px;
                        padding-left:4px;
                    ">
                        {{ $othersText }}
                    </span>
                </div>

            </div>

        </div>
                
        <div class="row" style="
            padding:6px 8px;
            border-bottom:2px transparent;
            page-break-inside: avoid;
            break-inside: avoid;
            margin-top: 20px;
        ">

            <div style="font-size:11px; line-height:1.4;">

                {{-- TITLE --}}
                <div style="font-weight:700; margin-bottom:4px;">
                    AGREEMENT:
                </div>

                {{-- CONTENT --}}
                <div style="
                    text-align:justify;
                    text-indent:20px;
                ">
                    We understand that there are rules and regulations which govern solicitation activities using the name of the University. 
                    Failure to abide by them and the approved terms and conditions of this form entails sanctions for the organization and 
                    disciplinary measures for the students involved.
                </div>

            </div>

        </div>

        

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
                        ✔ Approved In System · '.$s->signed_at?->format('M d, Y h:i A').'
                    </div>
                ';
            }
        @endphp

        <div class="row" style="
            padding:14px 8px;
            border-bottom: 3px solid #000;
            page-break-inside: avoid;
            break-inside: avoid;
        ">

            <div style="
                display:grid;
                grid-template-columns:1fr 1fr 1fr;
                gap:30px;
                text-align:center;
            ">

                {{-- PROJECT HEAD --}}
                <div>

                    {!! approvalLine('project_head', $sigs) !!}

                    <div style="
                        margin-top:18px;
                        border-bottom:1px solid #000;
                        padding-bottom:2px;
                        font-weight:100;
                    ">
                        {{ sig('project_head', $sigs)?->user?->name ?? '—' }}
                    </div>

                    <div style="font-size:11px; line-height:1.2;">
                        Signature Over Printed Name<br>
                        of Project Head
                    </div>

                    {{-- CONTACT --}}
                    <div style="font-size:11px; margin-top:2px;">
                        {{ sig('project_head', $sigs)?->user?->contact_number ?? '' }}
                    </div>

                </div>


                {{-- PRESIDENT --}}
                <div>

                    {!! approvalLine('president', $sigs) !!}

                    <div style="
                        margin-top:18px;
                        border-bottom:1px solid #000;
                        padding-bottom:2px;
                        font-weight:100;
                    ">
                        {{ sig('president', $sigs)?->user?->name ?? '—' }}
                    </div>

                    <div style="font-size:11px; line-height:1.2;">
                        Signature Over Printed Name<br>
                        of President
                    </div>

                </div>


                {{-- MODERATOR --}}
                <div>

                    {!! approvalLine('moderator', $sigs) !!}

                    <div style="
                        margin-top:18px;
                        border-bottom:1px solid #000;
                        padding-bottom:2px;
                        font-size:12px;
                    ">
                       <strong>{{ sig('moderator', $sigs)?->user?->name ?? '—' }}</strong>
                    </div>

                    <div style="font-size:11px; line-height:1.2;">
                        Signature Over Printed Name<br>
                        of Moderator
                    </div>

                </div>

            </div>

        </div>


        <div class="row" style="
            padding:12px 8px;
            border-bottom: 3px solid #000;
            page-break-inside: avoid;
            break-inside: avoid;
        ">

            <div style="
                display:grid;
                grid-template-columns:1fr 1fr;
                width:100%;
                text-align:center;
            ">

                {{-- SACDEV --}}
                <div>

                    {{-- LABEL --}}
                    <div style="font-size:11px; margin-bottom:6px; text-align:left;">
                        Endorsed by:
                    </div>

                    {{-- APPROVAL STATUS --}}
                    {!! approvalLine('sacdev_admin', $sigs) !!}

                    {{-- NAME --}}
                    <div style="
                        margin-top:10px;
                        font-weight:600;
                        text-transform:uppercase;
                    ">
                        {{ sig('sacdev_admin', $sigs)?->user?->name ?? '—' }}
                    </div>

                    {{-- TITLE --}}
                    <div style="font-size:11px; line-height:1.2;">
                        Student Activities and Leadership Development Head
                    </div>

                </div>


                {{-- OSA ADMIN --}}
                <div>

                    {{-- LABEL --}}
                    <div style="font-size:11px; margin-bottom:6px; text-align:left; margin-left:20px;">
                        Approved by:
                    </div>

                    {{-- APPROVAL STATUS --}}
                    {!! approvalLine('osa_admin', $sigs) !!}

                    {{-- NAME --}}
                    <div style="
                        margin-top:10px;
                        font-weight:600;
                        text-transform:uppercase;
                    ">
                        {{ sig('osa_admin', $sigs)?->user?->name ?? 'Mr Ivanell R Subarabas' }}
                    </div>

                    {{-- TITLE --}}
                    <div style="font-size:11px; line-height:1.2;">
                        Director of Student Affairs
                    </div>

                </div>

            </div>

        </div>

        <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent;">

            <div style="
                background:#2f6fb3;
                color:#fff;
                font-weight:700;
                font-size:11px;
                padding:3px 8px;
                width:100%;
            ">
                IMPORTANT:
            </div>

        </div>

        <div class="row" style="
            padding:6px 8px;
            border-bottom:none;
            page-break-inside: avoid;
            break-inside: avoid;
        ">

            <div style="
                font-size:11px;
                line-height:1.4;
                text-align:justify;
            ">

                Please print and submit this application form with an attached draft of the solicitation letter. 
                Please refer to the student organization manual for detailed instructions on how to prepare the letter. 
                Solicitation letters should have the clause 
                <span style="font-style:italic;">
                    “This letter is considered invalid unless audited by SACDEV-OSA, Xavier University-Ateneo de Cagayan”
                </span> 
                at the bottom. Mass production of letters may be done after approval of this application. 
                Submit mass produced letters to SACDEV-OSA for assigning of control numbers. 
                A solicitation report should be submitted to SACDEV-OSA (attached to the liquidation report of the activity where solicitation is meant for).

            </div>

        </div>

    </div>

</div>

</body>
</html>