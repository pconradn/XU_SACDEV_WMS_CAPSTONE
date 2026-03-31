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

            {{-- CHECKBOX BOX --}}
            <div style="
                border:1px solid #5b8fd9;
                padding:0px 5px;
                font-size:8px;
                margin-left: 50px
            ">

                <div style="margin-bottom:4px; ">
                    ☐ Accomplish 3 copies of this form
                </div>

                <div style="margin-bottom:4px; ">
                    ☐ Attach Cash Advance Form / Inflow Reports
                </div>

                <div style="margin-bottom:8px; ">
                    ☐ Attach original official receipts
                </div>

                <div style="border-top:1px solid #5b8fd9; padding-top:6px; ">
                    ☐ Advanced (Disbursed before implementation)
                </div>

                <div style="margin-bottom:4px; margin-top:4px ">
                    ☐ For Reimbursement
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
                ____________
            </div>
        </div>

        <div style="display:grid; grid-template-columns:18% 82%; border-bottom:1px solid #000;">
            <div style="padding:3px 6px; border-right:1px solid #000; font-weight:600;">
                Name of Organization:
            </div>
            <div style="padding:3px 6px;">
                ____________
            </div>
        </div>

        <div style="display:grid; grid-template-columns:18% 82%; border-bottom:1px solid #000;">
            <div style="padding:3px 6px; border-right:1px solid #000; font-weight:600;">
                Implementation Date:
            </div>
            <div style="padding:3px 6px;">
                ____________
            </div>
        </div>

        <div style="display:grid; grid-template-columns:18% 82%; border-bottom:1px solid #000;">
            <div style="padding:3px 6px; border-right:1px solid #000; font-weight:600;">
                Project Head:
            </div>
            <div style="padding:3px 6px;">
                ____________
            </div>
        </div>

        <div style="display:grid; grid-template-columns:18% 82%;">
            <div style="padding:3px 6px; border-right:1px solid #000; font-weight:600;">
                Position:
            </div>
            <div style="padding:3px 6px;">
                ____________
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
        font-size:11px;
    ">

        <div style="
            display:grid;
            grid-template-columns:10% 20% 20% 20% 15% 15%;
            min-height:60px;
        ">

            {{-- COL 1 --}}
            <div style="
                background:#5b8fd9;
                border-right:1px solid #000;
                display:flex;
                align-items:center;
                justify-content:center;
                font-weight:600;
            ">
                Col 1
            </div>

            {{-- COL 2 --}}
            <div style="
                display:grid;
                grid-template-rows:1fr 1fr 1fr;
            ">
                <div style=" background:#5b8fd9; padding:2px 4px; border-bottom:1px solid #000;"></div>
                <div style="border-right:1px solid #000; background:#5b8fd9; padding:2px 4px; border-bottom:1px solid #000;">Col 2 - 2</div>
                <div style="border-right:1px solid #000; padding:2px 4px;">Col 2 - 3</div>
            </div>

            {{-- COL 3 --}}
            <div style="
                display:grid;
                grid-template-rows:1fr 1fr 1fr;
            ">
                <div style="background:#5b8fd9; padding:2px 4px; border-bottom:1px solid #000;">Cluster A </div>
                <div style="border-right:1px solid #000; background:#5b8fd9; padding:2px 4px; border-bottom:1px solid #000;">Col 3 - 2</div>
                <div style="border-right:1px solid #000; padding:2px 4px;">Col 3 - 3</div>
            </div>

            {{-- COL 4 --}}
            <div style="
                border-right:1px solid #000;
                display:grid;
                grid-template-rows:1fr 1fr 1fr;
            ">
                <div style=" 
                background:#5b8fd9;padding:2px 4px; border-bottom:1px solid #000;"></div>
                <div style="
                background:#5b8fd9; padding:2px 4px; border-bottom:1px solid #000;">Col 4 - 2</div>
                <div style="padding:2px 4px;">Col 4 - 3</div>
            </div>

            {{-- COL 5 --}}
            <div style="
                border-right:1px solid #000;
                display:grid;
                grid-template-rows:1fr 1fr 1fr;
            ">
                <div style=" 
                background:#5b8fd9;padding:2px 4px; border-bottom:1px solid #000;">Col 4 - 1</div>
                <div style="
                background:#5b8fd9; padding:2px 4px; border-bottom:1px solid #000;">Col 4 - 2</div>
                <div style="padding:2px 4px;">Col 4 - 3</div>
            </div>

            {{-- COL 6 --}}
            <div style="
                display:flex;
                align-items:center;
                justify-content:center;
                font-weight:600;
            ">
                Col 6
            </div>

        </div>

    </div>











    {{-- NEXT CONTENT GOES HERE --}}
    <div class="form-container" style="margin-top:10px;">
        {{-- we build next --}}
    </div>

</div>

</body>
</html>