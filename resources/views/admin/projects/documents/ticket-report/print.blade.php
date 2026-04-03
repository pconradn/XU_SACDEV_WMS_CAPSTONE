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
    $docoumentData = $document->ticketSellingReport;
    $activityName = old('activity_name', $docoumentData->activity_name ?? $project->title);
    $purpose = old('purpose', $docoumentData->solicitationSponsorshipReport->purpose ?? '');
    $durationFrom = old('duration_from', $docoumentData->selling_from ?? '');
    $durationTo = old('duration_to', $docoumentData->selling_to ?? '');
    $data = $document->ticketSellingReport
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
    <div class="header">

        <div class="header-left">
            <div class="logo-box"></div>

            <div class="header-text">
                <strong>STUDENT ACTIVITIES AND LEADERSHIP DEVELOPMENT</strong><br>
                Office of Student Affairs, Xavier University – Ateneo de Cagayan<br>
                Rm 204, 2F Magis Student Complex (Tel) 853-9800 local 9245
            </div>
        </div>

        <div class="form-code">
            Form A7-1
        </div>

    </div>

    {{-- TITLE --}}
    <div class="title">
        <h2>TICKET-SELLING REPORT</h2>
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
                   Name of activity where ticket-selling was conducted:
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
        <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent;">

            <div style="
                display:grid;
                grid-template-columns: 35% 2% 63%;
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
                    Total Ticket Sales: 
                </div>



            </div>

        </div>

       
        <div class="row" style="padding:0; min-height:unset; border-bottom: 0px solid #5b8fd9">

            <div style="
                display:grid;
                grid-template-columns: 35% 2% 63%;
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

                @php
                    $ticketItems = $data->items ?? [];
                @endphp

                {{-- COL 3 --}}
                <div style="padding:4px 12px;">

                    <div style="font-size:10px; color:#5b8fd9; margin-bottom:2px;"></div>

                    <div style="font-size:12px;">
                        ₱ {{
                            number_format(
                                collect($ticketItems)->sum(fn($i) => ($i->quantity ?? 0) * ($i->price_per_ticket ?? 0)),
                                2
                            )
                        }}
                    </div>

                </div>



            </div>

        </div>

        <div class="row" style="padding:0; min-height:10px; border-bottom: 1px solid #5b8fd9; border-top: 0px transparrent #5b8fd9">

            

        </div>


        <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent; margin-bottom: 20px;">

            <div style="
                display:grid;
                grid-template-columns: 35% 65%;
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
                    Actual Tickets Sold (Summary):
                </div>

                {{-- COL 2 (gap/value) --}}
                <div></div>

                {{-- COL 3 --}}
                <div ></div>
                    
            



            </div>

        </div>




    <div style="width:100%; ">

        {{-- HEADER --}}
        <div style="
            display:grid;
            grid-template-columns:14% 2% 22% 2% 16% 2% 16% 2% 1fr;
            text-align:center;
            font-size:10.5px;
            font-weight:600;
        ">

            <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                Quantity
            </div>

            <div></div>

            <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                Series / Control Numbers
            </div>

            <div></div>

            <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                Price / Ticket
            </div>

            <div></div>

            <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                Amount
            </div>

            <div></div>

            <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
                Remarks
            </div>

        </div>


        {{-- BODY --}}
        @php
            $ticketItems = $data->items ?? [];
        @endphp

        @forelse($ticketItems as $item)
        <div style="
            display:grid;
            grid-template-columns:14% 2% 22% 2% 16% 2% 16% 2% 26%;
            font-size:12px;
            border-bottom: 1px solid #5b8fd9;
        
        ">

            {{-- QUANTITY --}}
            <div style="padding:3px 6px; text-align:center;">
                {{ $item->quantity ?? '' }}
            </div>

            <div></div>

            {{-- SERIES --}}
            <div style="padding:3px 6px;">
                {{ $item->series_control_numbers ?? '' }}
            </div>

            <div style="border-bottom: 1px transparent #5b8fd9;"></div>

            {{-- PRICE --}}
            <div style="padding:3px 6px; text-align:right;">
                {{ isset($item->price_per_ticket) ? '₱ '.number_format($item->price_per_ticket,2) : '' }}
            </div>

            <div></div>

            {{-- AMOUNT --}}
            <div style="padding:3px 6px; text-align:right;">
                {{ isset($item->quantity, $item->price_per_ticket)
                    ? '₱ '.number_format($item->quantity * $item->price_per_ticket,2)
                    : '' }}
            </div>

            <div></div>

            {{-- REMARKS --}}
            <div style="padding:3px 6px; ">
                {{ $item->remarks ?? '' }}
            </div>

        </div>
        @empty

        {{-- EMPTY FORM LINES --}}
        @for($i = 0; $i < 12; $i++)
        <div style="
            display:grid;
            grid-template-columns:14% 2% 22% 2% 16% 2% 16% 2% 26%;
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
            grid-template-columns:74% 26%;
            border-top:2px solid #5b8fd9;
            border-bottom:2px solid #5b8fd9;
            border-left:2px solid #5b8fd9;
            border-right:2px solid #5b8fd9;
            margin-top:20px;
        ">

            <div style="padding:4px 6px; font-weight:600; font-size:11px;">
                Total Amount
            </div>

            <div style="padding:4px 6px; text-align:center; font-weight:600;">
                ₱ {{
                    number_format(
                        collect($ticketItems)->sum(fn($i) => ($i->quantity ?? 0) * ($i->price_per_ticket ?? 0)),
                        2
                    )
                }}
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

                    @php
                        $officer = sig('project_head', $sigs)?->user->officerEntries()->first() ?? null;
                        //dd(sig('project_head', $sigs)?->user->officerEntries()->first()->mobile_number)
                    @endphp

                    <div style="margin-top:10px; font-size:11px; text-align:left">
                        Mobile Number: {{ $officer->mobile_number ?? '—' }}<br>
                        Email Address: {{ $officer->email ?? '—' }}
                    </div>




                </div>


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
                        margin-top:20px;
                        font-weight:600;
                        text-transform:uppercase;
                        border-bottom:1px solid #000;
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


        <div>


        </div>




        <div class="row" style="padding:6px 8px;  border-bottom: 2px transparent; page-break-inside: avoid;
    break-inside: avoid;">

            <div style="
                font-size:11px;
                line-height:1.4;
                text-align:justify;
                text-indent:20px;
            ">

                Unsold tickets must be attached to this report for auditing purpose. Submit this report to SACDEV-OSA upon submission of the liquidation report of the activity where the ticket-selling was meant for.    

            </div>

        </div>





    </div>

</div>

</body>
</html>