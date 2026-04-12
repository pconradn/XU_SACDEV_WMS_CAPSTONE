<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Solicitation Report</title>

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
            background: #5b8fd9;
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
            border-bottom: 1px solid #5b8fd9;
            min-height: 35px;
            padding: 6px 8px;
        }

        .row:first-child {
            border-top: 1px solid #5b8fd9;
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
    $purpose = old('purpose', $document->solicitationSponsorshipReport->purpose ?? '');
    $durationFrom = old('duration_from', $document->solicitationSponsorshipReport->solicitation_from ?? '');
    $durationTo = old('duration_to', $document->solicitationSponsorshipReport->solicitation_to ?? '');
    $targetAmount = old('target_amount', $document->solicitationSponsorshipReport->target_amount ?? '');
    $letterCount = old('desired_letter_count', $document->solicitationSponsorshipReport->desired_letter_count ?? '');
    $link = old('letter_draft_link', $document->solicitationSponsorshipReport->letter_draft_link ?? '');
    $data = $document->solicitationSponsorshipReport
@endphp
@php
    $batch = $document->solicitationBatches?->first();
@endphp


<body>

{{-- PRINT BUTTON --}}
<div style="display:flex; justify-content:flex-end; max-width:800px; margin:10px auto;">
    <button onclick="window.print()" class="print-btn"
        style="padding:8px 16px; background:#5b8fd9; color:white; font-size:12px; border:none; border-radius:6px;">
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
            Form A6-1
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
    <div class="title" style="margin-top: -20px">
        <h2>SOLICITATION/ SPONSORSHIP REPORT</h2>
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
                    background:#5b8fd9;
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
                    background:#5b8fd9;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:1px 6px;
                ">
                    Name of activity where solicitation was conducted:
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
                    background:#5b8fd9;
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
                    color:#5b8fd9;
                    padding:1px 6px;
                    font-style:italic;
                ">
                    
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
                grid-template-columns: 35% 2% 31% 2% 30%;
                width:100%;
            ">

                {{-- COL 1 --}}
                <div style="
                    background:#5b8fd9;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 6px;
                ">
                    Duration of Solicitation:
                </div>

                {{-- COL 2 (gap/value) --}}
                <div></div>

                {{-- COL 3 --}}
                <div style="
                    background:#5b8fd9;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 6px;
                ">
                    Approved number of letters distributed:
                </div>

                {{-- COL 4 (gap/value) --}}
                <div></div>

                {{-- COL 5 --}}
                <div style="
                    background:#5b8fd9;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 6px;
                ">
                    Total Amount Raised:
                </div>

            </div>

        </div>

       
        <div class="row" style="padding:0; min-height:unset; border-bottom: transparent">

            <div style="
                display:grid;
                grid-template-columns: 35% 2% 31% 2% 30%;
                width:100%;
            ">

                {{-- COL 1 (FROM) --}}
                <div style="padding:4px 12px;">

                    <div style="font-size:10px; color:#5b8fd9; margin-bottom:2px;"></div>

                    <div style="font-size:12px;">
                        From {{ $durationFrom ? \Carbon\Carbon::parse($durationFrom)->format('d/m/Y') : '' }} 
                        to {{ $durationTo ? \Carbon\Carbon::parse($durationTo)->format('d/m/Y') : '' }}
                    </div>

                </div>

                {{-- COL 2 (gap) --}}
                <div></div>

                {{-- COL 3 --}}
                <div style="padding:4px 12px;">

                    <div style="font-size:10px; color:#5b8fd9; margin-bottom:2px;"></div>

                    <div style="font-size:12px; text-align:center">
                        {{$document->solicitationSponsorshipReport->approved_letters_distributed}}
                    </div>

                </div>

                {{-- COL 4 (gap) --}}
                <div></div>

                {{-- COL 5 --}}
                <div style="padding:6px 12px; font-size:12px;">

                    <div style="font-size:10px; color:#5b8fd9; margin-bottom:2px;"></div>

                    <div style="font-size:12px; text-align:center">
                        {{$document->solicitationSponsorshipReport->approved_letters_distributed}}
                    </div>


                </div>

            </div>

        </div>

        <div class="row" style="padding-bottom: 20px ; min-height:unset; margin-bottom: 30px; border-bottom: 1px solid #5b8fd9"></div>


        <div class="row" style="padding:0; border-bottom:2px solid #5b8fd9; margin-top:10px;">

            <div style="width:100%; border-top:px solid #5b8fd9;">

                {{-- HEADER --}}
                <div style="
                    display:grid;
                    grid-template-columns:20% 2% 26% 2% 26% 2% 22%;
                    text-align:center;
                    font-size:10.5px;
                    font-weight:600;
                ">

                    <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                        Control Number<br>
                        <span style="font-size:9px; font-weight:400;">
                            (assigned for individual letters)
                        </span>
                    </div>

                    <div></div>

                    <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                        Person In Charge<br>
                        <span style="font-size:9px; font-weight:400;">
                            (name of sender)
                        </span>
                    </div>

                    <div></div>

                    <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                        Recipient of Letter<br>
                        <span style="font-size:9px; font-weight:400;">
                            (company / individual)
                        </span>
                    </div>

                    <div></div>

                    <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                        Amount Given / Remarks
                    </div>

                </div>


                {{-- BODY --}}
                @php
                    $solicitationItems = $data->items ?? [];
                @endphp

                @forelse($solicitationItems as $item)
                <div style="
                    display:grid;
                    grid-template-columns:20% 2% 26% 2% 26% 2% 22%;
                    font-size:12px;
               
                ">

                    <div style="padding:3px 6px; border-top: 1px solid #5b8fd9 ; ">
                        {{ $item->control_number ?? '' }}
                    </div>

                    <div  ></div>

                     <div style="padding:3px 6px; border-top: 1px solid #5b8fd9 ; ">
                        {{ $item->person_in_charge ?? '' }}
                    </div>

                    <div style="border-bottom: transparent"></div>

                     <div style="padding:3px 6px; border-top: 1px solid #5b8fd9 ; ">
                        {{ $item->recipient ?? '' }}
                    </div>

                    <div style="border-bottom: transparent"></div>

                     <div style="padding:3px 6px; border-top: 1px solid #5b8fd9 ; text-align:center">
                        {{ isset($item->amount_given) ? '₱ '.number_format($item->amount_given,2) : '' }}
                    </div>

                </div>
                @empty

                {{-- EMPTY LINES (FORM STYLE) --}}
                @for($i = 0; $i < 8; $i++)
                <div style="
                    display:grid;
                    grid-template-columns:20% 2% 26% 2% 26% 2% 22%;
                    border-bottom:1px solid #5b8fd9;
                ">

                    <div style="height:16px;"></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>

                </div>
                @endfor

                @endforelse


                {{-- TOTAL ROW --}}
                <div style="
                    display:grid;
                    grid-template-columns:78% 22%;
                    border-top:2px solid #5b8fd9;
                    margin-top:4px;
                ">

                    <div style="
                        padding:4px 6px;
                        font-weight:600;
                        font-size:11px;
                    ">
                        Total Amount
                    </div>

                    <div style="
                        padding:4px 6px;
                        text-align:center;
                        font-size:12px;
                        font-weight:600;
                    ">
                        ₱ {{
                            number_format(
                                collect($solicitationItems)->sum('amount_given'),
                                2
                            )
                        }}
                    </div>

                </div>




                

            </div>


            

        </div>



        {{-- NOTE / INSTRUCTIONS --}}
        <div class="row" style="
            border-bottom:none;
            padding:6px 10px;
            margin-top:8px;
        ">

            <div style="
                font-size:10.5px;
                line-height:1.4;
                text-align:justify;
            ">

                <span style="font-weight:600;">
                    *If the recipient did not provide anything,
                </span>
                the person-in-charge must be able to retrieve the letter from the recipient and return to the organization for submission to SACDEV. In case of lost letters, the person-in-charge must be able to secure a waiver from the recipient stating the fact that there was nothing provided for the particular solicitation letter. This waiver shall then be attached to this solicitation report together with the acknowledgment receipts issued to companies/individuals who gave any amount to the organization.

                <br><br>

                Print and submit this report to SACDEV-OSA upon submission of liquidation report of the activity where solicitation was meant for.

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
            border-bottom: 1px solid #000;
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

                                {{-- LABEL --}}
                    <div style="font-size:11px; margin-bottom:6px; text-align:left; border-bottom: 1px transparent #000;">
                        Prepared by:
                    </div>

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
                       Project Head
                    </div>

                    {{-- CONTACT --}}
                    <div style="font-size:11px; margin-top:2px;">
                        {{ sig('project_head', $sigs)?->user?->contact_number ?? '' }}
                    </div>

                </div>


                {{-- PRESIDENT --}}
                <div>
                    <div style="font-size:11px; margin-bottom:6px; text-align:left; border-bottom: 1px transparent #000;">
                        Approved by:
                    </div>

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
                        President
                    </div>

                </div>


                {{-- MODERATOR --}}
                <div>
                    <div style="font-size:11px; margin-bottom:6px; text-align:left; border-bottom: 1px transparent #000;">
                    .
                    </div>

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
                        Moderator
                    </div>

                </div>

            </div>

        </div>




        <div class="row" style="
            padding:12px 8px;
            border-bottom: 1px solid #000;
            page-break-inside: avoid;
            break-inside: avoid;
        ">

            <div style="
                display:grid;
                grid-template-columns:1fr 1fr; border-bottom: 1px transparent #000;
                width:100%;
                text-align:center;
            ">

                {{-- SACDEV --}}
                <div>

                    {{-- LABEL --}}
                    <div style="font-size:11px; margin-bottom:6px; text-align:left; border-bottom: 1px transparent #000;">
                        Approved by:
                    </div>

                    {{-- APPROVAL STATUS --}}
                    {!! approvalLine('coa_officer', $sigs) !!}

                    {{-- NAME --}}
                    <div style="
                        margin-top:10px;
                        font-weight:600;
                        text-transform:uppercase;
                    ">
                        {{ sig('coa_officer', $sigs)?->user?->name ?? '—' }}
                    </div>

                    {{-- TITLE --}}
                    <div style="font-size:11px; line-height:1.2;">
                        SACDEV Commission on Audit Officer
                    </div>

                </div>

            </div>

        </div>


    </div>

</div>

</body>
</html>