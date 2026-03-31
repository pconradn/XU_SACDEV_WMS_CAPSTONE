<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Off Campus Activity Form</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .page {
            width: 100%;
            max-width: 665px;
            margin: auto;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px transparent #000;
            padding-bottom: 30px;
        }

        .header-left {
            display: flex;
            gap: 10px;
        }

        .logo-box {
            width: 50px;
            height: 50px;
            border: 1px solid #000;
        }

        .header-text {
            font-size: 11px;
            line-height: 1.4;
        }

        .header-text strong {
            font-size: 13px;
        }

        .form-code {
            background: #2f6fb3;
            color: #fff;
            font-weight: bold;
            padding: 4px 10px;
            font-size: 12px;
        }

        /* TITLE */
        .title {
            text-align: center;
            margin-top: 20px;
        }

        .title h2 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
        }

        .title span {
            font-size: 11px;
        }

        /* FORM (NO SIDE BORDERS) */
        .form-container {
            margin-top: 15px;
        }

        /* ROW SYSTEM */
        .row {
            border-bottom: 1px solid #2f6fb3;
            min-height: 35px;
            padding: 6px 8px;
        }

        .row:first-child {
            border-top: 1px solid #2f6fb3;
        }

        /* PRINT */
        @media print {
            body {
                margin: 0;
            }

            .print-btn {
                display: none;
            }
        @media print {
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        }
    </style>
</head>

<body>

{{-- PRINT BUTTON --}}
<div style="display:flex; justify-content:flex-end; max-width:800px; margin:10px auto;">
    <button onclick="window.print()"
        class="print-btn"
        style="
            padding:8px 16px;
            background:#2f6fb3;
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

<div class="page">

    {{-- HEADER --}}
    <div class="header">

        {{-- LEFT --}}
        <div class="header-left">
            <div class="logo-box"></div>

            <div class="header-text">
                <strong>STUDENT ACTIVITIES AND LEADERSHIP DEVELOPMENT</strong><br>
                Office of Student Affairs, Xavier University – Ateneo de Cagayan<br>
                Rm 204, 2F Magis Student Complex (Tel) 853-9800 local 9245
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="form-code">
            Form A12 (2023)
        </div>

    </div>

    {{-- TITLE --}}
    <div class="title" style="padding-bottom: 15px;">
        <h2>OFF-CAMPUS ACTIVITY FORM</h2>
        <span>(Please accomplish 2 copies.)</span>
    </div>

    {{-- FORM --}}
    <div class="form-container">

        {{-- ROW 1 --}}
        <div class="row" style="padding:0; min-height:unset; border-bottom: 2px transparent">

            <div style="
                display:grid;
                grid-template-columns: 35% 15% 25% 25%;
                width:100%;
            ">

                {{-- COL 1 --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:1px 6px;
                ">
                    Name of Organization:
                </div>

                {{-- COL 2 --}}
                <div style="
                    padding:4px 6px;
                    font-size:11px;
                ">
                </div>

                {{-- COL 3 --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:1px 6px;
                ">
                    Name of Activity:
                </div>

                {{-- COL 4 --}}
                <div style="
                    padding:4px 6px;
                    font-size:11px;
                ">
                </div>

            </div>

        </div>

        {{-- ROW 2 --}}
        <div class="row" style="padding:0; min-height:unset;">

            <div style="
                display:grid;
                grid-template-columns: 50% 50%;
                width:100%;
            ">

                {{-- LEFT (ORG NAME) --}}
                <div style="
                    padding:8px 35px;
                    font-size:13px;
                    color:#000;
                    
                ">
                    {{ $project->organization->name ?? '—' }}
                </div>

                {{-- RIGHT (ACTIVITY NAME) --}}
                <div style="
                    padding:8px 35px;
                    font-size:13px;
                    color:#000;
                ">
                    {{ $project->title }}
                </div>

            </div>

        </div>

        {{-- ROW 3 --}}
        <div class="row" style="padding:0; min-height:unset; border-bottom: 2px transparent">

            <div style="
                display:grid;
                grid-template-columns: 35% 15% 25% 25%;
                width:100%;
            ">

                {{-- COL 1 --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:1px 6px;
                ">
                    Inclusive Date(s) of Activity: 
                </div>

                {{-- COL 2 --}}
                <div style="
                    padding:4px 6px;
                    font-size:11px;
                ">
                </div>

                {{-- COL 3 --}}
                <div style="
                    background:#2f6fb3;
                    color:#fff;
                    font-weight:600;
                    font-size:11px;
                    padding:1px 6px;
                ">
                    Venue/ Destination:
                </div>

                {{-- COL 4 --}}
                <div style="
                    padding:4px 6px;
                    font-size:11px;
                ">
                </div>

            </div>

        </div>

        @php
            $inclusiveDates = old('inclusive_dates', optional($offcampus)->inclusive_dates ?? '');
            $venue = old('venue_destination', optional($offcampus)->venue_destination ?? '');
        @endphp

        {{-- ROW 4 --}}
        <div class="row" style="padding:0; min-height:unset;">

            <div style="
                display:grid;
                grid-template-columns: 50% 50%;
                width:100%;
            ">

                {{-- LEFT (ORG NAME) --}}
                <div style="
                    padding:8px 35px;
                    font-size:13px;
                    color:#000;
                    
                ">
                    {{ $inclusiveDates }}
                </div>

                {{-- RIGHT (ACTIVITY NAME) --}}
                <div style="
                    padding:8px 35px;
                    font-size:13px;
                    color:#000;
                ">
                    {{ $venue }}
                </div>

            </div>

        </div>

        {{-- ROW 5 --}}
        <div class="row" style="padding:6px 8px;  border-bottom: 2px transparent">

            <div style="font-size:11px; line-height:1.3;">

                {{-- TITLE --}}
                <div style="font-weight:700; margin-bottom:4px;">
                    INSTRUCTIONS
                </div>

                {{-- LIST --}}
                <ol style="
                    margin:0;
                    padding-left:18px;
                ">

                    <li style="margin-bottom:2px;">
                        Accomplish and submit this OFF-CAMPUS ACTIVITY FORM (Form A12) to the Student Activities and Leadership Development (Office of Student Affairs) at least 3 days before the activity. Please encode all information asked.
                    </li>

                    <li style="margin-bottom:2px;">
                        Download electronic copy through XU Student Leaders Facebook Group.
                    </li>

                    <li style="margin-bottom:2px;">
                        Print copies of the <strong>STUDENT TRAVEL AGREEMENT (Form A12.1)</strong> and disseminate to your participants. Ask students to accomplish and submit accomplished forms by the deadline you have set.
                    </li>

                    <li style="margin-bottom:2px;">
                        Attach accomplished <strong>STUDENT TRAVEL AGREEMENT (Form A12.1)</strong> to this form.
                    </li>

                    <li style="margin-bottom:2px;">
                        All students going to an off-campus activity must be accompanied by their organization moderator or his/her duly assigned representative. Representative must also be a full-time faculty/staff member of the University.
                    </li>

                    <li style="margin-bottom:2px;">
                        Only students who have submitted accomplished Form A12.1 shall be listed below and be allowed to participate in the off-campus activity.
                    </li>

                    <li>
                        Any change made in the established schedule/itinerary of the off-campus activity must be immediately communicated to OSA-SACDEV. Please see <strong>IMPLEMENTING GUIDELINES ON OFF-CAMPUS ACTIVITIES</strong> available at OSA-SACDEV for complete information on the conduct of off-campus activities.
                    </li>

                </ol>

            </div>

        </div>

        {{-- ROW 6 --}}
        <div class="row" style="padding:6px 8px;">

            <div style="text-align:left;">

                {{-- MAIN TITLE --}}
                <div style="
                    font-weight:700;
                    font-size:11px;
                ">
                    COMPLETE LIST OF STUDENTS PARTICIPATING IN THE OFF-CAMPUS ACTIVITY
                </div>

                {{-- SUBTEXT --}}
                <div style="
                    font-size:10px;
                    font-style:italic;
                    margin-top:2px;
                ">
                    (Include only those who have submitted accomplished Form A12.1)
                </div>

            </div>

        </div>

        {{-- ROW 7 --}}
        <div class="row" style="padding:0; min-height:unset;">

            <div style="
                display:grid;
                grid-template-columns: 1fr 2% 1fr 2% 1fr 2% 1fr 2% 1fr;
                width:100%;
            ">

                {{-- COL 1 --}}
                <div style="background:#2f6fb3; color:#fff; font-size:10px; text-align:center; padding:4px;">
                    Full Name of Student
                </div>

                {{-- GAP --}}
                <div></div>

                {{-- COL 3 --}}
                <div style="background:#2f6fb3; color:#fff; font-size:10px; text-align:center; padding:4px;">
                    Course and Year
                </div>

                {{-- GAP --}}
                <div></div>

                {{-- COL 5 --}}
                <div style="background:#2f6fb3; color:#fff; font-size:10px; text-align:center; padding:4px;">
                    Student's Mobile Number
                </div>

                {{-- GAP --}}
                <div></div>

                {{-- COL 7 --}}
                <div style="background:#2f6fb3; color:#fff; font-size:10px; text-align:center; padding:4px;">
                    Parent/ Guardian's Name
                </div>

                {{-- GAP --}}
                <div></div>

                {{-- COL 9 --}}
                <div style="background:#2f6fb3; color:#fff; font-size:10px; text-align:center; padding:4px;">
                    Parent/ Guardian's Mobile Number
                </div>

            </div>

        </div>


        @foreach($participants as $index => $p)

        <div class="row" style="padding:2px 0; min-height:unset; border-bottom:none;">

            <div style="
                display:grid;
                grid-template-columns: 1fr 2% 1fr 2% 1fr 2% 1fr 2% 1fr;
                width:100%;
                align-items:center;
                font-size:11px;
            ">

                {{-- COL 1 --}}
                <div style="
                    border-bottom:1px solid #2f6fb3;
                    padding:2px 4px;
                    white-space:nowrap;
                    overflow:hidden;
                    
                ">
                    {{ $index + 1 }}. {{ $p->student_name ?? '' }}
                </div>

                <div></div>

                {{-- COL 3 --}}
                <div style="
                    border-bottom:1px solid #2f6fb3;
                    text-align:center;
                    white-space:nowrap;
                    overflow:hidden;
                    
                ">
                    {{ $p->course_year ?? '' }}
                </div>

                <div></div>

                {{-- COL 5 --}}
                <div style="
                    border-bottom:1px solid #2f6fb3;
                    text-align:center;
                    white-space:nowrap;
                    overflow:hidden;
                    
                ">
                    {{ $p->student_mobile ?? '' }}
                </div>

                <div></div>

                {{-- COL 7 --}}
                <div style="
                    border-bottom:1px solid #2f6fb3;
                    text-align:center;
                    white-space:nowrap;
                    overflow:hidden;
                    
                ">
                    {{ $p->parent_name ?? '' }}
                </div>

                <div></div>

                {{-- COL 9 --}}
                <div style="
                    border-bottom:1px solid #2f6fb3;
                    text-align:center;
                    white-space:nowrap;
                    overflow:hidden;
                    
                ">
                    {{ $p->parent_mobile ?? '' }}
                </div>

            </div>

        </div>

        @endforeach

        {{-- AGREEMENT ROW --}}
        <div class="row" style="padding:6px 8px; margin-top:13px;  border-bottom: 2px transparent; page-break-inside: avoid;
break-inside: avoid;">

            <div style="font-size:11px; line-height:1.4;">

                {{-- TITLE --}}
                <div style="font-weight:700; margin-bottom:4px;">
                    AGREEMENT:
                </div>

                {{-- CONTENT --}}
                <div style="
                    text-align:justify;
                    text-indent:20px;
                ">
                    We, cognizant of the risks and benefits entailed by our activity take upon ourselves the responsibility of ensuring the welfare and safety of everyone participating in our off-campus activity.
                </div>

            </div>

        </div>

        @if($document && $document->signatures && $document->signatures->count())

        @php
            $sigs = $document->signatures->keyBy('role');

            function sig($role, $sigs) {
                return $sigs[$role] ?? null;
            }

            function approvalLine($role, $sigs) {
                $s = $sigs[$role] ?? null;

                if (!$s || $s->status !== 'signed') {
                    return '<div style="font-size:10px; color:#9ca3af;">Pending Approval</div>';
                }

                return '
                    <div style="font-size:10px; color:#2f6fb3; font-weight:600;">
                        ✔ Approved In System · '.$s->signed_at?->format('M d, Y h:i A').'
                    </div>
                ';
            }
        @endphp


        {{-- SIGNATURE ROW --}}
        <div class="row" style="padding:12px 8px; border-bottom:none; page-break-inside: avoid;
break-inside: avoid;">

            <div style="
                display:grid;
                grid-template-columns:1fr 1fr;
                gap:40px;
                text-align:center;
            ">

                {{-- PROJECT HEAD --}}
                <div style="page-break-inside: avoid;">

                    {!! approvalLine('project_head', $sigs) !!}

                    {{-- SIGNATURE LINE --}}
                    <div style="
                        margin-top:18px;
                        border-bottom:1px solid #000;
                        padding-bottom:2px;
                        font-weight:600;
                    ">
                        {{ sig('project_head', $sigs)?->user?->name ?? '—' }}
                    </div>

                    <div style="font-size:11px;">
                        Name and Signature of Project Head
                    </div>

                </div>


                {{-- PRESIDENT --}}
                <div style="page-break-inside: avoid;">

                    {!! approvalLine('president', $sigs) !!}

                    {{-- SIGNATURE LINE --}}
                    <div style="
                        margin-top:18px;
                        border-bottom:1px solid #000;
                        padding-bottom:2px;
                        font-weight:600;
                    ">
                        {{ sig('president', $sigs)?->user?->name ?? '—' }}
                    </div>

                    <div style="font-size:11px;">
                        Name and Signature of Organization President
                    </div>

                </div>

            </div>

        </div>

        @endif

        {{-- MODERATOR DECLARATION --}}
        <div class="row" style="padding:6px 8px;  border-bottom: 2px transparent; page-break-inside: avoid;
break-inside: avoid;">

            <div style="
                font-size:11px;
                line-height:1.4;
                text-align:justify;
                text-indent:20px;
            ">

                I hereby agree to accompany the above-mentioned students in their off-campus activity. 
                As moderator/ duly designated representative of the University, I acknowledge that I have read 
                and understood the responsibilities of accompanying Moderator and assume full responsibility 
                for the proceedings of the activity.

            </div>

        </div>

        {{-- MODERATOR SIGNATURE --}}
        <div class="row" style="padding:12px 8px; border-bottom:none; page-break-inside: avoid;
break-inside: avoid;">

            <div style="
                display:grid;
                grid-template-columns:1fr 1fr;
                width:100%;
            ">

                {{-- LEFT (EMPTY) --}}
                <div></div>

                {{-- RIGHT (SIGNATURE) --}}
                <div style="
                    text-align:center;
                    page-break-inside: avoid;
                ">

                    {{-- APPROVAL LINE --}}
                    {!! approvalLine('moderator', $sigs) !!}

                    {{-- SIGNATURE LINE --}}
                    <div style="
                        margin-top:18px;
                        border-bottom:1px solid #000;
                        padding-bottom:2px;
                        font-weight:600;
                    ">
                        {{ sig('moderator', $sigs)?->user?->name ?? '—' }}
                    </div>

                    {{-- LABEL --}}
                    <div style="font-size:11px;">
                        Name and Signature of Moderator/ Representative
                    </div>

                </div>

            </div>


            {{-- ENDORSEMENT AND APPROVAL --}}
        <div class="row" style="padding:6px 8px;  border-bottom: 2px transparent">

            <div style="font-size:11px; line-height:1.4;">

                {{-- TITLE --}}
                <div style="font-weight:700; margin-bottom:4px;">
                    ENDORSEMENT AND APPROVAL:
                </div>

                {{-- CONTENT --}}
                <div style="
                    text-align:justify;
                    text-indent:20px;
                ">
                    This is to certify that the aforementioned organization has successfully complied with all the requirements set for the aforementioned off-campus activity.
                </div>

            </div>

        </div>



        {{-- SACDEV / OSA APPROVAL --}}
        <div class="row" style="padding:12px 8px; border-bottom:none; page-break-inside: avoid;
break-inside: avoid;">

            <div style="
                display:grid;
                grid-template-columns:1fr 1fr;
                width:100%;
                text-align:center;
            ">

                {{-- SACDEV --}}
                <div style="page-break-inside: avoid;">

                    <div style="font-size:11px; margin-bottom:6px; text-align:left">
                        Endorsed by:
                    </div>

                    {!! approvalLine('sacdev_admin', $sigs) !!}

                    <div style="
                        margin-top:10px;
                        font-weight:600;
                        text-transform:uppercase;
                    ">
                        {{ sig('sacdev_admin', $sigs)?->user?->name ?? '—' }}
                    </div>

                    <div style="font-size:11px;">
                        Student Activities and Leadership Development Head
                    </div>

                </div>


                {{-- OSA ADMIN --}}
                <div style="page-break-inside: avoid;">

                    <div style="font-size:11px; margin-bottom:6px; text-align:left; margin-left: 20px">
                        Approved by:
                    </div>

                    {!! approvalLine('osa_admin', $sigs) !!}

                    <div style="
                        margin-top:10px;
                        font-weight:600;
                        text-transform:uppercase;
                    ">
                        {{ sig('osa_admin', $sigs)?->user?->name ?? '_________________________________' }}
                    </div>

                    <div style="font-size:11px;">
                        Director of Student Affairs
                    </div>

                </div>

            </div>

        </div>

        </div>
        {{-- ROW 11 --}}


    </div>

</div>

</body>
</html>