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
margin-bottom:30px;
}

.header h1{
font-size:22px;
margin:0;
}

.header h2{
font-size:16px;
font-weight:normal;
margin-top:4px;
}

.info-table{
width:100%;
border-collapse:collapse;
margin-bottom:25px;
font-size:14px;
}

.info-table td{
padding:6px;
}

.section-title{
font-weight:bold;
margin-top:20px;
margin-bottom:10px;
font-size:14px;
border-bottom:1px solid #000;
padding-bottom:3px;
}

.checklist div{
margin:4px 0;
font-size:14px;
}

table{
width:100%;
border-collapse:collapse;
font-size:13px;
margin-top:10px;
}

th,td{
border:1px solid #000;
padding:6px;
text-align:left;
}

.qr-container{
margin-top:40px;
display:flex;
justify-content:space-between;
}

.qr-box{
text-align:center;
}

.qr-label{
font-size:12px;
margin-top:5px;
}

.print-btn{
margin-bottom:20px;
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

</div>



<table class="info-table">

<tr>
<td><strong>Packet ID:</strong></td>
<td>{{ $packet->packet_code }}</td>
</tr>

<tr>
<td><strong>Project:</strong></td>
<td>{{ $project->title }}</td>
</tr>

<tr>
<td><strong>Generated:</strong></td>
<td>{{ \Carbon\Carbon::parse($packet->generated_at)->format('F d, Y') }}</td>
</tr>

</table>



<div class="section-title">
Documents Included
</div>

<div class="checklist">

<div>
@if($packet->has_solicitation_letter) ☑ @else ☐ @endif
Solicitation / Sponsorship Letters
</div>

<div>
@if($packet->has_disbursement_voucher) ☑ @else ☐ @endif
Disbursement Voucher
</div>

<div>
@if($packet->has_receipts) ☑ @else ☐ @endif
Official Receipts
</div>

<div>
@if($packet->has_collection_report) ☑ @else ☐ @endif
Collection Report
</div>

<div>
@if($packet->has_certificates) ☑ @else ☐ @endif
Certificates
</div>

</div>



@if($packet->letters->count())

<div class="section-title">
Solicitation Letters
</div>

<table>

<tr>
<th>Control Number</th>
<th>Organization</th>
</tr>

@foreach($packet->letters as $letter)

<tr>
<td>{{ $letter->control_number }}</td>
<td>{{ $letter->organization_name }}</td>
</tr>

@endforeach

</table>

@endif



@if($packet->receipts->count())

<div class="section-title">
Official Receipts
</div>

<table>

<tr>
<th>Receipt Number</th>
</tr>

@foreach($packet->receipts as $receipt)

<tr>
<td>OR #{{ $receipt->or_number }}</td>
</tr>

@endforeach

</table>

@endif



@if($packet->dvs->count())

<div class="section-title">
Disbursement Vouchers
</div>

<table>

<tr>
<th>Reference</th>
<th>Description</th>
<th>Amount</th>
</tr>

@foreach($packet->dvs as $dv)

<tr>
<td>{{ $dv->dv_reference }}</td>
<td>{{ $dv->dv_label }}</td>
<td>{{ $dv->amount }}</td>
</tr>

@endforeach

</table>

@endif



@if($packet->other_items)

<div class="section-title">
Other Items
</div>

<div style="font-size:13px">
{{ $packet->other_items }}
</div>

@endif



<div class="qr-container">

<div class="qr-box">

{!! QrCode::size(120)->generate(route('org.projects.packet.show', [$project,$packet])) !!}

<div class="qr-label">
Organization View
</div>

</div>



<div class="qr-box">

{!! QrCode::size(120)->generate(url('/admin/projects/'.$project->id.'/documents')) !!}

<div class="qr-label">
SACDEV Verification
</div>

</div>

</div>



</div>

</body>
</html>