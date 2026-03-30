<!DOCTYPE html>
<html>
<head>
    <title>Student Travel Form</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .container {
            width: 800px;
            margin: auto;
        }

        .title {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 20px;
        }

        .row {
            margin-bottom: 6px;
        }

        .label {
            font-weight: bold;
        }

        .line {
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 250px;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="title">
        STUDENT TRAVEL AGREEMENT / PARENT’S CONSENT FORM
    </div>

    <div class="grid">

        <div>
            <div class="row"><span class="label">Name of Activity:</span> {{ $data['activity_name'] }}</div>
            <div class="row"><span class="label">Inclusive Dates:</span> {{ $data['inclusive_dates'] }}</div>
            <div class="row"><span class="label">Accommodation:</span> {{ $data['accommodation'] }}</div>
            <div class="row"><span class="label">Date of Departure:</span> {{ $data['departure_date_formatted'] }}</div>
            <div class="row"><span class="label">Time:</span> {{ $data['departure_time'] }}</div>
        </div>

        <div>
            <div class="row"><span class="label">Organization:</span> {{ $project->organization->name }}</div>
            <div class="row"><span class="label">Venue:</span> {{ $data['venue'] }}</div>
            <div class="row"><span class="label">Address:</span> {{ $data['address'] }}</div>
            <div class="row"><span class="label">Expected Return:</span> {{ $data['return_date_formatted'] }}</div>
            <div class="row"><span class="label">Time:</span> {{ $data['return_time'] }}</div>
        </div>

    </div>

    <div style="margin-top:30px;">
        I have read, understood and acknowledge full acceptance and conformity.
    </div>

    <div style="margin-top:40px;">
        Student Name: ___________________________
    </div>

    <div style="margin-top:20px;">
        Parent / Guardian: ___________________________
    </div>

</div>

<button onclick="window.print()">Print</button>

</body>
</html>