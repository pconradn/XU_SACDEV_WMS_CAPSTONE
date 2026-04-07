<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>External Packet</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            margin: 0;
        }

        .page {
            width: 700px;
            margin: 0 auto;
            padding: 20px 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 13px;
        }

        .section {
            margin-bottom: 14px;
        }

        .label {
            font-weight: bold;
        }

        .box {
            border: 1px solid #2f6fb3;
            padding: 8px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .table th, .table td {
            border: 1px solid #2f6fb3;
            padding: 6px;
            text-align: left;
        }

        .table th {
            background: #2f6fb3;
            color: white;
        }

        .signature {
            margin-top: 40px;
        }

        .signature-line {
            margin-top: 30px;
            border-top: 1px solid black;
            width: 220px;
        }

        .qr {
            text-align: right;
            margin-top: 10px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <div class="title">EXTERNAL PACKET COVER</div>
        <div class="subtitle">SACDEV Document Transmission</div>
    </div>

    {{-- BASIC INFO --}}
    <div class="section">
        <div><span class="label">Project:</span> {{ $packet->project->title }}</div>
        <div><span class="label">Destination:</span> {{ $packet->destination }}</div>
        <div><span class="label">Reference No:</span> {{ $packet->reference_no }}</div>
        <div><span class="label">Date Created:</span> {{ $packet->created_at->format('M d, Y') }}</div>
    </div>

    {{-- ITEMS --}}
    <div class="section">
        <div class="label">Items Included:</div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Item</th>
                    <th style="width:120px;">Type</th>
                    <th style="width:140px;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($packet->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->label }}</td>
                        <td>{{ ucfirst($item->type) }}</td>
                        <td>{{ $item->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- REMARKS --}}
    @if($packet->remarks)
        <div class="section">
            <div class="label">Remarks:</div>
            <div class="box">
                {{ $packet->remarks }}
            </div>
        </div>
    @endif

    {{-- QR --}}
    <div class="qr">
        {!! QrCode::size(100)->generate($receiveUrl) !!}
        <div style="font-size:10px;">
            Scan to process packet
        </div>
    </div>

    {{-- SIGNATURE --}}
    <div class="signature">

        <div>
            Prepared by:
            <div class="signature-line"></div>
            <div style="font-size:11px;">
                {{ $packet->creator->name ?? 'N/A' }}
            </div>
        </div>

        <div style="margin-top:20px;">
            Received by:
            <div class="signature-line"></div>
            <div style="font-size:11px;">
                SACDEV Staff
            </div>
        </div>

    </div>

</div>

</body>
</html>