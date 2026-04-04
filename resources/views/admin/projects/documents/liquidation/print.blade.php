<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liquidation Report</title>

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

        .logo-box {
            width: 50px;
            height: 50px;
            border: 1px solid #000;
        }

        .row {
            border-bottom: 1px solid #5b8fd9;
            min-height: 35px;
            padding: 6px 8px;
        }

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

<body>

@php
$report = $document->liquidationData

@endphp

{{-- PRINT BUTTON --}}
<div style="display:flex; justify-content:flex-end; max-width:800px; margin:10px auto;">
    <button onclick="window.print()" class="print-btn"
        style="padding:8px 16px; background:#2f6fb3; color:white; font-size:12px; border:none; border-radius:6px;">
        Print Document
    </button>
</div>

<div class="page">

    {{-- HEADER --}}
    <div style="
        display:grid;
        grid-template-columns:1fr 260px;
        gap:10px;
        align-items:start;
        margin-bottom:10px;
    ">

        {{-- LEFT --}}
        <div style="display:flex; gap:10px;">

            <div class="logo-box"></div>

            <div style="font-size:9px; line-height:1.3;">
                <strong style="font-size:10px;">
                    STUDENT ACTIVITIES AND LEADERSHIP DEVELOPMENT
                </strong><br>
                Office of Student Affairs, Xavier University - Ateneo de Cagayan<br>
                Rm 204, 2/F Magis Student Complex (Tel) 858-3116 local 3330
            </div>

        </div>


        {{-- RIGHT --}}
        <div style="display:flex; flex-direction:column; gap:6px;">

            {{-- FORM CODE --}}
            <div style="
                background:#5b8fd9;
                color:#fff;
                font-weight:600;
                font-size:11px;
                padding:4px 10px;
                text-align:right;
            ">
                Form A3 (2016 Edition)
            </div>

            {{-- QR + CHECKBOX ROW --}}
            <div style="display:flex; gap:6px; align-items:flex-start;">

                {{-- QR --}}
                @if($document->verification_url)
                    <div style="text-align:center;">
                        {!! QrCode::size(65)->generate($document->verification_url) !!}

                        <div style="font-size:9px; margin-top:2px; color:#555;">
                            Scan
                        </div>
                    </div>
                @endif

                {{-- CHECKBOX BOX --}}
                <div style="
                    border:1px solid #5b8fd9;
                    padding:0px 5px;
                    font-size:8px;
                    flex:1;
                ">

                    <div style="margin-bottom:4px;">
                        ☐ Accomplish 3 copies of this form
                    </div>

                    <div style="margin-bottom:4px;">
                        ☐ Attach Cash Advance Form / Inflow Reports
                    </div>

                    <div style="margin-bottom:8px;">
                        ☐ Attach original official receipts
                    </div>

                    <div style="border-top:1px solid #5b8fd9; padding-top:6px;">
                        ☐ Advanced (Disbursed before implementation)
                    </div>

                    <div style="margin-bottom:4px; margin-top:4px;">
                        ☐ For Reimbursement
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- TITLE --}}
    <div style="
        font-weight:700;
        font-size:16px;
        margin-top:10px;
        margin-bottom:10px;
    ">
        LIQUIDATION REPORT
    </div>

    <div style="
        width:100%;
        border:1px solid #000;
        margin-top:6px;
        font-size:10px;
    ">

        <div style="display:grid; grid-template-columns:18% 82%; border-bottom:1px solid #000;">
            <div style="padding:3px 6px; border-right:1px solid #000; font-weight:600;">
                Name of Project:
            </div>
            <div style="padding:3px 6px;">
                {{ $project->title }}
            </div>
        </div>

        <div style="display:grid; grid-template-columns:18% 82%; border-bottom:1px solid #000;">
            <div style="padding:3px 6px; border-right:1px solid #000; font-weight:600;">
                Name of Organization:
            </div>
            <div style="padding:3px 6px;">
                {{ $project->organization->name ?? '—' }}
            </div>
        </div>

        <div style="display:grid; grid-template-columns:18% 82%; border-bottom:1px solid #000;">
            <div style="padding:3px 6px; border-right:1px solid #000; font-weight:600;">
                Implementation Date:
            </div>
            <div style="padding:3px 6px;">
                {{ optional($project->proposalDocument?->proposalData?->start_date)
                    ? \Carbon\Carbon::parse($project->proposalDocument->proposalData->start_date)->format('F d, Y')
                    : '—' }}
            </div>
        </div>

        <div style="display:grid; grid-template-columns:18% 82%; border-bottom:1px solid #000;">
            <div style="padding:3px 6px; border-right:1px solid #000; font-weight:600;">
                Project Head:
            </div>
            <div style="padding:3px 6px;">
                {{ $project->projectHead?->user?->name ?? '—' }}
            </div>
        </div>

        <div style="display:grid; grid-template-columns:18% 82%;">
            <div style="padding:3px 6px; border-right:1px solid #000; font-weight:600;">
                Position:
            </div>
            <div style="padding:3px 6px;">
                {{ $project->projectHead?->user?->officerEntries()->first()->position ?? '—' }}
            </div>
        </div>

    </div>

    <div style="
        width:100%;
        border-left:1px transparent #000;
        border-right:1px transparent #000;
        border-bottom:1px solid #000;
        height:22px;
    ">
    </div>

    <div style="
        width:100%;
        border:1px solid #000;
        border-top:none;
        font-size:9px;
        box-sizing:border-box;
    ">

        <div style="
            display:grid;
            grid-template-columns:10% 20% 20% 20% 15% 15%;
            min-height:60px;
            text-align: center;
        ">

            {{-- COL 1 --}}
            <div style="
                background:#5b8fd9;
                border-right:1px solid #000;
                display:flex;
                align-items:center;
                justify-content:center;
                font-weight:600;
                color: #ffffff;
            ">
                Cash Received From:
            </div>

            {{-- COL 2 --}}
            <div style="
                display:grid;
                grid-template-rows:1fr 1fr 1fr; font-weight:600;
            ">
                <div style=" background:#5b8fd9; padding:2px 4px; border-bottom:1px solid #000;"></div>
                <div style="border-right:1px solid #000; 
                background:#5b8fd9; padding:2px 4px; border-bottom:1px solid #000;
                color: #ffffff;">XU Finance</div>
                <div style="border-right:1px solid #000; padding:2px 4px;">{{ $report->finance_amount ? '₱ ' . number_format($report->finance_amount, 2) : '' }}</div>
            </div>

            {{-- COL 3 --}}
            <div style="
                display:grid; font-weight:600;
                grid-template-rows:1fr 1fr 1fr;
            ">
                <div style="background:#5b8fd9; padding:2px 4px; border-bottom:1px solid #000; text-align:center; 
                color: #ffffff;">Cluster A </div>
                <div style="border-right:1px solid #000; background:#5b8fd9; padding:2px 4px; 
                    border-bottom:1px solid #000; text-align: center; 
                color: #ffffff;">Fund Raising</div>
                <div style="border-right:1px solid #000; padding:2px 4px;">{{ $report->fund_raising_amount ? '₱ ' . number_format($report->fund_raising_amount, 2) : '' }}</div>
            </div>

            {{-- COL 4 --}}
            <div style="
                border-right:2px solid #000;
                display:grid;
                grid-template-rows:1fr 1fr 1fr; font-weight:600;
            ">
                <div style=" 
                background:#5b8fd9;padding:2px 4px; border-bottom:1px solid #000;"></div>
                <div style="
                background:#5b8fd9; padding:2px 4px; border-bottom:1px solid #000; 
                color: #ffffff;">SACDEV</div>
                <div style="padding:2px 4px;">{{ $report->sacdev_amount ? '₱ ' . number_format($report->sacdev_amount, 2) : '' }}</div>
            </div>

            {{-- COL 5 --}}
            <div style="
                border-right:2px solid #000;
                display:grid;
                grid-template-rows:1fr 1fr 1fr; font-weight:600;
            ">
                <div style=" 
                background:#5b8fd9;padding:2px 4px; border-bottom:1px solid #000; 
                color: #ffffff;">Cluster B</div>
                <div style="
                background:#5b8fd9; padding:2px 4px; border-bottom:1px solid #000; 
                color: #ffffff;">PTA/College/Dept</div>
                <div style="padding:2px 4px;">{{ $report->pta_amount ? '₱ ' . number_format($report->pta_amount, 2) : '' }}</div>
            </div>

            @php
                $totalFunds =
                    ($report->finance_amount ?? 0) +
                    ($report->fund_raising_amount ?? 0) +
                    ($report->sacdev_amount ?? 0) +
                    ($report->pta_amount ?? 0);
            @endphp

            {{-- COL 6 --}}
            <div style="
                display:grid;
                grid-template-rows:1fr 1fr 1fr; font-weight:600;
            ">
                <div style=" 
                background:#5b8fd9;padding:2px 4px; border-bottom:1px transparent #000; 
                color: #ffffff;"></div>
                <div style="
                background:#5b8fd9; padding:2px 4px; border-bottom:1px solid #000; 
                color: #ffffff;">Total Funds</div>
                <div style="padding:2px 4px;">{{ $totalFunds ? '₱ ' . number_format($totalFunds, 2) : '' }}</div>
            </div>

        </div>

    </div>

    @php
        $fundraisingTypes = $document->liquidationData->fundraising_types ?? [];
        //dd($fundraisingTypes)
    @endphp



    <div style="
        width:100%;
        border:1px solid #000;
        border-top:none;
        background:#e5e5e5;
        font-size:9px;
        padding:4px 8px;
        box-sizing:border-box;
        text-align: center;
    ">

        If Fund Raising, check among the options:

        {{-- SOLICITATION --}}
        <span style="margin-left:10px;">
            <span style="
                display:inline-block;
                min-width:22px;
                border-bottom:1px solid #000;
                text-align:center;
            ">
                {{ in_array('solicitation', $fundraisingTypes) ? '✔' : '' }}
            </span>
            Solicitation
        </span>

        {{-- COUNTERPART --}}
        <span style="margin-left:12px;">
            <span style="
                display:inline-block;
                min-width:22px;
                border-bottom:1px solid #000;
                text-align:center;
            ">
                {{ in_array('counterpart', $fundraisingTypes) ? '✔' : '' }}
            </span>
            Counterpart
        </span>

        {{-- TICKET SELLING --}}
        <span style="margin-left:12px;">
            <span style="
                display:inline-block;
                min-width:22px;
                border-bottom:1px solid #000;
                text-align:center;
            ">
                {{ in_array('ticket_selling', $fundraisingTypes) ? '✔' : '' }}
            </span>
            Ticket Selling
        </span>

        {{-- SELLING --}}
        <span style="margin-left:12px;">
            <span style="
                display:inline-block;
                min-width:22px;
                border-bottom:1px solid #000;
                text-align:center;
            ">
                {{ in_array('selling', $fundraisingTypes) ? '✔' : '' }}
            </span>
            Selling
        </span>

    </div>

    <div style="
        width:100%;
        border-left:1px transparent #000;
        border-right:1px transparent #000;
        border-bottom:1px transparent #000;
        height:22px;
    ">
    </div>
    <div style="
        width:100%;
        border-left:1px transparent #000;
        border-right:1px transparent #000;
        border-bottom:1px solid #000;
        height:22px;
    ">
    </div>

    <div style="
        width:100%;
        border:1px solid #000;
        border-top:none;
        display:grid;
        grid-template-columns:14% 86%;
        font-size:8px;
        box-sizing:border-box;
    ">

        {{-- LEFT --}}
        <div style="
            background:#5b8fd9;
            color:#fff;
            font-weight:600;
            padding:4px 6px;
            border-right:1px solid #000;
            display:flex;
            align-items:center;
        ">
            Cash Spent for:
        </div>

        {{-- RIGHT --}}
        <div style="
            background:#fff200;
            padding:4px 6px;
            line-height:1.3;
        ">
            <span style="font-weight:600;">Required Attachments:</span>
            <span style="color:#d60000;">
                Cash Advance Form
            </span>
            <span style="color:#000;">
                (If source of fund is Finance),
            </span>
            <span style="color:#d60000;">
                Collection/Selling/Solicitation Reports 
            </span>
            <span>
                (If Fund Raising),
                Official Receipts of expenses,
                and Photocopy of Certificates
            </span>
            <span style="color:#000;">
                (for attendance in Conventions/Seminars)
            </span>
        </div>

    </div>

    @php
        $items = $document->liquidationData->items ?? collect();
        $grouped = $document->liquidationData->items->groupBy('section_label');
    @endphp

    <div style="
        width:100%;
        border:1px solid #000;
        border-top:none;
        font-size:9px;
        box-sizing:border-box;
    ">

        {{-- HEADER --}}
        <div style="
            display:grid;
            grid-template-columns:12% 28% 15% 30% 15%;
            background:#5b8fd9;
            color:#fff;
            font-weight:600;
        ">
            <div style="padding:4px 6px; border-right:1px solid #000;">DATE</div>
            <div style="padding:4px 6px; border-right:1px solid #000; text-align:center;">PARTICULARS</div>
            <div style="padding:4px 6px; border-right:1px solid #000; text-align:right;">AMOUNT</div>
            <div style="padding:4px 6px; border-right:1px solid #000;">SOURCE DOCUMENT</div>
            <div style="padding:4px 6px; text-align:center;">OR NUMBER</div>
        </div>

        {{-- BODY --}}
        @foreach($grouped as $section => $rows)

            {{-- SECTION HEADER ROW --}}
            <div style="
                display:grid;
                grid-template-columns:12% 28% 15% 30% 15%;
                border-top:1px transparent #000;
            ">

                <div style="border-right:1px solid #000;"></div>

                <div style="
                    border-right:1px solid #000;
                    text-align:center;
                    font-weight:600;
                    padding:4px 6px;
                ">
                    {{ $section }}
                </div>

                <div style="border-right:1px solid #000;"></div>
                <div style="border-right:1px solid #000;"></div>
                <div></div>

            </div>


            {{-- ITEM ROWS --}}
            @foreach($rows as $row)

            <div style="
                display:grid;
                grid-template-columns:12% 28% 15% 30% 15%;
                border-top:1px transparent #000;
            ">

                {{-- DATE --}}
                <div style="padding:4px 6px; border-right:1px solid #000;">
                    {{ $row->date ?? '' }}
                </div>

                {{-- PARTICULARS --}}
                <div style="padding:4px 15px; border-right:1px solid #000; word-wrap: break-word;
                    overflow-wrap: break-word;
                    word-break: break-word; ">
                    {{ $row->particulars ?? '' }}
                </div>

                {{-- AMOUNT --}}
                <div style="padding:4px 6px; border-right:1px solid #000; text-align:right;">
                    {{ isset($row->amount) ? number_format($row->amount, 2) : '' }}
                </div>

                {{-- SOURCE DOCUMENT --}}
                <div style="padding:4px 6px; border-right:1px solid #000;">
                    {{ ($row->source_document_type ?? '') . ' - ' . ($row->source_document_description ?? '') }}
                </div>

                {{-- OR NUMBER --}}
                <div style="padding:4px 6px; text-align:center; word-wrap: break-word;
                    overflow-wrap: break-word;
                    word-break: break-word;">
                    {{ $row->or_number ?? '' }}
                </div>

            </div>

            @endforeach

        @endforeach

    </div>


<div style="position:relative;">


    <div style="
        width:100%;
        
        border-top:none;
        display:grid;
        grid-template-columns:40% 15% 45%;
        font-size:11px;
        box-sizing:border-box;
        border-right:1px transparent #000;
        border-bottom:1px transparent #000;
    ">

        @php
            $items = $report->items ?? collect();

            $totalExpenses = $items->sum(function ($item) {
                return $item->amount ?? 0;
            });

            $remainingAmount = (float) ($totalFunds ?? 0) - (float) ($totalExpenses ?? 0);
            
        @endphp

        {{-- LEFT --}}
        <div style="
            border-right:1px solid #000;
            border-left:1px solid #000;
            border-bottom:1px solid #000;
            min-height:unset;
            display:grid;
            grid-template-rows:auto auto auto;
            font-size:9px;
        ">

            <div style="
                border-bottom:1px solid #000;
                display:flex;
                align-items:center;
                padding:3px 6px;
            ">
                Total Expenses:
            </div>

            <div style="
                border-bottom:1px solid #000;
                display:flex;
                align-items:center;
                padding:3px 6px;
            ">
                Total Amount Advanced:
            </div>

            <div style="
                align-items:center;
                padding:3px 6px;
            ">
                 <span style="font-weight: 600">Balance</span>
                <span style="font-size: 6px">
                (Negative balance should be returned to the organization; 
                if balance is positive, only up to 10% of the over-all budget is reimbursable): 
                </span>
            </div>

        </div>


        <div style="
            border-right:1px solid #000;   
            border-bottom:1px solid #000;
            min-height:unset;
            display:grid;
            grid-template-rows:29% 28% 44%;
            font-size:9px;
        ">

            <div style="
                border-bottom:1px solid #000;
                display:flex;
                align-items:center;
                justify-content:flex-end;
                padding:3px 6px;
                text-align:right;
            ">
                {{ isset($totalExpenses) ? '₱ ' . number_format($totalExpenses, 2) : '' }}
            </div>

            <div style="
                border-bottom:1px solid #000;
                display:flex;
                align-items:center;
                justify-content:flex-end;
                padding:3px 6px;
                text-align:right;
            ">
                {{ isset($totalFunds) ? '₱ ' . number_format($totalFunds, 2) : '' }}
            </div>

            <div style="
                display:flex;
                align-items:center;
                justify-content:flex-end;
                padding:3px 6px;
                text-align:right;
            ">
                {{ isset($remainingAmount) ? '₱ ' . number_format($remainingAmount, 2) : '' }}
            </div>

        </div>

        {{-- RIGHT --}}
        <div style="
            padding:4px 6px;
            min-height:unset;
            display:flex;
            align-items:center;
            justify-content:flex-end;
            text-align:right;
            border-right:1px transparent #000;
            border-bottom:1px transparent #000;
        ">
           
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
                return '<div style="font-size:8px; color:#9ca3af;">Pending Approval</div>';
            }

            return '
                <div style="font-size:8px; color:#2f6fb3; font-weight:600;">
                    ✔ Approved In System <br> 
                    '.$s->signed_at?->format('M d, Y h:i A').'
                </div>
            ';
        }
    @endphp



    {{-- ROW 1 --}}
    <div style="
        width:100%;
        display:grid;
        grid-template-columns:50% 50%;
        margin-top:20px;
        font-size:10px;
    ">

        {{-- LEFT --}}
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px;">

            {{-- PREPARED --}}
            <div>
                <div style="margin-bottom:2px; font-size:9px;">Prepared by:</div>

                {!! approvalLine('project_head', $sigs) !!}

                <div style="margin-top:10px; border-bottom:1px solid #000;">
                    {{ sig('project_head', $sigs)?->user?->name ?? '—' }}
                </div>

                <div style="font-size:9px;">
                    Project Head
                </div>

                <div style="font-size:9px;">
                    {{ sig('project_head', $sigs)?->user?->contact_number ?? '' }}
                </div>
            </div>


            {{-- NOTED --}}
            <div>
                <div style="margin-bottom:2px; font-size:9px;">Noted by:</div>

                {!! approvalLine('treasurer', $sigs) !!}

                <div style="margin-top:10px; border-bottom:1px solid #000;">
                    {{ sig('treasurer', $sigs)?->user?->name ?? '—' }}
                </div>

                <div style="font-size:9px;">
                    Treasurer
                </div>
            </div>


            {{-- AUDITED --}}
            <div>
                <div style="margin-bottom:2px; font-size:9px;">Audited by:</div>

                {!! approvalLine('finance_officer', $sigs) !!}

                <div style="margin-top:10px; border-bottom:1px solid #000;">
                    {{ sig('finance_officer', $sigs)?->user?->name ?? '—' }}
                </div>

                <div style="font-size:9px;">
                    Budget and Finance Officer
                </div>
            </div>

        </div>

        {{-- RIGHT (EMPTY) --}}
        <div></div>

    </div>


    {{-- ROW 2 --}}
    <div style="
        width:100%;
        display:grid;
        grid-template-columns:50% 50%;
        margin-top:8px;
        font-size:10px;
    ">

        {{-- LEFT --}}
        <div>

            <div style="margin-bottom:4px; font-size:9px;">
                Approved by:
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                {{-- PRESIDENT --}}
                <div>
                    {!! approvalLine('president', $sigs) !!}

                    <div style="margin-top:10px; border-bottom:1px solid #000;">
                        {{ sig('president', $sigs)?->user?->name ?? '—' }}
                    </div>

                    <div style="font-size:9px;">
                        President
                    </div>
                </div>


                {{-- MODERATOR --}}
                <div>
                    {!! approvalLine('moderator', $sigs) !!}

                    <div style="margin-top:10px; border-bottom:1px solid #000;">
                        {{ sig('moderator', $sigs)?->user?->name ?? '—' }}
                    </div>

                    <div style="font-size:9px;">
                        Moderator
                    </div>
                </div>

            </div>

        </div>

        {{-- RIGHT (EMPTY) --}}
        <div></div>

    </div>


    <div style="
        position:absolute;
        right:0;
        top:10px;
        width:230px;
        border:1px solid #000;
        padding:8px;
        font-size:9px;
        background:#fff;
        box-sizing:border-box;
    ">

        <div style="font-weight:600; margin-bottom:8px;">
            Amount to be Returned to the Org:
        </div>

        <div style="
            display:flex;
            justify-content:space-between;
            margin-bottom:4px;
        ">
            <span>Cluster A (XU Finance)</span>
            <span>{{ isset($report->cluster_a_return) ? '₱ ' . number_format($report->cluster_a_return, 2) : '' }}</span>
        </div>

        <div style="
            display:flex;
            justify-content:space-between;
            margin-bottom:6px;
        ">
            <span>Cluster B (PTA)</span>
            <span>{{ isset($report->cluster_b_return) ? '₱ ' . number_format($report->cluster_b_return, 2) : '' }}</span>
        </div>

        <div style="font-style:italic; margin-bottom:4px;">
            Formula:
        </div>

        <div style="font-size:8px; line-height:1.3; margin-bottom:6px;">
            (Source of Funds: Cluster A = Total Funds) x Balance<br>
            (Source of Funds: Cluster B = Total Funds) x Balance
        </div>

        <div style="
            display:flex;
            justify-content:space-between;
            border-top:1px solid #000;
            padding-top:4px;
            font-weight:600;
        ">
            <span>Total</span>
            <span>{{ isset($remainingAmount) ? '₱ ' . number_format($remainingAmount, 2) : '' }}</span>
        </div>

    </div>

</div>


    <div style="
        width:100%;
        font-size:9px;
        margin-top:37px;
        line-height:1.4;
    ">

        <div style="margin-bottom:4px;">
            <em>
                If there is any amount to be returned to the organization, please attach XU Finance Receipt/
                Deposit Slip in the liquidation report as proof of deposit.
            </em>
        </div>

        <div>
            <em>
                Legend (Source Documents): OR - Official Receipt, SR - Subsidiary Receipt,
                CI - Cash Invoice, SI - Sales Invoice, AR - Acknowledgment Receipt,
                PV - Payment Voucher
            </em>
        </div>

    </div>



    {{-- NEXT CONTENT GOES HERE --}}
    <div class="form-container" style="margin-top:10px;">
        {{-- we build next --}}
    </div>

</div>

</body>
</html>