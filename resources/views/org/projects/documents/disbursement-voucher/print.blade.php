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


{{-- APPROVALS --}}
@include('org.projects.documents.disbursement-voucher.print_partials._approvals')


</div>

</body>
</html>