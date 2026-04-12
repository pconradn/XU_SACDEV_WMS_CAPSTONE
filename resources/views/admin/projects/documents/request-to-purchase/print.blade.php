<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Request to Purchase</title>

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
            border-bottom: 1px transparent #999;
            padding: 10px 10px 14px 10px;
            margin-bottom: 10px;
        }

        .header-left {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .logo-box {
            width: 40px;
            height: 40px;
            border: 1px solid #000;
        }

        .header-text {
            font-size: 10.5px;
            line-height: 1.3;
        }

        .header-text strong {
            font-size: 11px;
            display: block;
            margin-bottom: 1px;
        }

        .form-code {
            background: #5b8fd9;
            color: #fff;
            font-size: 11px;
            padding: 6px 14px;
            font-weight: 600;
        }

        /* TITLE */
        .title {
            text-align: center;
            margin-top: 12px;
            margin-bottom: 20px;
        }

        .title h2 {
            font-size: 15px;
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .title span {
            display: block;
            font-size: 11px;
            margin-top: 2px;
            font-style: italic;
        }

        /* ROW SYSTEM (for next sections) */
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

<body >

    <div class="page" >

<div class="header" style="display:flex; justify-content:space-between; align-items:flex-start;">

    {{-- LEFT --}}
    <div class="header-left" style="display:flex; align-items:center; gap:10px;">

        {{-- LOGO BOX --}}
        <div class="logo-box"></div>

        {{-- TEXT --}}
        <div class="header-text">
            <strong>
                STUDENT ACTIVITIES AND LEADERSHIP DEVELOPMENT
            </strong>
            Office of Student Affairs, Xavier University - Ateneo de Cagayan<br>
            Rm 204, 2/F Magis Student Complex (Tel) 858-3116 local 9245
        </div>

    </div>

    {{-- RIGHT (STACKED) --}}
    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">

        {{-- FORM CODE --}}
        <div class="form-code">
            Form A10 (2023)
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
        <div class="title" style="margin-top:-50px">
            <h2>REQUEST TO PURCHASE FORM</h2>
            <span>(Accomplish 3 copies)</span>
        </div>

        @php
            $activityName = old('activity_name', $document->activity_name ?? $project->title);
            $purpose = old('purpose', $document->solicitationData->purpose ?? '');

        @endphp


        <div class="row" style="border-bottom: 1px transparent">
                       
            <div class="row" style="padding:0; min-height:unset; border-bottom: 2px transparent; border-top:1px solid #5b8fd9">

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
                        Date of Application:
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

                        {{ $document->submitted_at ? \Carbon\Carbon::parse($document->submitted_at)->format('F d, Y') : '—' }}
                    </div>


                </div>

            </div>

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
                        Name of Organization
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
                            {{ $project->organization->name ?? '—' }}
                        </span>
                    @endif

                </div>

            </div>

            <div class="row" style="padding:0; min-height:unset; border-bottom: 2px transparent; border-top:1px transparent #5b8fd9">

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
                        Source(s) of Funds:
                    </div>



                </div>

            </div>
            @php

            $data = $purchaseSourceOfFunds
            @endphp


            {{-- SOURCE OF FUNDS ROW --}}
            <div class="row" style="padding:0; border-bottom:1px solid #5b8fd9; ">

                <div style="
                    display:grid;
                    grid-template-columns:1fr 1fr 1fr;
                    width:100%;
                    padding:6px 10px;
                    font-size:11px;
                ">

                    {{-- LEFT COLUMN --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">

                        <div>
                            [ {{ ($data->xu_finance_amount ?? 0) > 0 ? '✓' : ' ' }} ]
                            XU Finance
                            <span style="margin-left:6px;">
                                {{ ($data->xu_finance_amount ?? 0) > 0 ? '₱ '.number_format($data->xu_finance_amount,2) : '______________' }}
                            </span>
                        </div>

                        <div>
                            [ {{ ($data->pta_amount ?? 0) > 0 ? '✓' : ' ' }} ]
                            PTA
                            <span style="margin-left:6px;">
                                {{ ($data->pta_amount ?? 0) > 0 ? '₱ '.number_format($data->pta_amount,2) : '______________' }}
                            </span>
                        </div>

                    </div>


                    {{-- MIDDLE COLUMN --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">

                        <div>
                            [ {{ ($data->membership_fee_amount ?? 0) > 0 ? '✓' : ' ' }} ]
                            Membership Fee
                            <span style="margin-left:6px;">
                                {{ ($data->membership_fee_amount ?? 0) > 0 ? '₱ '.number_format($data->membership_fee_amount,2) : '______________' }}
                            </span>
                        </div>

                        <div>
                            [ {{ ($data->solicitations_amount ?? 0) > 0 ? '✓' : ' ' }} ]
                            Solicitations
                            <span style="margin-left:6px;">
                                {{ ($data->solicitations_amount ?? 0) > 0 ? '₱ '.number_format($data->solicitations_amount,2) : '______________' }}
                            </span>
                        </div>

                    </div>


                    {{-- RIGHT COLUMN --}}
                    <div style="display:flex; flex-direction:column; gap:6px;">

                        <div>
                            [ {{ ($data->others_amount ?? 0) > 0 ? '✓' : ' ' }} ]
                            Others:
                            <span style="margin-left:4px;">
                                {{ $data->others_label ?? '______________' }}
                            </span>
                        </div>

                        <div>
                            Amount:
                            <span style="margin-left:6px;">
                                {{ ($data->others_amount ?? 0) > 0 ? '₱ '.number_format($data->others_amount,2) : '______________' }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>




            {{-- ITEMS TABLE --}}
            <div class="row" style="padding:0; border-bottom:1px solid #5b8fd9; margin-top: 30px">

                <div style="width:100%; border-top:1px solid #5b8fd9;">

                    {{-- HEADER --}}
                    <div style="
                        display:grid;
                        grid-template-columns:10% 10% 32% 11% 11% 26%;
                        background:#5b8fd9;
                        color:#fff;
                        font-size:11px;
                        font-weight:600;
                        text-align:center;
                    ">

                        <div style="padding:6px; border-right:1px solid #fff;">Quantity</div>
                        <div style="padding:6px; border-right:1px solid #fff;">Unit</div>
                        <div style="padding:6px; border-right:1px solid #fff;">Particulars</div>
                        <div style="padding:6px; border-right:1px solid #fff;">Unit Price</div>
                        <div style="padding:6px; border-right:1px solid #fff;">Amount</div>
                        <div style="padding:6px;">Vendor / Supplier</div>

                    </div>


                    {{-- BODY --}}
                    @php
                        $items = $data->items ?? [];
                    @endphp

                    @forelse($items as $item)
                    <div style="
                        display:grid;
                        grid-template-columns:10% 10% 32% 11% 11% 26%;
                        font-size:12px;
                        min-height:45px;
                        border-top:1px transparent #5b8fd9;
                    ">

                        <div style="padding:6px; border-right:1px solid #5b8fd9; text-align:center;">
                            {{ $item->quantity ?? '' }}
                        </div>

                        <div style="padding:6px; border-right:1px solid #5b8fd9; text-align:center;">
                            {{ $item->unit ?? '' }}
                        </div>

                        <div style="padding:6px; border-right:1px solid #5b8fd9;">
                            {{ $item->particulars ?? '' }}
                        </div>

                        <div style="padding:6px; border-right:1px solid #5b8fd9; text-align:right;">
                            {{ isset($item->unit_price) ? '₱ '.number_format($item->unit_price,2) : '' }}
                        </div>

                        <div style="padding:6px; border-right:1px solid #5b8fd9; text-align:right;">
                            {{ isset($item->amount) ? '₱ '.number_format($item->amount,2) : '' }}
                        </div>

                        <div style="padding:6px;">
                            {{ $item->vendor ?? '' }}
                        </div>

                    </div>
                    @empty

                    {{-- EMPTY GRID (LIKE YOUR IMAGE HEIGHT) --}}
                    @for($i = 0; $i < 6; $i++)
                    <div style="
                        display:grid;
                        grid-template-columns:10% 10% 32% 11% 11% 26%;
                        font-size:12px;
                        min-height:45px;
                        border-top:1px solid #5b8fd9;
                    ">

                        <div style="border-right:1px solid #5b8fd9;"></div>
                        <div style="border-right:1px solid #5b8fd9;"></div>
                        <div style="border-right:1px solid #5b8fd9;"></div>
                        <div style="border-right:1px solid #5b8fd9;"></div>
                        <div style="border-right:1px solid #5b8fd9;"></div>
                        <div></div>

                    </div>
                    @endfor

                    @endforelse

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

                </div>

            </div>
            {{--hui rows here--}}




        </div>


    </div>

</body>
</html>