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

        /* PAGE */
        .page {
            width: 100%;
            max-width: 800px;
            margin: auto;
        }

        /* MAIN FORM */
        .form-container {
            border: 2px solid #2f6fb3; /* BLUE */
        }

        /* ROW */
        .row {
            border-bottom: 1px solid #2f6fb3;
            min-height: 35px;
            padding: 0px 5px;
        }

        .row:last-child {
            border-bottom: none;
        }

        /* PRINT */
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
        }
        .inner-grid {
            display: grid !important; /* force grid */
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: auto 1fr;
            width: 100%;
        }

        /* CELLS */
        .cell {
            border: 1px solid black; /* debug */
            padding: 2px;
            font-size: 11px;

            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body>

{{-- PRINT BUTTON --}}
<div style="display:flex; justify-content:flex-end; max-width:800px; margin:10px auto;">
    <button onclick="printDocument()"
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

        
    <div style="display:flex; justify-content:space-between; align-items:center; padding:6px 10px; font-size:11px; ">

        
        <div style="font-style:italic;">
            Your org letterhead here. Please use A4 paper.
        </div>

        
        <div style="background:#2f6fb3; color:#fff; padding:4px 10px; font-weight:bold;">
            Form A1 <span style="font-weight:normal;">(2023 Edition)</span>
        </div>

    </div>

    
    <div style="text-align:center; padding:10px 0; margin-top:50px;">
        <div style="font-size:18px; font-weight:bold; letter-spacing:0.5px;">
            PROJECT PROPOSAL
        </div>
        <div style="font-size:11px;">
            (Please accomplish 4 copies)
        </div>
    </div>

    {{-- ACTUAL FORM --}}
    <div class="form-container">

        <div style="background:#2f6fb3; color:#fff; text-align:center; font-weight:bold; padding:4px;">
            PROJECT DEFINITION
        </div>

        <div class="row" id="row-2">
            <div style="font-size:12px; margin-bottom:4px;">
                <strong>Name/ Title of Project:</strong>
            </div>

            <div style="text-align:center; font-weight:bold; font-size:15px; margin-bottom:8px;">
                {{ $project->title }}
            </div>
        </div>

        {{--imp dates--}}
        <div class="row" id="row-3">

            <div class="inner-grid">

                {{-- ROW 1 --}}
                <div >
                    <strong>Proposed Implementation Date(s):</strong>
                </div>

                <div style="padding:6px 8px; border-right:1px solid #2f6fb3;">
                </div>

                <div style="padding:0px 2px;">
                    <strong>Time: (Start and End)</strong>
                </div>


                {{-- ROW 2 --}}
                <div style="
                    padding:1px 8px;
                    border-right:1px solid #2f6fb3;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                ">
                    <span style="color:#2f6fb3; font-style: italic;  font-size:11px;">
                        Starting Date:
                    </span>

                    <div style="text-align:center; font-size:12px; font-weight:600; margin-bottom:4px;">
                        {{ $proposal->start_date 
                            ? \Carbon\Carbon::parse($proposal->start_date)->format('M d, Y') 
                            : '—' }}
                    </div>
                </div>


                <div style="
                    padding:1px 8px;
                    border-right:1px solid #2f6fb3;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                ">
                    <span style="color:#2f6fb3; font-style: italic;  font-size:11px;">
                        End Date:
                    </span>

                    <div style="text-align:center; font-size:12px; font-weight:600; margin-bottom:4px;">
                        {{ $proposal->end_date 
                            ? \Carbon\Carbon::parse($proposal->end_date)->format('M d, Y') 
                            : '—' }}
                    </div>
                </div>


                <div style="
                    padding:10px 8px;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                ">


                    <div style="text-align:center; font-size:12px; font-weight:600;">
                        {{ $proposal->start_time && $proposal->end_time 
                            ? \Carbon\Carbon::parse($proposal->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($proposal->end_time)->format('h:i A') 
                            : '—' }}
                    </div>
                </div>

            </div>

        </div>

        {{--imp venues--}}
        <div class="row" id="row-4">

            <div class="inner-grid">

                {{-- ROW 1 --}}
                <div >
                    <strong>Proposed Venue:</strong>
                </div>

                <div style="padding:1px 8px; ">
                </div>

                <div style="padding:1px 2px;">
                </div>


                {{-- ROW 2 --}}
                <div style="
                    padding:1px 8px;
                    border-right:1px solid #2f6fb3;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                ">
                    <span style="color:#2f6fb3; font-style: italic;  font-size:11px; ">
                        For off-campus activities, please accomplish off-campus activity permit after approval of this proposal.
                    </span>


                </div>


                <div style="
                    padding:0px 8px;
                    border-right:1px solid #2f6fb3;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                    
                ">
                    <span style="color:#2f6fb3; font-style: italic;  font-size:11px;">
                        On Campus: 
                    </span>

                    <div style="text-align:center; font-size:12px; font-weight:600; margin-bottom:4px">
                        {{ $proposal->on_campus_venue ?? '—' }}
                    </div>
                </div>


                <div style="
                    padding:0px 8px;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                    margin-bottom:4px
                    
                ">
                    <span style="color:#2f6fb3; font-style: italic;  font-size:11px; ">
                        Off Campus: 
                    </span>


                    <div style="text-align:center; font-size:12px; font-weight:600; margin-bottom:4px ">
                        {{ $proposal->off_campus_venue ?? '—' }}
                    </div>
                </div>

            </div>

        </div>


        <div class="row" id="row-5">

            <div style="
                display:grid;
                grid-template-columns: 1fr 1fr;
                height:100%;
            ">

                {{-- LEFT --}}
                <div style="
                    padding:2px 1px;
                    border-right:1px solid #2f6fb3;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                ">

                    <div>
                        <strong>Nature of Engagement:</strong>
                    </div>

                    <div style="
                        margin-top:4px;
                        font-size:12px;
                        color:#000;
                    ">

                        <span style="margin-right:12px;">
                            {{ $proposal->engagement_type === 'organizer' ? '☑' : '☐' }} Organizer
                        </span>

                        <span style="margin-right:12px;">
                            {{ $proposal->engagement_type === 'partner' ? '☑' : '☐' }} Partner
                        </span>

                        <span>
                            {{ $proposal->engagement_type === 'participant' ? '☑' : '☐' }} Participant
                        </span>

                    </div>

                </div>


                {{-- RIGHT --}}
                <div style="
                    padding:2px 8px;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                ">

                    <div>
                        If participant, state the main organizer below:
                    </div>

                    <div style="
                        text-align:center;
                        font-size:12px;
                        font-weight:600;
                        margin-bottom:4px;
                    ">
                        {{ $proposal->main_organizer ?? '—' }}
                    </div>

                </div>

            </div>

        </div>


        <div class="row" id="row-6">

            @php
                $nature = $proposal->project_nature
                    ? explode(', ', $proposal->project_nature)
                    : [];

                $natureOther = $proposal->project_nature_other ?? null;

                function checked($val, $arr) {
                    return in_array($val, $arr) ? '☑' : '☐';
                }
            @endphp

            <div style="
                display:flex;
                flex-direction:column;
                justify-content:space-between;
                height:100%;
                padding:2px 1px;
            ">

                {{-- TITLE --}}
                <div>
                    <strong>Nature of Project:</strong>
                </div>

                {{-- OPTIONS --}}
                <div style="
                    display:grid;
                    grid-template-columns: 1fr 1fr 1fr;
                    margin-top:4px; 
                    font-size:12px;
                    padding:1px 15px;
                ">

                    {{-- COL 1 --}}
                    <div>
                        {{ checked('assembly', $nature) }} Assembly<br>
                        {{ checked('film_showing', $nature) }} Film Showing<br>
                        {{ checked('lecture_seminar_workshop', $nature) }} Lecture/Seminar/Workshop
                    </div>

                    {{-- COL 2 --}}
                    <div>
                        {{ checked('convention', $nature) }} Convention/Congress<br>
                        {{ checked('outreach', $nature) }} Outreach<br>
                        {{ checked('fund_raising', $nature) }} Fund Raising
                    </div>

                    {{-- COL 3 --}}
                    <div>
                        {{ checked('contest', $nature) }} Contest/Competition<br>
                        {{ checked('other', $nature) }} Others
                        @if(in_array('other', $nature) && $natureOther)
                            — {{ $natureOther }}
                        @endif
                    </div>

                </div>

            </div>

        </div>

        <div class="row" id="row-13">

            @php
                $sdg = $proposal->sdg
                    ? explode(', ', $proposal->sdg)
                    : [];
            @endphp

            <div style="
                display:flex;
                flex-direction:column;
                justify-content:space-between;
                height:100%;
                padding:2px 1px;
            ">

                {{-- TITLE --}}
                <div>
                    <strong>Target Sustainable Development Goal:</strong>
                </div>

                {{-- OPTIONS --}}
                <div style="
                    display:grid;
                    grid-template-columns: 1fr 1fr 1fr;
                    margin-top:4px;
                    font-size:12px;
                    padding:1px 15px;
                ">

                    {{-- COL 1 --}}
                    <div>
                        {{ in_array('No Poverty', $sdg) ? '☑' : '☐' }} No Poverty<br>
                        {{ in_array('Affordable and Clean Energy', $sdg) ? '☑' : '☐' }} Affordable and Clean Energy<br>
                        {{ in_array('Sustainable Cities and Communities', $sdg) ? '☑' : '☐' }} Sustainable Cities and Communities<br>
                        {{ in_array('Life Below Water', $sdg) ? '☑' : '☐' }} Life Below Water<br>
                        {{ in_array('Zero Hunger', $sdg) ? '☑' : '☐' }} Zero Hunger
                    </div>

                    {{-- COL 2 --}}
                    <div>
                        
                        {{ in_array('Decent Work and Economic Growth', $sdg) ? '☑' : '☐' }} Decent Work and Economic Growth<br>
                        {{ in_array('Responsible Consumption and Production', $sdg) ? '☑' : '☐' }} Responsible Consumption and Production<br>
                        {{ in_array('Peace and Justice Strong institutions', $sdg) ? '☑' : '☐' }} Peace and Justice Strong institutions<br>
                        {{ in_array('Quality Education', $sdg) ? '☑' : '☐' }} Quality Education<br>
                        {{ in_array('Industry, Innovation and Infrastructure', $sdg) ? '☑' : '☐' }} Industry, Innovation and Infrastructure
                    </div>

                    {{-- COL 3 --}}
                    <div>
                        
                        {{ in_array('Clean Water and Sanitation', $sdg) ? '☑' : '☐' }} Clean Water and Sanitation<br>
                        {{ in_array('Gender Equality', $sdg) ? '☑' : '☐' }} Gender Equality<br>
                        {{ in_array('Reduce Inequalities', $sdg) ? '☑' : '☐' }} Reduce Inequalities<br>
                        {{ in_array('Climate Action', $sdg) ? '☑' : '☐' }} Climate Action<br>
                        {{ in_array('Partnerships for the Goals', $sdg) ? '☑' : '☐' }} Partnerships for the Goals
                    </div>

                </div>

            </div>

        </div>


        <div class="row" id="row-7">

            @php
                $af = $proposal->area_focus
                    ? explode(', ', $proposal->area_focus)
                    : [];

    
            @endphp

            <div style="
                display:flex;
                flex-direction:column;
                justify-content:space-between;
                height:100%;
                padding:2px 1px;
            ">

                {{-- TITLE --}}
                <div>
                    <strong>Area Focus:</strong>
                </div>

                {{-- OPTIONS --}}
                <div style="
                    display:grid;
                    grid-template-columns: 1fr 1fr 1fr;
                    margin-top:4px;
                    font-size:12px;
                    padding:1px 15px;
                ">

                    <div>
                        {{ checked('organizational_development', $af) }}
                        Organizational Development
                    </div>

                    <div>
                        {{ checked('student_services', $af) }}
                        Student Services and Formation
                    </div>

                    <div>
                        {{ checked('community_involvement', $af) }}
                        Community Involvement
                    </div>

                </div>

            </div>

        </div>


        <div class="row" id="row-8">

            <div style="
                display:grid;
                grid-template-rows: auto 1fr;
                height:100%;
                padding:2px 1px;
            ">

                {{-- ROW 1: TITLE --}}
                <div>
                    <strong>
                        Brief Description of the Project
                    </strong>

                    <span style="color:#2f6fb3; font-style: italic;  font-size:11px;">
                        (In 1-2 sentences, please describe the nature or intent of the project.)
                    </span>
                </div>


                {{-- ROW 2: CONTENT --}}
                <div style="
                    margin-top:4px;
                    font-size:12px;
                    padding:2px 15px;
                    display:flex;
                    align-items:flex-start;
                ">

                    {{ $proposal->description ?? '—' }}

                </div>

            </div>

        </div>


        <div class="row" id="row-9">

            <div style="
                display:grid;
                grid-template-rows: auto 1fr;
                height:100%;
                padding:2px 1px;
            ">

                {{-- ROW 1: TITLE --}}
                <div>
                    <strong>
                        Link of the Project with the Organization
                    </strong>

                    <span style="color:#2f6fb3; font-style: italic;  font-size:11px;">
                        (State below the link of the project with the mission/purpose of the organization and the UAP.)
                    </span>
                </div>


                {{-- ROW 2: CONTENT --}}
                <div style="
                    margin-top:4px;
                    font-size:12px;
                    padding:2px 15px;
                    display:flex;
                    align-items:flex-start;
                    min-height:30px;
                ">

                    {{ $proposal->org_link ?? '—' }}

                </div>

            </div>

        </div>


        <div class="row" id="row-10" style="min-height:5px; ">

            <div style="
                display:grid;
                grid-template-columns: 15% 45% 20% 20%;
                height:100%;
                padding:2px 1px;
                min-height:5px;
            ">

                {{-- COL 1 --}}
                <div style="
                    padding:2px 0px;
                    border-right:1px solid #2f6fb3;
                    display:flex;
                    align-items:center;
                ">
                    <strong>Your Org Cluster:</strong>
                </div>


                {{-- COL 2 --}}
                <div style="
                    padding:2px 8px;
                    display:flex;
                    align-items:center;
                    font-size:12px;
                    font-weight:600;
                ">
                    {{ $proposal->org_cluster ?? '—' }}
                </div>


                {{-- COL 3 (EMPTY SPACE) --}}
                <div></div>


                {{-- COL 4 (EMPTY SPACE) --}}
                <div></div>

            </div>

        </div>

        <div class="row" id="row-11">

            <div style="
                display:grid;
                grid-template-columns: 1fr 1fr;
                height:100%;
                padding:1px 0px;
            ">

                {{-- OBJECTIVES --}}
                <div style="
                    padding:2px 2px;
                    border-right:1px solid #2f6fb3;
                    display:flex;
                    flex-direction:column;
                    justify-content:flex-start;
                ">

                    <div>
                        <strong>Objectives:</strong><br>
                        <span style="color:#2f6fb3; font-style: italic;  font-size:11px;">
                            By the end of the project…
                        </span>
                    </div>

                    <ol style="
                        margin-top:1px;
                        padding-left:18px;
                        font-size:12px;
                    ">
                        @forelse($proposal->objectives as $obj)
                            <li style="margin-bottom:4px;">
                                {{ $obj->objective }}
                            </li>
                        @empty
                            <li>—</li>
                        @endforelse
                    </ol>

                </div>


                {{-- INDICATORS --}}
                <div style="
                    padding:2px 4px;
                    display:flex;
                    flex-direction:column;
                    justify-content:flex-start;
                ">

                    <div>
                        <strong>Targets/Success Indicators:</strong><br>
                        <span style="color:#2f6fb3; font-style: italic;  font-size:11px;">
                            (What will determine whether you have achieved your objectives or not)
                        </span>
                    </div>

                    <ol style="
                        margin-top:1px;
                        padding-left:18px;
                        font-size:12px;
                    ">
                        @forelse($proposal->indicators as $ind)
                            <li style="margin-bottom:4px;">
                                {{ $ind->indicator }}
                            </li>
                        @empty
                            <li>—</li>
                        @endforelse
                    </ol>

                </div>

            </div>

        </div>

        <div class="row" id="row-12">

            <div style="
                display:grid;
                grid-template-columns: 1fr 1fr;
                height:100%;
                padding:2px 1px;
            ">

                {{-- PARTNERS --}}
                <div style="
                    padding:2px 4px;
                    border-right:1px solid #2f6fb3;
                    display:flex;
                    flex-direction:column;
                    justify-content:flex-start;
                ">

                    <div>
                        <strong>Target Partners/Sponsors:</strong>
                    </div>

                    <ol style="
                        margin-top:2px;
                        padding-left:18px;
                        font-size:12px;
                    ">
                        @forelse($proposal->partners as $p)
                            <li style="margin-bottom:4px;">
                                {{ $p->name }}
                            </li>
                        @empty
                            <li>—</li>
                        @endforelse
                    </ol>

                </div>


                {{-- ROLES --}}
                <div style="
                    padding:2px 4px;
                    display:flex;
                    flex-direction:column;
                    justify-content:flex-start;
                ">

                    <div>
                        <strong>Role Specific to the Project:</strong>
                    </div>

                    <ol style="
                        margin-top:2px;
                        padding-left:18px;
                        font-size:12px;
                    ">
                        @forelse($proposal->roles as $r)
                            <li style="margin-bottom:4px;">
                                {{ $r->role_name }}
                            </li>
                        @empty
                            <li>—</li>
                        @endforelse
                    </ol>

                </div>

            </div>

        </div>


        @php
            use Illuminate\Support\Str;

            // ================= SOURCES =================
            $sources = [
                'Finance Office',
                'PTA',
                'OSA-SACDEV',
                'Counterpart',
                'Solicitation',
                'Ticket-Selling',
                'Others',
            ];

            $existingFunds = $proposal?->fundSources
                ? $proposal->fundSources->pluck('amount', 'source_name')->toArray()
                : [];

            $total = array_sum($existingFunds);

            // ================= AUDIENCE =================
            $aud = $proposal->audience_type ?? null;

            $xuSubs = isset($proposal->xu_subtypes)
                ? explode(', ', $proposal->xu_subtypes)
                : [];
        @endphp


        <div class="row" id="row-14">

            <div style="
                display:grid;
                grid-template-rows: auto 1fr;
                height:100%;
                padding:2px 1px;
            ">

                {{-- ROW 1: TITLE --}}
                <div>
                    <strong>Proposed Budget:</strong>

                    <span style="color:#2f6fb3; font-style: italic;  font-size:11px;">
                        (Total Amount)
                    </span>
                </div>


                {{-- ROW 2: VALUE --}}
                <div style="
                    margin-top:4px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:16px;
                    font-weight:700;
                    padding:6px 0;
                ">

                    Php {{ number_format($total, 2) }}

                </div>

            </div>

        </div>

        <div class="row" id="row-15">

            <div style="
                display:flex;
                flex-direction:column;
                justify-content:space-between;
                height:100%;
                padding:2px 1px;
            ">

                {{-- TITLE --}}
                <div>
                    <strong>Sources of Funds:</strong>

                    <span style="
                        font-size:11px;
                        margin-left:4px;
                    ">
                        (Breakdown) Please specify the total amount for each applicable category.
                    </span>
                </div>


                {{-- CONTENT --}}
                <div style="
                    display:grid;
                    grid-template-columns: 1fr 1fr 1fr;
                    margin-top:4px;
                    font-size:12px;
                    padding:1px 15px;
                ">

                    {{-- COLUMN 1 --}}
                    <div>
                        @foreach(['Finance Office','PTA','OSA-SACDEV','Counterpart'] as $source)

                            @php $amount = $existingFunds[$source] ?? null; @endphp

                            <div style="margin-bottom:4px;">
                                {{ $amount !== null ? '☑' : '☐' }} {{ $source }}
                                @if($amount !== null)
                                    — Php {{ number_format($amount, 2) }}
                                @endif
                            </div>

                        @endforeach
                    </div>


                    {{-- COLUMN 2 --}}
                    <div>
                        @foreach(['Solicitation','Ticket-Selling','Others'] as $source)

                            @php $amount = $existingFunds[$source] ?? null; @endphp

                            <div style="margin-bottom:4px;">
                                {{ $amount !== null ? '☑' : '☐' }} {{ $source }}
                                @if($amount !== null)
                                    — Php {{ number_format($amount, 2) }}
                                @endif
                            </div>

                        @endforeach
                    </div>


                    {{-- COLUMN 3 --}}
                    <div>

                        <div style="
                            font-size:11px;
                            margin-bottom:4px;
                        ">
                            <strong>If with counterpart, how much are you collecting from each participant?</strong>
                        </div>

                        @php
                            $counterpartAmount = $existingFunds['Counterpart'] ?? null;
                        @endphp

                        <div style="font-size:12px;">
                           Php {{$proposal->budgetDocument->budgetproposalData->counterpart_amount_per_pax}} 
                        </div>

                    </div>

                </div>

            </div>

        </div>



        <div class="row" id="row-16">

            <div style="
                display:grid;
                grid-template-columns: 1fr 1fr 1fr 1.2fr;
                height:100%;
                padding:2px 1px;
            ">

                {{-- LEFT BLOCK (COL 1-3) --}}
                <div style="
                    grid-column: span 3;
                    padding:2px 8px;
                    border-right:1px solid #2f6fb3;
                    display:grid;
                    grid-template-columns: 1fr 1fr 1fr;
                ">

                    {{-- COL 1 --}}
                    <div style="padding-right:8px;">

                        <div>
                            <strong>Target Audience/Participants/Beneficiaries:</strong>
                            <span style="font-size:11px;">
                                (Please tick all applicable.)
                            </span>
                        </div>

                        <div style="margin-top:4px; font-size:12px;">

                            {{ $aud === 'xu_community' ? '☑' : '☐' }} XU Community

                            <div style="margin-left:15px; margin-top:2px;">
                                {{ in_array('Officers', $xuSubs) ? '☑' : '☐' }} Officers<br>
                                {{ in_array('Org Members', $xuSubs) ? '☑' : '☐' }} Org Members<br>
                                {{ in_array('Non-Org Members', $xuSubs) ? '☑' : '☐' }} Non-Org Members<br>
                                {{ in_array('Faculty/Staff', $xuSubs) ? '☑' : '☐' }} Faculty/Staff
                            </div>

                        </div>

                    </div>


                    {{-- COL 2 --}}
                    <div style="padding-right:8px; font-size:12px;">

                        <div style="margin-top:18px;">
                            {{ $aud === 'non_xu_community' ? '☑' : '☐' }} Non-XU Community
                        </div>

                        <div style="margin-top:4px; font-size:11px;">
                            Please specify below.
                        </div>

                    </div>


                    {{-- COL 3 --}}
                    <div style="font-size:12px;">

                        <div style="margin-top:18px;">
                            {{ $aud === 'beneficiaries' ? '☑' : '☐' }} Beneficiaries
                        </div>

                        <div style="margin-top:4px; font-size:11px;">
                            Please specify below. (If any)
                        </div>

                        <div style="margin-top:4px;">
                            {{ $proposal->audience_details ?? '—' }}
                        </div>

                    </div>

                </div>


               
            {{-- RIGHT BLOCK (COL 4) --}}
            <div style="
                padding:2px 8px;
                display:flex;
                flex-direction:column;
                justify-content:flex-start;
            ">

                <div>
                    <strong>Expected Number of Audience/Participants:</strong>
                </div>

                <div style="
                    margin-top:6px;
                    font-size:12px;
                ">

                    <div style="margin-bottom:6px;">
                        {{ $proposal->expected_xu_participants ? '☑' : '☐' }} XU Community —
                        <span style="
                            margin-left:6px;
                            font-weight:600;
                        ">
                            {{ $proposal->expected_xu_participants ?? '—' }}
                        </span>
                    </div>

                    <div>
                        {{ $proposal->expected_non_xu_participants ? '☑' : '☐' }} Non-XU Community —
                        <span style="
                            margin-left:6px;
                            font-weight:600;
                        ">
                            {{ $proposal->expected_non_xu_participants ?? '—' }}
                        </span>
                    </div>

                </div>

            </div>

            </div>

        </div>

        @php
            use Carbon\Carbon;

            $hasGuests = $proposal->has_guest_speakers ?? false;

            $guests = $proposal->guests ?? collect();
            $plans  = $proposal->planOfActions ?? collect();
        @endphp

        <div class="row" id="row-17">

            <div style="
                display:grid;
                grid-template-columns: 1fr 1fr;
                height:100%;
            ">

                {{-- LEFT --}}
                <div style="
                    padding:2px 8px;
                    border-right:1px solid #2f6fb3;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                ">

                    <div>
                        <strong>Participation of Guests/Speakers/Dignitaries Required?</strong>
                    </div>

                    <div style="
                        margin-top:6px;
                        font-size:12px;
                    ">
                        {{ $hasGuests ? '☑' : '☐' }} Yes
                        &nbsp;&nbsp;&nbsp;
                        {{ !$hasGuests ? '☑' : '☐' }} No
                    </div>

                </div>


                {{-- RIGHT --}}
                <div style="
                    padding:2px 8px;
                    display:flex;
                    flex-direction:column;
                    justify-content:flex-start;
                ">

                    <div>
                        <strong>If yes, please list down guests from inside and outside XU:</strong><br>
                        <span style="font-size:11px;">
                            Full Name, Affiliation, and Designation
                        </span>
                    </div>

                    <div style="
                        margin-top:6px;
                        font-size:12px;
                    ">

                        @forelse($guests as $g)
                            <div style="margin-bottom:4px;">
                                • {{ $g->full_name }}
                                @if($g->affiliation) — {{ $g->affiliation }} @endif
                                @if($g->designation) ({{ $g->designation }}) @endif
                            </div>
                        @empty
                            —
                        @endforelse

                    </div>

                </div>

            </div>

        </div>


        
        <div class="row" id="row-18" style="border-top:1px solid #2f6fb3; border-bottom:1px solid transparent;">

            <div style="
                text-align:center;
                font-weight:700;
                font-size:13px;
                padding-top:4px;
            ">
                PLAN OF ACTION
            </div>

            <div style="
                font-size:10px;
                padding:4px 8px 5px 8px;
                text-align:justify;
            ">
                Note: For on-campus activities, please state the program flow. For off-campus activities organized by your org,
                please state the itinerary first and then the program flow. For participation in off-campus activities organized
                by other entities, please state the itinerary and attach the invitation and program flow from the organizers.
            </div>

        </div>


        <div class="row" id="row-19" style="margin-bottom: 60px">

            <table style="
                width:100%;
                border-collapse:collapse;
                font-size:12px;
            ">

                {{-- HEADER --}}
                <tr style="
                    background:#2f6fb3;
                    color:#fff;
                    text-align:center;
                    font-weight:600;
                    margin-bottom: 20px
                ">
                    <td style="padding:6px; border-bottom:1px solid #2f6fb3;">Date</td>
                    <td style="padding:6px; border-bottom:1px solid #2f6fb3;">Time</td>
                    <td style="padding:6px; border-bottom:1px solid #2f6fb3;">Activity/Particulars</td>
                    <td style="padding:6px; border-bottom:1px solid #2f6fb3;">Venue</td>
                </tr>

                {{-- DATA --}}
                @forelse($plans as $p)
                    <tr>

                        <td style="
                            padding:6px;
                            text-align:center;
                            border-bottom:1px solid #ddd;
                        ">
                            {{ $p->date ? \Carbon\Carbon::parse($p->date)->format('M d, Y') : '—' }}
                        </td>

                        <td style="
                            padding:6px;
                            text-align:center;
                            border-bottom:1px solid #ddd;
                        ">
                            {{ $p->time ? \Carbon\Carbon::parse($p->time)->format('h:i A') : '—' }}
                        </td>

                        <td style="
                            padding:6px;
                            border-bottom:1px solid #ddd;
                            text-align:center;
                        ">
                            {{ $p->activity ?? '—' }}
                        </td>

                        <td style="
                            padding:6px;
                            border-bottom:1px solid #ddd;
                            text-align:center;
                        ">
                            {{ $p->venue ?? '—' }}
                        </td>

                    </tr>
                @empty

                    {{-- EMPTY ROWS (PRINT LOOK) --}}
                    @for($i = 0; $i < 3; $i++)
                        <tr>
                            <td style="padding:10px; border-bottom:1px solid #ddd;">&nbsp;</td>
                            <td style="border-bottom:1px solid #ddd;"></td>
                            <td style="border-bottom:1px solid #ddd;"></td>
                            <td style="border-bottom:1px solid #ddd;"></td>
                        </tr>
                    @endfor

                @endforelse

            </table>

        </div>

            {{-- SIGNATURES --}}
    <div class="no-break">
        @include('admin.projects.documents.project-proposal.print.partials.signatures')
    </div>





    </div>

</div>



<script>
function printDocument() {
    window.print();
}
</script>

</body>
</html>