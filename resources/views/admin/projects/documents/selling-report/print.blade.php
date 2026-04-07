<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Application for Selling</title>

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
    $data = $document->sellingActivityReport;
    $activityName = old('activity_name', $data->activity_name ?? $project->title);
    $projectedSales = old('projected_sales', $data->projected_sales ?? '');
    $purpose = old('purpose', $data->purpose ?? '');
    $durationFrom = old('duration_from', $data->selling_from ?? '');
    $durationTo = old('duration_to', $data->selling_to ?? '');
@endphp

@php
    $sellingItems = $data->items ?? [];
@endphp

@php
    $totalAmount = collect($sellingItems)->sum(
        fn($i) => ($i->quantity ?? 0) * ($i->price ?? 0)
    );
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
            <strong>STUDENT ACTIVITIES AND LEADERSHIP DEVELOPMENT</strong>
            Office of Student Affairs, Xavier University - Ateneo de Cagayan<br>
            Rm 204, 2/F Magis Student Complex (Tel) 858-3116 local 9245
        </div>

    </div>

    {{-- RIGHT (STACKED) --}}
    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">

        {{-- FORM CODE --}}
        <div class="form-code">
            Form A8-1 (2023)
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
        <div class="title" style="margin-top: -40px">
            <h2>SELLING ACTIVITY REPORT</h2>
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
                        Total Sales:
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

                  
                    <div style="font-size:12px; margin-top:14px; margin-left:10px">
                            ₱ {{ number_format($totalAmount, 2) }}
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
                        Actual Number of Items Sold:
                    </div>
                    <div></div>
                </div>
            </div>

        <div class="row" style="padding:0; border-bottom:2px solid #5b8fd9; margin-top:10px;">

            <div style="width:100%;">

                {{-- HEADER --}}
                <div style="
                    display:grid;
                    grid-template-columns:14% 2% 28% 2% 18% 2% 18% 2% 16%;
                    text-align:center;
                    font-size:10.5px;
                    font-weight:600;
                ">

                    <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                        Quantity
                    </div>

                    <div></div>

                    <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                        Particulars
                    </div>

                    <div></div>

                    <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                        Price
                    </div>

                    <div></div>

                    <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                        Amount
                    </div>

                    <div></div>

                    <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                        Acknowledgement Receipt Number
                    </div>

                </div>


                {{-- BODY --}}


                @forelse($sellingItems as $item)
                <div style="
                    display:grid;
                    grid-template-columns:14% 2% 28% 2% 18% 2% 18% 2% 16%;
                    font-size:12px;
                ">

                    <div style="padding:3px 6px; border-top:1px solid #5b8fd9; text-align:center;">
                        {{ $item->quantity ?? '' }}
                    </div>

                    <div></div>

                    <div style="padding:3px 6px; border-top:1px solid #5b8fd9;">
                        {{ $item->particulars ?? '' }}
                    </div>

                    <div></div>

                    <div style="padding:3px 6px; border-top:1px solid #5b8fd9; text-align:right;">
                        {{ isset($item->price) ? '₱ '.number_format($item->price,2) : '' }}
                    </div>

                    <div></div>

                    <div style="padding:3px 6px; border-top:1px solid #5b8fd9; text-align:right;">
                        {{ isset($item->quantity, $item->price) 
                            ? '₱ '.number_format($item->quantity * $item->price,2) 
                            : '' }}
                    </div>

                    <div></div>

                    <div style="padding:3px 6px; border-top:1px solid #5b8fd9; text-align:center;">
                        {{ $item->acknowledgement_receipt_number ?? '' }}
                    </div>

                </div>
                @empty

                {{-- EMPTY LINES --}}
                @for($i = 0; $i < 10; $i++)
                <div style="
                    display:grid;
                    grid-template-columns:14% 2% 28% 2% 18% 2% 18% 2% 16%;
                    border-bottom:1px solid #5b8fd9;
                ">
                    <div style="height:16px;"></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                @endfor

                @endforelse


                {{-- TOTAL --}}
                <div style="
                    display:grid;
                    grid-template-columns:58% 1fr;
                    border-top:2px solid #5b8fd9;border-left:2px solid #5b8fd9;border-right:2px solid #5b8fd9;
                    margin-top:4px;
                ">

                    <div style="padding:4px 6px; font-weight:600; font-size:11px;">
                        Total Amount
                    </div>

                    <div style="padding:4px 6px; text-align:center; font-weight:600;">
                        ₱ {{
                            number_format(
                                collect($sellingItems)->sum(fn($i) => ($i->quantity ?? 0) * ($i->price ?? 0)),
                                2
                            )
                        }}
                    </div>

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
            border-bottom: 1px solid #000;
            page-break-inside: avoid;
            break-inside: avoid;
            margin-top: 30px;
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
                        Signature Over Printed Name<br>
                        of President
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
                        Signature Over Printed Name<br>
                        of Moderator
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
                        Student Activities and Leadership Development Head
                    </div>

                </div>

            </div>

        </div>




        </div>
    </div>

</body>
</html>