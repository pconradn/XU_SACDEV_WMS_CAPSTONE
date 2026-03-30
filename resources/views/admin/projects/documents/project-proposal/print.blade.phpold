<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Project Proposal Print</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
        }

        .page {
            width: 100%;
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

        h1, h2 {
            margin: 0;
        }

        .center {
            text-align: center;
        }

        .grid {
            display: flex;
            gap: 10px;
        }

        .col {
            flex: 1;
        }

        @media print {

            body {
                margin: 0;
                padding: 0;
            }

            .print-btn {
                display: none !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .page {
                max-width: 100%;
            }

        }

        @media print {

            .no-break {
                page-break-inside: avoid;
                break-inside: avoid;
                page-break-before: auto;
                page-break-after: auto;
            }

        }
    </style>
</head>

<body>

<div style="display:flex; justify-content:flex-end; max-width:800px; margin:10px auto;">
    <button onclick="printDocument()"
        class="print-btn"
        style="
            padding:8px 16px;
            background:#111827;
            color:white;
            font-size:12px;
            font-weight:600;
            border:none;
            border-radius:6px;
            cursor:pointer;
        ">
        Print Document
    </button>
</div>

<div class="page" id="print-area">

    {{-- HEADER --}}
    @include('admin.projects.documents.project-proposal.print.partials.header')

    {{-- DATES + VENUE --}}
    @include('admin.projects.documents.project-proposal.print.partials.schedule')

    @include('admin.projects.documents.project-proposal.print.partials.classification')

    {{-- SUMMARY --}}
    @include('admin.projects.documents.project-proposal.print.partials.summary')

    @include('admin.projects.documents.project-proposal.print.partials.objectives-partners')

    {{-- BUDGET --}}
    @include('admin.projects.documents.project-proposal.print.partials.budget') 

    {{-- GUESTS + PLAN --}}
    @include('admin.projects.documents.project-proposal.print.partials.guests-plan') 

    {{-- SIGNATURES --}}
    <div class="no-break">
        @include('admin.projects.documents.project-proposal.print.partials.signatures')
    </div>

</div>

<script>
function printDocument() {
    window.print();
}
</script>

</body>
</html>