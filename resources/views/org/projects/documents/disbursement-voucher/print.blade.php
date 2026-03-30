<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<title>Disbursement Voucher</title>

<script>
function printDV(){
    window.print();
}
</script>

<style>

body{
    font-family: Arial, sans-serif;
    font-size:12px;
}

.container{
    width:900px;
    margin:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

td,th{
    border:1px solid #000;
    padding:5px;
}

.no-border{
    border:none;
}

.center{
    text-align:center;
}

.right{
    text-align:right;
}

.print-btn{
    margin:20px 0;
}

.checkbox{
    width:14px;
    height:14px;
    border:1px solid black;
    display:inline-block;
    text-align:center;
    line-height:12px;
    font-size:11px;
}

@media print{

.print-btn{
display:none;
}

body{
margin:0;
}

}

</style>

</head>


<body>

<div class="container">

<div class="print-btn">
<button onclick="printDV()">Print Disbursement Voucher</button>
</div>


{{-- HEADER --}}
@include('org.projects.documents.disbursement-voucher.print_partials._header')


{{-- PAYMENT SECTION --}}
@include('org.projects.documents.disbursement-voucher.print_partials._payment')


{{-- DETAILS --}}
@include('org.projects.documents.disbursement-voucher.print_partials._details')


{{-- SIGNATURES --}}
@include('org.projects.documents.disbursement-voucher.print_partials._signatures')

{{-- NEW ROW --}}
<div style="display:flex; width:100%;">

    {{-- LEFT (60%) --}}
    <div style="
        width:60%;
        padding:8px;
        display:grid;
        grid-template-columns: 1.2fr 1fr 2fr;
        row-gap:6px;
        font-size:12px;
    ">

        {{-- HEADER --}}
        <div style="border-bottom:1px solid #000;">
            <strong>Organization:</strong>
        </div>

        <div style="border-bottom:1px solid #000; border-right:1px solid #000;"></div>

        <div style="border-bottom:1px solid #000; text-align:left;">
            <strong>Contact #</strong>
        </div>


        {{-- DASH LINE --}}
        <div style="grid-column: span 3; border-bottom:1px dashed #000; margin-top:4px;"></div>


        {{-- TITLE --}}
        <div style="grid-column: span 3; margin-top:6px;">
            <strong>Endorsed/Approved by:</strong>
        </div>


        {{-- LEVEL 1 --}}
        <div style="text-align: center">1st level</div>
        <div>up to P10,000</div>
        <div style="
            display:grid;
            grid-template-columns:1fr 1fr;
            column-gap:10px;
        ">
            <div style="text-align:center;">
                <div style="border-bottom:1px solid ; font-size:8px;">
                    ENGR BILLY JHONES ADAYA
                </div>
                <div style="font-size:11px;">SACDEV Head</div>
            </div>

            <div style="text-align:center;  font-size:8px;">
                <div style="border-bottom:1px solid;" >
                    MR IVANELL R SUBRABAS
                </div>
                <div style="font-size:11px;">Director of Student Affairs</div>
            </div>
        </div>


        {{-- LEVEL 2 --}}
        <div style="margin-top:8px; text-align: center;">2nd level</div>
        <div style="margin-top:8px;">up to P50,000</div>
        <div style="margin-top:8px; text-align:center;">
            <div style="border-bottom:1px solid;">MR IVANELL R SUBRABAS</div>
            <div style="font-size:11px;">Director of Student Affairs</div>
        </div>


        {{-- LEVEL 3 --}}
        <div style="margin-top:8px; text-align: center;" >3rd level</div>
        <div style="margin-top:8px;">up to P100,000</div>
        <div style="margin-top:8px; text-align:center;">
            <div style="border-bottom:1px solid;">FR FRANK DENNIS B SAVADERA, SJ</div>
            <div style="font-size:11px;">VP for Mission and Ministry</div>
        </div>


        {{-- LEVEL 4 --}}
        <div style="margin-top:8px; text-align: center">4th level</div>
        <div style="margin-top:8px;">above P100,000</div>
        <div style="margin-top:8px; text-align:center;">
            <div style="border-bottom:1px solid;">FR MARS P TAN, SJ</div>
            <div style="font-size:11px;">University President</div>
        </div>

    </div>

    {{-- RIGHT (40%) --}}
 <div style="
    width:40%;
    padding:8px;
    display:grid;
    grid-template-columns: 1fr 1fr;
    row-gap:6px;
    font-size:12px;
    border-left:1px solid #000;
">

    {{-- ================= TOP GRID (FIXED) ================= --}}
    <div style="
        grid-column: span 2;
        display:grid;
        grid-template-columns: 1fr 1fr;
        gap:0;
    ">

        {{-- HEADER --}}
        <div style="grid-column: span 2; border:1px solid #000; padding:4px;">
            BUDGET ALLOCATION
        </div>

        <div style="border:1px solid #000; padding:4px;">
            EXPENSES to date
        </div>

        <div style="border:1px solid #000; padding:4px; text-align:right;">
            ₱
        </div>

        <div style="border:1px solid #000; padding:4px;">
            BUDGET AVAILABLE
        </div>

        <div style="border:1px solid #000; padding:4px; text-align:right;">
            ₱
        </div>

        <div style="grid-column: span 2; border:1px solid #000; padding:4px;">
            Source of funds:
        </div>

        <div style="border:1px solid #000; padding:4px;">
            Budget item
        </div>

        <div style="border:1px solid #000; padding:4px;">
            Dept/Unit
        </div>

    </div>


    {{-- ================= SIGNATURES ================= --}}
    <div style="margin-top:20px; text-align:center;">
        <div style="border-bottom:1px solid #000; border-right:1px solid #000; height:20px;"></div>
        <div style="margin-top:4px;">Budget Controller</div>
    </div>

    <div style="margin-top:20px; text-align:center;">
        <div style="border-bottom:1px solid #000; height:20px;"></div>
        <div style="margin-top:4px;">Special Funds Incharge</div>
    </div>


    {{-- ================= APPROVED ================= --}}
    <div style="grid-column: span 2; margin-top:15px;">
        <strong>Approved for Processing:</strong>
    </div>

    <div style="
        grid-column: span 2;
        display:grid;
        grid-template-columns: 3fr 1fr;
        column-gap:10px;
        align-items:end;
    ">

        <div style="text-align:center;">
            <div style="border-bottom:1px solid #000; height:20px;"></div>
            <div style="margin-top:4px;">University Treasurer</div>
        </div>

        <div style="text-align:center;">
            <div style="border-bottom:1px solid #000; height:20px;"></div>
            <div style="margin-top:4px;">Date</div>
        </div>

    </div>


    {{-- ================= POSTED ================= --}}
    <div style="grid-column: span 2; margin-top:15px;">
        <strong>Posted to SAP by:</strong>
    </div>

    <div style="grid-column: span 2; text-align:center;">
        <div style="border-bottom:1px solid #000; height:20px;"></div>
        <div style="margin-top:4px;">Accounting Staff</div>
    </div>

</div>

</div>

</body>
</html>