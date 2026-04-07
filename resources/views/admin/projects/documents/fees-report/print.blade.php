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
    $data = $document->feesCollectionReport;
    $activityName = old('activity_name', $data->activity_name ?? $project->title);
    $purpose = old('purpose', $data->purpose ?? '');
    $durationFrom = old('duration_from', $data->collection_from ?? '');
    $durationTo = old('duration_to', $data->collection_to ?? '');
    $targetAmount = old('target_amount', $document->solicitationData->target_amount ?? '');
    $letterCount = old('desired_letter_count', $document->solicitationData->desired_letter_count ?? '');
    $link = old('letter_draft_link', $document->solicitationData->letter_draft_link ?? '');
    
@endphp

   
@php
    $collectionItems = $data->items ?? [];
@endphp

@php
    $totalCollection = collect($collectionItems)->sum(function ($item) {
        return ($item->number_of_payers ?? 0) * ($item->amount_paid ?? 0);
    });
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
                Form A9 (2023)
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
    <div class="title" style="margin-top:-30px">
        <h2>FEES COLLECTION REPORT</h2>
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
                grid-template-columns: 50% 50%;
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
                    Name of Activity where collection of counterpart was done:
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
                    (Please state the reason/ justification why collection was done.)
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
                    min-height:30px;
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
                    background:#5b8fd9;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 6px;
                ">
                    Duration of the Collection/ Date of Collection:
                </div>

                {{-- COL 2 (EMPTY VALUE SPACE) --}}
                <div style="
                    background:#5b8fd9;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 6px;
                "></div>

                {{-- COL 3 (GAP / WHITE) --}}
                <div></div>

                {{-- COL 4 (LABEL) --}}
                <div style="
                    background:#5b8fd9;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:2px 6px;
                ">
                    Total Amount Collected:
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
                        color:#5b8fd9;
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
                        color:#5b8fd9;
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
                        color:#5b8fd9;
                        margin-bottom:2px;
                    ">
                     
                    </div>

                    {{-- VALUE --}}
                    <div style="font-size:12px; margin-top:10px; margin-left:10px">
                         ₱ {{ number_format($totalCollection,2) }} 
                    </div>


                   
                </div>

            </div>

        </div>



        {{-- ROW --}}
        <div class="row" style="padding:0; min-height:unset;  margin-top:20px; border-bottom: 0px solid #5b8fd9; margin-bottom:10px ">

            <div style="
                display:grid;
                grid-template-columns: 30% 70%;
                width:100%;
                
                border-top: 1px solid #5b8fd9;
                
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


                <div ></div>



            </div>




        </div>



<div style="width:100%;">

    {{-- HEADER --}}
    <div style="
        display:grid;
        grid-template-columns:20% 2% 20% 2% 26% 2% 30%;
        text-align:center;
        font-size:10.5px;
        font-weight:600;
    ">

        <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
            Number of Payers
        </div>

        <div></div>

        <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
            Amount Paid
        </div>

        <div></div>

        <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
            Series/ Control Number Assigned to Issued Acknowledgment Receipt
        </div>

        <div></div>

        <div style="background:#5b8fd9; color:#fff; padding:4px 6px;">
            SACDEV Remarks
        </div>

    </div>



    @forelse($collectionItems as $item)
    <div style="
        display:grid;
        grid-template-columns:20% 2% 20% 2% 26% 2% 30%;
        font-size:12px;
        border-bottom: 1px solid #5b8fd9
    ">

        {{-- PAYERS --}}
        <div style="padding:3px 6px; border-top:1px solid #5b8fd9; text-align:center;">
            {{ $item->number_of_payers ?? '' }}
        </div>

        <div></div>

        {{-- AMOUNT --}}
        <div style="padding:3px 6px; border-top:1px solid #5b8fd9; text-align:right; text-align:center;">
            {{ isset($item->amount_paid) ? '₱ '.number_format($item->amount_paid,2) : '' }}
        </div>

        <div></div>

        {{-- RECEIPT --}}
        <div style="padding:3px 6px; border-top:1px solid #5b8fd9; text-align:center;">
            {{ $item->receipt_series ?? '' }}
        </div>

        <div></div>

        {{-- REMARKS --}}
        <div style="padding:3px 6px; border-top:1px solid #5b8fd9; text-align:center;">
            {{ $item->remarks ?? '' }}
        </div>

        </div>
        @empty

        {{-- EMPTY FORM LINES --}}
        @for($i = 0; $i < 12; $i++)
        <div style="
            display:grid;
            grid-template-columns:20% 2% 20% 2% 26% 2% 30%;
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


        {{-- TOTAL --}}

        @php
            $totalCollection = collect($collectionItems)->sum(function ($item) {
                return ($item->number_of_payers ?? 0) * ($item->amount_paid ?? 0);
            });
        @endphp

        <div style="
            display:grid;
            grid-template-columns:21% 21% 1fr 1fr;
        
            margin-top:10px;
        ">

            <div style="padding:4px 6px; font-weight:600; font-size:11px;  border-top:2px solid #5b8fd9;  border-bottom:2px solid #5b8fd9; border-left:2px solid #5b8fd9;">
                Total Collection
            </div>

            <div style="padding:4px 6px; text-align:center; font-weight:600;  border-top:2px solid #5b8fd9;  border-bottom:2px solid #5b8fd9; border-right:2px solid #5b8fd9;">
                ₱ {{ number_format($totalCollection,2) }}
            </div>



            <div></div>
            <div></div>
        </div>


        <div class="row" style="
            padding:6px 8px;
            border-bottom:none;
            page-break-inside: avoid;
            break-inside: avoid;
            margin-top:20px;
            margin-bottom: 20px
        ">

            <div style="
                font-size:11px;
                line-height:1.4;
                text-align:justify;
            ">

                <strong>AGREEMENT: </strong>


            </div>

            <div style="
                font-size:11px;
                line-height:1.4;
                text-align:justify;
            ">

                We understand that there are rules and regulations which govern collection of fees in the University. Failure to abide by them entails sanctions for the organization and disciplinary measures for the students involved. 


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
                        font-weight:100;
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
                    {!! approvalLine('coa_officer', $sigs) !!}

                    {{-- NAME --}}
                    <div style="
                        margin-top:10px;
                        font-weight:100;
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






        <div style="width:100%; margin-top:6px;">

            {{-- HEADER BAR --}}
            <div style="
                background:#5b8fd9;
                color:#fff;
                font-size:11px;
                font-weight:600;
                padding:4px 6px;
            ">
                IMPORTANT:
            </div>

            {{-- CONTENT --}}
            <div style="
                padding:6px 6px;
                font-size:11px;
                line-height:1.4;
            ">
                This form applies to collection of membership fee, registration fees for some activities (e.g., seminar), counterpart from members, service fee, rental fee, or any fee collected by the organization.
            </div>

        </div>


    </div>


        

</div>




























    </div>

</div>

</body>
</html>