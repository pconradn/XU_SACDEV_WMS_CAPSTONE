<!DOCTYPE html>
<html>

<head>

<meta charset="utf-8">

<title>Off-Campus Clearance</title>

<style>

body{
font-family:Arial;
font-size:12px;
}

.container{
width:800px;
margin:auto;
}

.header{
text-align:center;
margin-bottom:30px;
}

.title{
font-size:18px;
font-weight:bold;
margin-top:10px;
}

.section{
margin-top:25px;
}

.label{
font-weight:bold;
}

.signature-row{
margin-top:60px;
display:flex;
justify-content:space-between;
}

.signature-box{
width:220px;
text-align:center;
}

.line{
border-top:1px solid black;
margin-top:50px;
}

.footer{
margin-top:40px;
font-size:10px;
text-align:center;
color:#555;
}

</style>

</head>

<body>

<div class="container">

<div class="header">

<div>
Xavier University – Ateneo de Cagayan
</div>

<div class="title">
OFF-CAMPUS ACTIVITY CLEARANCE
</div>

<div>
Reference: {{ $project->clearance_reference }}
</div>

</div>


<div class="section">

<div>
<span class="label">Organization:</span>
{{ $organization->name }}
</div>

<div>
<span class="label">Project Title:</span>
{{ $project->title }}
</div>

@if($proposal)

<div>
<span class="label">Description:</span>
{{ $proposal->activity_description ?? '' }}
</div>

@endif

@if($activity)

<div>
<span class="label">Venue:</span>
{{ $activity->venue ?? '' }}
</div>

@endif

@if($proposal)

<div>
<span class="label">Date:</span>
{{ optional($proposal->start_date)->format('F d, Y') }}
</div>

@endif

</div>


@if($participants->count())

<div class="section">

<div class="label">
Participants
</div>

<ul>

@foreach($participants as $p)

<li>{{ $p->student_name }}</li>

@endforeach

</ul>

</div>

@endif


<div class="signature-row">

<div class="signature-box">
<div class="line"></div>
Project Head
</div>

<div class="signature-box">
<div class="line"></div>
Organization President
</div>

<div class="signature-box">
<div class="line"></div>
Moderator
</div>

</div>


<div class="signature-row">

<div class="signature-box">
<div class="line"></div>
Department Chair / Dean
</div>




<div class="footer">

Verification Hash:
{{ $hash }}

</div>

</div>

</body>

</html>