<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Budget Proposal Print</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
        }

        .page {
            max-width: 800px;
            margin: auto;
        }

        .section {
            border: 1px solid #000;
            margin-bottom: 10px;
        }

        .section-header {
            background: #f1f1f1;
            text-align: center;
            font-weight: bold;
            padding: 4px;
        }

        .content {
            padding: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #000;
            padding: 4px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        @media print {
            .print-btn { display: none; }
        }
    </style>
</head>

<body>

<div style="display:flex; justify-content:flex-end; max-width:800px; margin:10px auto;">
    <button onclick="printDocument()" class="print-btn">
        Print Document
    </button>
</div>

<div class="page">

    {{-- HEADER --}}
    @include('admin.projects.documents.budget-proposal.print.header')

    {{-- BUDGET SECTIONS --}}
    @include('admin.projects.documents.budget-proposal.print.sections')

    {{-- TOTALS --}}
    @include('admin.projects.documents.budget-proposal.print.totals')

    @include('admin.projects.documents.budget-proposal.print.sources')

    {{-- SIGNATURES --}}
    <div class="no-break">
        @include('admin.projects.documents.budget-proposal.print.signatures')
    </div>

</div>

<script>
function printDocument() {
    window.print();
}
</script>

</body>
</html>