<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
<title>Documentation Report</title>

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

@php

    $ppDocu = $project->documents->where('form_type_id', 1)->first();
    $drDocu = $project->documents->where('form_type_id', 11)->first();

    $proposal = $ppDocu?->proposalData;
    $docuData = $drDocu?->documentationReport;

    $description = $docuData?->description;

    $startDate = $docuData?->implementation_start_date;
    $endDate = $docuData?->implementation_end_date;

    $startTime = $docuData?->implementation_start_time;
    $endTime = $docuData?->implementation_end_time;

    $onCampusVenue = $docuData?->on_campus_venue;
    $offCampusVenue = $docuData?->off_campus_venue;

    $objMet = $docuData?->objectives_met;
    $contribute = $docuData?->contributing_factors;

    $expected = $docuData?->expected_participants;
    $actual = $docuData?->actual_participants;

    $rating = $docuData?->implementation_rating;

    $preImplementation = $docuData?->pre_implementation_stage;
    $implementation = $docuData?->implementation_stage;
    $postImplementation = $docuData?->post_implementation_stage;

    $recommendations = $docuData?->recommendations;

    $proposedBudget = $docuData?->proposed_budget;
    $actualBudget = $docuData?->actual_budget;
    $balance = $docuData?->balance;

    $photoPath = $docuData?->photo_document_path;

    $objectives = $docuData?->objectives ?? collect();
    $indicators = $docuData?->indicators ?? collect();
    $partners = $docuData?->partners ?? collect();
    $attendees = $docuData?->attendees ?? collect();

@endphp

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
            Form A2 <span style="font-weight:normal;">(2023 Edition)</span>
        </div>

    </div>

    
    <div style="text-align:center; padding:10px 0; margin-top:50px;">
        <div style="font-size:18px; font-weight:bold; letter-spacing:0.5px;">
            DOCUMENTATION REPORT
        </div>
        <div style="font-size:11px;">
            (Please accomplish 3 copies)
        </div>
    </div>

    {{-- ACTUAL FORM --}}
    <div class="form-container" style="margin-bottom: 20px">

        <div style="background:#2f6fb3; color:#fff; text-align:center; font-weight:bold; padding:4px;">
            PROJECT DEFINITION
        </div>

        <div class="row" id="row-2" style="background:#eaf2fb;">
            <div style="font-size:12px; margin-bottom:4px; background:#eaf2fb;">
                <strong>Name/ Title of Project:</strong>
            </div>

            <div style="text-align:center; font-weight:bold; font-size:15px; margin-bottom:8px; background:#eaf2fb;">
                {{ $project->title }}
            </div>
        </div>

        {{--imp dates--}}
        <div class="row" id="row-3" style="padding:0px">

            <div class="inner-grid">

                {{-- ROW 1 --}}
                <div style="padding-left: 5px" >
                    <strong>Implementation Date(s):</strong>
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
                    justify-content:space-between; background:#eaf2fb; min-height:30px
                ">
                    <span style="color:#2f6fb3; font-style: italic;  font-size:11px;">
                        Starting Date:
                    </span>

                    <div style="text-align:center; font-size:12px; font-weight:600; margin-bottom:4px;">
                        {{$startDate}}
                    </div>
                </div>


                <div style="
                    padding:1px 8px;
                    border-right:1px solid #2f6fb3;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between; background:#eaf2fb;
                ">
                    <span style="color:#2f6fb3; font-style: italic;  font-size:11px;">
                        End Date:
                    </span>

                    <div style="text-align:center; font-size:12px; font-weight:600; margin-bottom:4px;">
                        {{$endDate}}
                    </div>
                </div>


                <div style="
                    padding:10px 8px;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between; background:#eaf2fb;
                ">


                    <div style="text-align:center; font-size:12px; font-weight:600;">
                        {{ $startTime && $endTime 
                            ? \Carbon\Carbon::parse($startTime)->format('g:i A') . ' - ' . \Carbon\Carbon::parse($endTime)->format('g:i A') 
                            : '—' 
                        }}
                    </div>
                </div>

            </div>

        </div>

        {{--imp venues--}}
        <div class="row" id="row-4" style="padding:0px">

            <div class="inner-grid">

                {{-- ROW 1 --}}
                <div style="padding-left: 5px">
                    <strong>Venue:</strong>
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
                    justify-content:space-between; background:#eaf2fb;
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
                    justify-content:space-between;background:#eaf2fb;
                    
                ">
                    <span style="color:#2f6fb3; font-style: italic;  font-size:11px;">
                        On Campus: 
                    </span>
                        
                    <div style="text-align:center; font-size:12px; font-weight:600; margin-bottom:4px">
                        {{$onCampusVenue }}
                    </div>
                </div>


                <div style="
                    padding:0px 8px;
                    display:flex;
                    flex-direction:column;
                    justify-content:space-between;
                    background:#eaf2fb;
                ">
                    <span style="color:#2f6fb3; font-style: italic;  font-size:11px; ">
                        Off Campus: 
                    </span>


                    <div style="text-align:center; font-size:12px; font-weight:600;  ">
                       {{$offCampusVenue }}
                    </div>
                </div>

            </div>

        </div>


        <div class="row" id="row-5 " style="padding:0px">

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
                        <strong style="padding-left: 5px">Nature of Engagement:</strong>
                    </div>

                    <div style="
                        margin-top:4px;
                        font-size:12px;
                        color:#000;
                        padding-left: 20px; background:#eaf2fb;
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
                    justify-content:space-between;  padding:0px
                ">

                    <div>
                        If participant, state the main organizer below:
                    </div>

                    <div style="
                        text-align:center;
                        font-size:12px;
                        font-weight:600;
                        margin-bottom:3px; background:#eaf2fb;
                    ">
                        {{ $proposal->main_organizer ?? '—' }}
                    </div>

                </div>

            </div>

        </div>


        <div class="row" id="row-7" style="padding: 0px">

            @php
                $af = $proposal->area_focus
                    ? explode(', ', $proposal->area_focus)
                    : [];

    
            @endphp

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
                
            ">

                {{-- TITLE --}}
                <div>
                    <strong style="margin-left: 5px">Area Focus:</strong>
                </div>

                {{-- OPTIONS --}}
                <div style="
                    display:grid;
                    grid-template-columns: 1fr 1fr 1fr;
                    margin-top:2px;
                    font-size:12px;
                    padding:1px 15px; background:#eaf2fb;
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



        <div class="row" id="row-7" style="padding:0px;  ">

            <div style="
                display:grid;
                grid-template-rows: auto 1fr;
                height:100%;
                padding:2px 1px;
            ">

                {{-- ROW 1: TITLE --}}
                <div>
                    <strong style="margin-left: 5px">
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
                    display:flex;
                    align-items:flex-start;
                    background:#eaf2fb;
                    min-height: 40px;
                    padding: 10px;
                    border-bottom: 1px solid #2f6fb3
                ">

                    {{ $proposal->description ?? '—' }}

                </div>

            </div>

        <div class="row" id="row-12" style="padding: 0px">




        <div style="
            display:grid;
            grid-template-columns: 1fr 1fr;
            height:100%;
            padding:2px 1px; background:#eaf2fb;
        ">

            <div style="
                padding:2px 4px;
                border-right:1px solid #2f6fb3;
                display:flex;
                flex-direction:column;
                justify-content:flex-start;
            ">

                <div>
                    <strong>Who attended your project?</strong>

                    <span style="color:#000; font-style: italic;  font-size:11px;">
                         (Guests and Dignitaries) 
                    </span>
                </div>

                <ol style="
                    margin-top:2px;
                    padding-left:18px;
                    font-size:12px;
                ">
                    @forelse($attendees as $p)
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
                    <strong>Partners/Sponsors:</strong>
                </div>

                <ol style="
                    margin-top:2px;
                    padding-left:18px;
                    font-size:12px;
                ">
                    @forelse($partners  as $r)
                        <li style="margin-bottom:4px;">
                            {{ $r->name }}
                        </li>
                    @empty
                        <li>—</li>
                    @endforelse
                </ol>

            </div>

        </div>

      



    </div>





    


    


    <div class="" style="margin-top:20px">

        <div style="background:#2f6fb3; color:#fff; text-align:center; font-weight:bold; padding:4px;">
            EVALUATION
        </div>

        <div style="
            display:grid;
            grid-template-columns: 1fr 1fr;
            height:100%;
            padding:2px 1px; ;
            border-bottom: 1px solid #2f6fb3;
        ">

            <div style="
                border-right:1px solid #2f6fb3;
                display:flex;
                flex-direction:column;
                justify-content:flex-start;
            ">

                <div>
                    <strong style="margin-left:5px">Objectives:</strong>

                    <span style="color:#000; font-style: italic;  font-size:11px;">
                         Please be consistent with the project proposal
                    </span>
                </div>

                <ol style="
                    margin-top:2px;
                    padding-left:18px;
                    font-size:12px; background:#eaf2fb
                ">
                    @forelse($objectives as $p)
                        <li style="margin-bottom:4px;">
                            {{ $p->objective }}
                        </li>
                    @empty
                        <li>—</li>
                    @endforelse
                </ol>

            </div>


          
            <div style="
                display:flex;
                flex-direction:column;
                justify-content:flex-start;
            ">

                <div>
                    <strong style="margin-left:5px">Targets/Success Indicators:</strong>
                </div>

                <ol style="
                    margin-top:2px;
                    padding-left:18px;
                    font-size:12px; background:#eaf2fb
                ">
                    @forelse($indicators  as $r)
                        <li style="margin-bottom:4px;">
                            {{ $r->indicator }}
                        </li>
                    @empty
                        <li>—</li>
                    @endforelse
                </ol>

            </div>

        </div>



        <div class="row" style="padding:0px; min-height:1px;">

            <div style="
                display:grid;
                grid-template-columns: 1fr;
                height:100%;
            ">

                {{-- ROW 1: OBJECTIVES MET --}}
                <div style="padding:4px 8px;">
                    <strong>Were your objectives met?</strong>

                    <span style="margin-left:20px;">
                        {{ $objMet === 1 ? '☑' : '☐' }} Yes
                    </span>

                    <span style="margin-left:15px;">
                        {{ $objMet === 0 ? '☑' : '☐' }} No
                    </span>
                </div>

            </div>

        </div>


        <div class="row" style="padding:0px 8px; min-height:1px; border-bottom:0px">
            <strong>
                What contributed to the attainment (or nonattainment) of your objectives?
            </strong>
        </div>


        <div class="row" style="padding:0px">

            <div style="
                min-height:50px;
                background:#eaf2fb;
                padding:10px;
                font-size:12px;
                display:flex;
                align-items:flex-start;
            ">
                {{ $contribute ?? '—' }}
            </div>

        </div>



        <div class="row" style="padding:0px">

            <div style="
                display:grid;
                grid-template-columns: 1fr 1fr;
                height:100%;
            ">

                {{-- COLUMN 1 --}}
                <div style="
                    border-right:1px solid #2f6fb3;
                    display:flex;
                    flex-direction:column;
                ">

                    {{-- LABEL --}}
                    <div style="padding:0px 8px; text-align:center">
                        <strong>Expected Number of Audience/Participants</strong>
                    </div>

                    {{-- VALUE --}}
                    <div style="
                        background:#eaf2fb;
                        padding:2px;
                        font-size:12px;
                        text-align:center;
                        font-weight:600;
                    ">
                        {{ $expected ?? '—' }}
                    </div>

                </div>


                {{-- COLUMN 2 --}}
                <div style="
                    display:flex;
                    flex-direction:column;
                ">

                    {{-- LABEL --}}
                    <div style="padding:0px 8px; text-align:center">
                        <strong>Actual Number of Audience/Participants</strong>
                    </div>

                    {{-- VALUE --}}
                    <div style="
                        background:#eaf2fb;
                        padding:2px;
                        font-size:12px;
                        text-align:center;
                        font-weight:600;
                    ">
                        {{ $actual ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

        <div class="row" style="padding:0px; border-bottom:1px solid #2f6fb3 ">

            <div style="
                display:grid;
                grid-template-columns: 1fr 1fr;
                height:100%;
            ">

                {{-- COLUMN 1: LABEL --}}
                <div style="
                    border-right:1px solid #2f6fb3;
                    display:flex;
                    align-items:center;
                    padding:4px 8px;
                ">
                    <strong>
                        Rate how well you implemented your project.
                    </strong>

                    <span style="margin-left:6px; font-size:11px; font-style:italic;">
                        (5 as the highest)
                    </span>
                </div>


                {{-- COLUMN 2: CHECKBOXES --}}
                <div style="
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    gap:18px;
                    font-size:12px;
                    padding:0px 0;
                ">

                    <span>{{ $rating == 5 ? '☑' : '☐' }} 5</span>
                    <span>{{ $rating == 4 ? '☑' : '☐' }} 4</span>
                    <span>{{ $rating == 3 ? '☑' : '☐' }} 3</span>
                    <span>{{ $rating == 2 ? '☑' : '☐' }} 2</span>
                    <span>{{ $rating == 1 ? '☑' : '☐' }} 1</span>

                </div>

            </div>

        </div>





        </div>





            {{-- SIGNATURES --}}
    <div class="no-break">



    

    
    <div style="margin-top:20px;     page-break-inside: avoid;
    break-inside: avoid;">
        


        <div style="background:#fff; color:#2f6fb3; text-align:center; font-weight:bold; padding:4px;">
            PROJECT IMPLEMENTATION
        </div>


        <div style="background:#2f6fb3; color:#fff; text-align:left; padding:4px; font-size:10px">
            I. PRE-IMPLEMENTATION STAGE
        </div>

        <div class="row" style="padding:0px">

            {{-- ROW 1: INSTRUCTION --}}
            <div style="padding:0px 8px; border-bottom: 1px solid #2f6fb3 ">
                <span style="font-style: italic; font-size:11px;">
                    State the highlights of your preparations for the project 
                    (i.e. meetings, strategies, committees created, etc)
                </span>
            </div>

            {{-- ROW 2: CONTENT --}}
            <div style="
                background:#eaf2fb;
                padding:8px;
                font-size:12px;
                min-height:40px;
                display:flex;
                align-items:flex-start;
            ">
                {{ $preImplementation ?? '—' }}
            </div>

        </div>


        <div style="background:#2f6fb3; color:#fff; text-align:left; padding:4px; font-size:10px">
            II. IMPLEMENTATION STAGE 
        </div>

        <div class="row" style="padding:0px">

            {{-- ROW 1: INSTRUCTION --}}
            <div style="padding:0px 8px; border-bottom: 1px solid #2f6fb3 ">
                <span style="font-style: italic; font-size:11px;">
                    State the flow of your project/activity or your general observations while conducting it. 
                </span>
            </div>

            {{-- ROW 2: CONTENT --}}
            <div style="
                background:#eaf2fb;
                padding:8px;
                font-size:12px;
                min-height:40px;
                display:flex;
                align-items:flex-start;
            ">
                {{ $implementation  ?? '—' }}
            </div>

        </div>


        <div style="background:#2f6fb3; color:#fff; text-align:left; padding:4px; font-size:10px">
            III. POST IMPLEMENTATION STAGE 
        </div>

        <div class="row" style="padding:0px">

            {{-- ROW 1: INSTRUCTION --}}
            <div style="padding:0px 8px; border-bottom: 1px solid #2f6fb3 ">
                <span style="font-style: italic; font-size:9px;">
                    State the results of your evaluation here. Evaluation could be qualitative, quantitative or both. You may also highlight the strengths/weaknesses/opportunities/threats (SWOT) that you encountered during the project.
                </span>
            </div>

            {{-- ROW 2: CONTENT --}}
            <div style="
                background:#eaf2fb;
                padding:8px;
                font-size:12px;
                min-height:40px;
                display:flex;
                align-items:flex-start;
            ">
                {{ $postImplementation  ?? '—' }}
            </div>

        </div>


        <div style="background:#2f6fb3; color:#fff; text-align:left; padding:4px; font-size:10px">
            IV. RECOMMENDATIONS 
        </div>

        <div class="row" style="padding:0px">

            {{-- ROW 1: INSTRUCTION --}}
            <div style="padding:0px 8px; border-bottom: 1px solid #2f6fb3 ">
                <span style="font-style: italic; font-size:11px;">
                    State your recommendations for the improvement of the project. 
                </span>
            </div>

            {{-- ROW 2: CONTENT --}}
            <div style="
                background:#eaf2fb;
                padding:8px;
                font-size:12px;
                min-height:40px;
                display:flex;
                align-items:flex-start;
            ">
                {{ $recommendations  ?? '—' }}
            </div>

        </div>

       


    <div class="">
  
  


   



    

</div>


    
<div class="" style="margin-top:20px; border-bottom: 1px solid #2f6fb3">

    <div style="background:#fff; color:#2f6fb3; text-align:center; font-weight:bold; padding:4px;">
        FINANCIAL REPORT
    </div>

    <div style="background:#2f6fb3; color:#fff; text-align:center;  padding:4px; font-size: 11px">
        EXPENDITURES
    </div>

    <div class="row" style="padding:0px">

        <div style="
            display:grid;
            grid-template-columns: 1fr 1fr 1fr;
        ">

            {{-- ROW 1: LABELS --}}
            <div style="padding:0px 6px; border-right:1px solid #2f6fb3;">
                <strong>Proposed Budget:</strong>
                <span style="font-size:9px; font-style:italic;">
                    (Based on the Project proposal)
                </span>
            </div>

            <div style="padding:0px 6px; border-right:1px solid #2f6fb3;">
                <strong>Actual Budget Spent:</strong>
                <span style="font-size:9px; font-style:italic;">
                    (Details on the Liquidation Report)
                </span>
            </div>

            <div style="padding:0px 6px;">
                <strong>Balance:</strong>
                <span style="font-size:9px; font-style:italic;">
                    (If any)
                </span>
            </div>


            {{-- ROW 2: VALUES --}}
            <div style="
                padding:6px;
                border-top:1px solid #2f6fb3;
                border-right:1px solid #2f6fb3;
                text-align:center;
                font-size:12px;
                font-weight:600;
            ">
                {{ $proposedBudget !== null ? '₱ ' . number_format((float)$proposedBudget, 2) : '—' }}
            </div>

            <div style="
                padding:6px;
                border-top:1px solid #2f6fb3;
                border-right:1px solid #2f6fb3;
                text-align:center;
                font-size:12px;
                font-weight:600;
            ">
                {{ $actualBudget !== null ? '₱ ' . number_format((float)$actualBudget, 2) : '—' }}
            </div>

            <div style="
                padding:6px;
                border-top:1px solid #2f6fb3;
                text-align:center;
                font-size:12px;
                font-weight:600;
            ">
                {{ $balance !== null ? '₱ ' . number_format((float)$balance, 2) : '—' }}
            </div>

        </div>

    </div>











    </div>





        
<div class="no-break">






<div style="margin-top:40px">

    <div style="background:#2f6fb3; color:#fff; text-align:center; padding:4px; font-size:11px">
        SIGNATORIES 
    </div>

    @php
        $sigs = $document->signatures->keyBy('role');

        function sig($role, $sigs) {
            return $sigs[$role] ?? null;
        }

        function approvalLine($role, $sigs) {
            $s = $sigs[$role] ?? null;

            if (!$s || $s->status !== 'signed') {
                return '<div style="font-size:10px; color:#000;">Pending Approval</div>';
            }

            return '
                <div style="font-size:10px; color:#000; font-weight:600;">
                    ✔ Approved In System <br> 
                    '.$s->signed_at?->format('M d, Y h:i A').'
                </div>
            ';
        }
    @endphp


    {{-- ROW 1: HEADER --}}
    <div class="row" style="padding:0px; background:#eaf2fb; border-bottom: 0px">

        <div style="
            display:grid;
            grid-template-columns: 1fr 2fr;
        ">

            <div style="padding:6px 8px;">
                Prepared by:
            </div>

            <div style="padding:6px 8px; text-align:center;">
                Noted by:
            </div>

        </div>

    </div>


    {{-- ROW 2: SIGNATORIES --}}
    <div class="row" style="padding:0px; background:#eaf2fb;">

        <div style="
            display:grid;
            grid-template-columns:1fr 1fr 1fr;
        ">

            {{-- PROJECT HEAD --}}
            <div style="
                padding:12px 10px;
                border-right:1px solid #eaf2fb;
                text-align:left;
            ">

                {!! approvalLine('project_head', $sigs) !!}

                <div style="
                    margin-top:18px;
                    border-bottom:1px solid #000;
                    padding-bottom:2px;
                ">
                    {{ sig('project_head', $sigs)?->user?->name ?? '—' }}
                </div>



                <div style="font-size:11px;">
                    Project Head
                </div>
                @php
                    $officer = sig('project_head', $sigs)?->user->officerEntries()->first() ?? null;
                    //dd(sig('project_head', $sigs)?->user->officerEntries()->first()->mobile_number)
                @endphp

                <div style="margin-top:10px; font-size:11px;">
                    Mobile Number: {{ $officer->mobile_number ?? '—' }}<br>
                    Email Address: {{ $officer->email ?? '—' }}
                </div>


            </div>


            {{-- PRESIDENT --}}
            <div style="
                padding:12px 10px;
                border-right:1px solid #eaf2fb;
                text-align:left;
            ">

                {!! approvalLine('president', $sigs) !!}

                <div style="
                    margin-top:18px;
                    border-bottom:1px solid #000;
                    padding-bottom:2px;
                ">
                    {{ sig('president', $sigs)?->user?->name ?? '—' }}
                </div>



                <div style="font-size:11px;">
                    President
                </div>

            </div>


            {{-- MODERATOR --}}
            <div style="
                padding:12px 10px;
                text-align:left;
            ">

                {!! approvalLine('moderator', $sigs) !!}

                <div style="
                    margin-top:18px;
                    border-bottom:1px solid #000;
                    padding-bottom:2px;
                ">
                    {{ sig('moderator', $sigs)?->user?->name ?? '—' }}
                </div>



                <div style="font-size:11px;">
                    Moderator
                </div>

            </div>

        </div>

    </div>

</div>




<div class="form-container" style="margin-top:30px">

    <div style="background:#2f6fb3; color:#fff; text-align:center; padding:4px; font-size:11px">
        PHOTO DOCUMENTATION
    </div>

    <div class="row" style="padding:10px; text-align:center;">

        @if($photoPath)
            <a href="{{ asset('storage/' . $photoPath) }}" target="_blank"
               style="color:#2f6fb3; text-decoration:underline; font-size:12px;">
                View Attached Photo Documentation (PDF)
                
            </a>
        @else
            <span style="font-size:12px;">No photo documentation uploaded.</span>
        @endif
        <div style="padding:0px 6px; border-right:1px solid #2f6fb3;">

            <span style="font-size:9px; font-style:italic;">
                (Print separately)
            </span>
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