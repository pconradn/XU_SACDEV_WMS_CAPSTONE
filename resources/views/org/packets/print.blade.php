<!DOCTYPE html>
<html>
<head>

<title>Submission Packet</title>

<style>

body{
font-family: Arial, Helvetica, sans-serif;
padding:40px;
color:#000;
}

.container{
max-width:800px;
margin:auto;
}

.header{
text-align:center;
margin-bottom:25px;
}

.header h1{
font-size:22px;
margin:0;
}

.header h2{
font-size:15px;
font-weight:normal;
margin-top:4px;
}

.badge{
display:inline-block;
margin-top:8px;
padding:4px 10px;
font-size:11px;
border:1px solid #000;
font-weight:bold;
letter-spacing:1px;
}

.info-table{
width:100%;
border-collapse:collapse;
margin-bottom:20px;
font-size:13px;
}

.info-table td{
padding:5px;
}

.section-title{
font-weight:bold;
margin-top:20px;
margin-bottom:8px;
font-size:13px;
border-bottom:1px solid #000;
padding-bottom:3px;
}

table{
width:100%;
border-collapse:collapse;
font-size:12px;
margin-top:8px;
}

th,td{
border:1px solid #000;
padding:6px;
text-align:left;
}

th{
background:#f5f5f5;
}

.qr-container{
margin-top:30px;
display:flex;
justify-content:space-between;
gap:15px;
}

.qr-box{
flex:1;
text-align:center;
border:1px dashed #000;
padding:10px;
}

.qr-title{
font-size:11px;
font-weight:bold;
margin-bottom:6px;
}

.qr-desc{
font-size:10px;
margin-top:6px;
line-height:1.3;
}

.footer{
margin-top:35px;
border-top:1px solid #000;
padding-top:12px;
font-size:11px;
}

.footer-row{
margin-top:12px;
display:flex;
justify-content:space-between;
gap:10px;
}

.footer-box{
flex:1;
border-bottom:1px solid #000;
height:30px;
}

.footer-label{
font-size:10px;
margin-top:4px;
}

.print-btn{
margin-bottom:15px;
}

@media print{

.print-btn{
display:none;
}

body{
padding:0;
}

}

</style>

</head>

<body>

<div class="container">

<div class="print-btn">
<button onclick="window.print()">Print Packet Cover</button>
</div>


<div class="header">

<h1>SACDEV Physical Submission Packet</h1>
<h2>Xavier University – Ateneo de Cagayan</h2>

<div class="badge">
PACKET CODE: {{ $packet->packet_code }}
</div>

</div>


<table class="info-table">

<tr>
<td><strong>Project:</strong></td>
<td>{{ $project->title }}</td>
</tr>

<tr>
<td><strong>Generated:</strong></td>
<td>{{ \Carbon\Carbon::parse($packet->generated_at)->format('F d, Y') }}</td>
</tr>

<tr>
<td><strong>Total Items:</strong></td>
<td>{{ $packet->items->count() }}</td>
</tr>

</table>


<div class="section-title">
Submitted Items
</div>

<table>

<tr>
<th style="width:20%">Type</th>
<th style="width:20%">Reference</th>
<th style="width:40%">Description</th>
<th style="width:20%">Amount</th>
</tr>

@forelse($packet->items as $item)

@php
$typeLabel = match($item->type) {
    'dv' => 'Disbursement Voucher',
    'receipt' => 'Official Receipt',
    'solicitation_letter' => 'Solicitation Letter',
    default => ucfirst(str_replace('_',' ', $item->type))
};
@endphp

<tr>
<td>{{ $typeLabel }}</td>
<td>{{ $item->reference_number }}</td>
<td>{{ $item->label }}</td>
<td>
@if($item->amount)
{{ number_format($item->amount,2) }}
@endif
</td>
</tr>

@empty

<tr>
<td colspan="4" style="text-align:center;">No items listed</td>
</tr>

@endforelse

</table>


<div class="qr-container">

<div class="qr-box">

<div class="qr-title">
SCAN FOR SACDEV PROCESSING
</div>

{!! QrCode::size(110)->generate(route('admin.packets.receive').'?packet_code='.$packet->packet_code) !!}

<div class="qr-desc">
Scan to open this packet in the SACDEV system for receiving and review.
</div>

</div>


<div class="qr-box">

<div class="qr-title">
SCAN FOR ORGANIZATION VIEW
</div>

{!! QrCode::size(110)->generate(route('org.projects.packets.show', [$project,$packet])) !!}

<div class="qr-desc">
Scan to track status, updates, and item-level progress.
</div>

</div>

</div>





</body>
</html>