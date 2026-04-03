<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fees Collection Report</title>

    <style>
        body {
            font-family: Arial, sans-serif;
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
            padding-bottom: 26px;
        }

        .header-left {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .header-logo img {
            width: 28px;
            height: auto;
            display: block;
        }
        .logo-box {
            width: 50px;
            height: 50px;
            border: 1px solid #000;
        }

        .header-text {
            font-size: 10.5px;
            line-height: 1.25;
        }

        .header-text strong {
            font-size: 11px;
            display: block;
            margin-bottom: 1px;
        }

        .form-code {
            background: #5b8fd9;
            color: #fff;
            padding: 4px 14px;
            font-size: 11px;
            min-width: 145px;
            text-align: left;
            line-height: 1.1;
        }

        /* TITLE */
        .title {
            text-align: center;
            margin-top: 18px;
            margin-bottom: 20px;
        }

        .title h2 {
            font-size: 15px;
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .title span {
            display: block;
            font-size: 11px;
            margin-top: 2px;
        }

        /* ROW SYSTEM */
        .row {
            border-bottom: 1px solid #5b8fd9;
            min-height: 35px;
            padding: 6px 8px;
        }

        /* PRINT */
        @media print {
            body {
                margin: 0;
            }

            .print-btn {
                display: none;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

@php
    $data = $document->sellingApplication;
    $activityName = old('activity_name', $data->activity_name ?? $project->title);
    $projectedSales = old('projected_sales', $data->projected_sales ?? '');
    $purpose = old('purpose', $data->purpose ?? '');
    $durationFrom = old('duration_from', $data->duration_from ?? '');
    $durationTo = old('duration_to', $data->duration_to ?? '');
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
        <div class="header">

            <div class="header-left">
                <div class="logo-box"></div>

                <div class="header-text">
                    <strong>STUDENT ACTIVITIES AND LEADERSHIP DEVELOPMENT</strong>
                    Office of Student Affairs, Xavier University - Ateneo de Cagayan<br>
                    Rm 204, 2/F Magis Student Complex (Tel) 858-3116 local 9245
                </div>
            </div>

            <div class="form-code">
                Form A8 (2023)
            </div>

        </div>

        {{-- TITLE --}}
        <div class="title">
            <h2>APPLICATION FOR SELLING</h2>
            <span>(Please accomplish 2 copies.)</span>
        </div>

        {{-- FORM START --}}
        <div class="form-container" style="margin-top:15px;">

            {{-- NAME OF ORGANIZATION --}}
            <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent; border-top:1px solid #5b8fd9;">
                <div style="display:grid; grid-template-columns:27% 73%; width:100%;">
                    <div style="
                        background:#5b8fd9;
                        color:#fff;
                        font-weight:600;
                        font-size:11px;
                        padding:1px 6px;
                    ">
                        Name of Organization:
                    </div>
                    <div></div>
                </div>
            </div>

            <div class="row" style="padding:0; min-height:unset;">
                <div style="padding:8px 10px; font-size:12px; color:#000;">
                    {{ $project->organization->name ?? '—' }}
                </div>
            </div>

            {{-- NAME OF SELLING ACTIVITY --}}
            <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent;">
                <div style="display:grid; grid-template-columns:22% 78%; width:100%;">
                    <div style="
                        background:#5b8fd9;
                        color:#fff;
                        font-weight:600;
                        font-size:11px;
                        padding:1px 6px;
                    ">
                        Name of Selling Activity:
                    </div>
                    <div></div>
                </div>
            </div>

            <div class="row" style="padding:0; min-height:unset;">
                <div style="padding:8px 10px; font-size:12px; color:#000;">
                    {{ $activityName ?: '—' }}
                </div>
            </div>

            {{-- PURPOSE --}}
            <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent;">
                <div style="display:grid; grid-template-columns:16% 84%; width:100%;">
                    <div style="
                        background:#5b8fd9;
                        color:#fff;
                        font-weight:600;
                        font-size:11px;
                        padding:1px 6px;
                    ">
                        Purpose:
                    </div>

                    <div style="
                        font-size:11px;
                        color:#2f6fb3;
                        padding:1px 6px;
                        font-style:italic;
                    ">
                        (Please state the reason / justification why there is a need for selling.)
                    </div>
                </div>
            </div>

            <div class="row" style="padding:0; min-height:unset;">
                <div style="padding:8px 10px; font-size:12px; color:#000;">
                    {{ $purpose ?: '—' }}
                </div>
            </div>

            {{-- DURATION + PROJECTED SALES --}}
            <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent;">
                <div style="
                    display:grid;
                    grid-template-columns:32% 22% 3% 43%;
                    width:100%;
                ">
                    <div style="
                        background:#5b8fd9;
                        color:#fff;
                        font-weight:600;
                        font-size:11px;
                        padding:1px 6px;
                    ">
                        Duration of Selling: <span style="font-style:italic; font-weight:400;">(Inclusive Dates)</span>
                    </div>

                    <div style="
                        background:#5b8fd9;
                        padding:1px 6px;
                    "></div>

                    <div></div>

                    <div style="
                        background:#5b8fd9;
                        color:#fff;
                        font-weight:600;
                        font-size:11px;
                        padding:1px 6px;
                    ">
                        Projected Sales:
                    </div>
                </div>
            </div>

            <div class="row" style="padding:0; min-height:unset;">
                <div style="
                    display:grid;
                    grid-template-columns:32% 22% 3% 43%;
                    width:100%;
                ">
                    <div style="padding:3px 10px 8px 10px;">
                        <div style="font-size:10px; color:#000; line-height:1.1;">
                            From (dd/mm/yy)
                        </div>
                        <div style="font-size:12px; margin-top:2px;">
                            {{ $durationFrom ? \Carbon\Carbon::parse($durationFrom)->format('d/m/y') : '' }}
                        </div>
                    </div>

                    <div style="padding:3px 10px 8px 10px;">
                        <div style="font-size:10px; color:#000; line-height:1.1;">
                            To (dd/mm/yy)
                        </div>
                        <div style="font-size:12px; margin-top:2px;">
                            {{ $durationTo ? \Carbon\Carbon::parse($durationTo)->format('d/m/y') : '' }}
                        </div>
                    </div>

                    <div></div>

                    <div style="padding:3px 10px 8px 10px;">
                        <div style="font-size:12px; margin-top:14px; margin-left:10px">
                             ₱ {{ number_format($projectedSales ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent;">
                <div style="
                    display:grid;
                    grid-template-columns:40% 60%;
                    width:100%;
                ">
                    <div style="
                        background:#5b8fd9;
                        color:#fff;
                        font-weight:600;
                        font-size:11px;
                        padding:2px 6px;
                    ">
                        Desired Number of Goods to be Sold:
                    </div>
                    <div></div>
                </div>
            </div>

            <div class="row" style="padding:0; min-height:unset;">

                <div style="
                    width:100%;
                    border-top:0px solid #5b8fd9;
                    border-bottom:1px solid #5b8fd9;
                ">

                    {{-- HEADER --}}
                    <div style="
                        display:grid;
                        grid-template-columns:10% 35% 25% 30%;
                        font-size:11px;
                        font-weight:600;
                    ">

                        <div style="padding:6px; border-right:1px solid #5b8fd9; text-align:center;">
                            Quantity
                        </div>

                        <div style="padding:6px; border-right:1px solid #5b8fd9;">
                            Particulars
                        </div>

                        <div style="padding:6px; border-right:1px solid #5b8fd9; text-align:center;">
                            Selling Price
                        </div>

                        <div style="padding:6px;">
                            Remarks (To be filled by SACDEV)
                        </div>

                    </div>



                    {{-- ROW --}}
                    @php
                        $items = $data->items ?? [];

                       //dd($items)
                    @endphp

                    @forelse($items as $item)
                    <div style="
                        display:grid;
                        grid-template-columns:10% 35% 25% 30%;
                        font-size:12px;
                        min-height:40px;
                        border-top:0px solid #5b8fd9;
                    ">

                        <div style="padding:6px; border-right:1px solid #5b8fd9; text-align:center;">
                            {{ $item['quantity'] ?? '' }}
                        </div>

                        <div style="padding:6px; border-right:1px solid #5b8fd9;">
                            {{ $item['particulars'] ?? '' }}
                        </div>

                        <div style="padding:6px; border-right:1px solid #5b8fd9; text-align:center;">
                            {{ isset($item['selling_price']) ? '₱ '.number_format($item['selling_price'],2) : '' }}
                        </div>

                        <div style="padding:6px;">
                            {{ $item['remarks'] ?? '' }}
                        </div>

                    </div>
                    @empty

                    {{-- EMPTY ROW --}}
                    <div style="
                        display:grid;
                        grid-template-columns:20% 25% 25% 30%;
                        font-size:11px;
                        min-height:50px;
                        border-top:1px solid #5b8fd9;
                    ">

                        <div style="padding:6px; border-right:1px solid #5b8fd9;">
                            (Please type here.)
                        </div>

                        <div style="padding:6px; border-right:1px solid #5b8fd9;">
                            (Please type here.)
                        </div>

                        <div style="padding:6px; border-right:1px solid #5b8fd9; text-align:center;">
                        </div>

                        <div style="padding:6px;">
                        </div>

                    </div>

                    @endforelse

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
                    We understand that there are rules and regulations which govern selling 
                    activities in the University. Failure to abide by them and the approved 
                    terms and conditions in this form entails sanctions for the organization 
                    and disciplinary measures for the students involved. 
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
                        ✔ Approved In System <br> 
                        '.$s->signed_at?->format('M d, Y h:i A').'
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
                        Project Head
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
                        President
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
                        Moderator
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

        <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent; margin-top: 20px;">

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

                Please print and submit this application to SACDEV-OSA. A selling 
                activity report should be submitted to SACDEV-OSA (attached to the 
                liquidation report of the activity where the selling was meant for).  

            </div>

        </div>






        </div>
    </div>

</body>
</html>