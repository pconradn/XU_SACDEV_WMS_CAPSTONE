<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document Verification</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        .card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            font-size: 12px;
            border-radius: 999px;
            font-weight: bold;
        }

        .valid {
            background: #ecfdf5;
            color: #065f46;
        }

        .row {
            margin-bottom: 12px;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
            color: #475569;
        }

        .value {
            color: #0f172a;
        }

        .header {
            margin-bottom: 16px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .sub {
            font-size: 13px;
            color: #64748b;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="card">

        {{-- HEADER --}}
        <div class="header">
            <h2>Document Verification</h2>
            <p class="sub">System-generated document authenticity check</p>
        </div>

        {{-- STATUS --}}
        <div style="margin-bottom:16px;">
            <span class="status valid">
                ✔ Valid Document
            </span>
        </div>

        {{-- DETAILS --}}
        <div class="row">
            <span class="label">Form Type:</span>
            <span class="value">{{ $document->formType->name }}</span>
        </div>

        <div class="row">
            <span class="label">Project:</span>
            <span class="value">{{ $document->project->title }}</span>
        </div>

        <div class="row">
            <span class="label">Organization:</span>
            <span class="value">{{ $document->project->organization->name }}</span>
        </div>

        <div class="row">
            <span class="label">Document Status:</span>
            <span class="value">{{ ucfirst($document->status) }}</span>
        </div>

        @if($document->reviewed_at)
        <div class="row">
            <span class="label">Reviewed At:</span>
            <span class="value">{{ $document->reviewed_at }}</span>
        </div>
        @endif

        {{-- OPTIONAL: Packet reference --}}
        @php
            $packetItem = \App\Models\ExternalPacketItem::where('document_id', $document->id)->first();
            $packet = $packetItem?->packet ?? null;
        @endphp

        @if($packet)
        <div class="row">
            <span class="label">Included in Packet:</span>
            <span class="value">{{ $packet->reference_no }}</span>
        </div>

        <div class="row">
            <span class="label">Packet Status:</span>
            <span class="value">{{ ucfirst($packet->status) }}</span>
        </div>
        @endif

    </div>

</div>

</body>
</html>