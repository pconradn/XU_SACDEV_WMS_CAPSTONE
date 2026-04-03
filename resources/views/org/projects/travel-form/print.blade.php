<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Travel Form</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .page {
            width: 100%;
            max-width: 700px;
            margin: auto;
        }

        @media print {
            body {
                margin: 0;
            }

            .print-btn {
                display: none;
            }

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
    <div style="
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        border-bottom:1px transparent #2f6fb3;
        padding-bottom:10px;
    ">

        {{-- LEFT --}}
        <div style="display:flex; gap:10px;">

            {{-- LOGO --}}
            <div style="width:45px; height:45px; border:1px solid #000;"></div>

            {{-- TEXT --}}
            <div style="font-size:11px; line-height:1.4;">
                <div style="font-weight:700; font-size:12px;">
                    STUDENT ACTIVITIES AND LEADERSHIP DEVELOPMENT
                </div>
                Office of Student Affairs, Xavier University – Ateneo de Cagayan<br>
                Rm 204, 2F Magis Student Complex (Tel) 853-9800 local 9245
            </div>

        </div>

        {{-- RIGHT --}}
        <div style="
            background:#2f6fb3;
            color:#fff;
            font-weight:700;
            font-size:11px;
            padding:6px 12px;
        ">
            Form A12.1 (2017)
        </div>

    </div>

    {{-- TITLE --}}
    <div style="text-align:center; margin-top:15px;">
        <div style="font-size:16px; font-weight:700;">
            STUDENT TRAVEL AGREEMENT/
        </div>
        <div style="font-size:16px; font-weight:700;">
            PARENT’S CONSENT FORM
        </div>
    </div>

    {{-- FORM --}}
    <div style="margin-top:25px; font-size:12px;">


        <div class="row" style="padding:6px 8px; border-bottom: 2px transparent;">

            <div style="font-size:11px; font-weight:700;">
                TO THE STUDENT ORGANIZATION:
            </div>

        </div>

        <div class="row" style="padding:6px 8px;">

            <div style="
                font-size:11px;
                line-height:1.4;
                text-align:justify;
                text-indent:20px;
            ">
                Please input all necessary details of your off-campus activity (as indicated in your approved project proposal) in the table below before disseminating this form to your participants. Pre-encoded information only serves as sample/guide.
            </div>

        </div>

        <div class="row" style="padding:6px 8px; border-bottom: 2px transparent;">

            <div style="font-size:11px; font-weight:700;">
                TO THE STUDENT:
            </div>

        </div>

        <div class="row" style="padding:6px 8px;">

            <div style="
                font-size:11px;
                line-height:1.4;
                text-align:justify;
                text-indent:20px;
            ">
                Please check accuracy of information provided by your student organization below. Present this form to your parent/guardian and ask them to review the information provided before signing. Submit accomplished form to your organization at least 3 days before your off-campus activity.
            </div>

        </div>


        <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent;">
            <div style="display:grid; grid-template-columns:25% 35% 15% 25%; width:100%;">

                <div style="padding:2px 6px; font-size:11px; font-weight:600; color:#2f6fb3; border-right:1px solid #2f6fb3; line-height:1.2;">
                    Name of Activity:
                </div>

                <div style="padding:2px 6px; font-size:11px; line-height:1.2;">
                    {{ $data['activity_name'] }}
                </div>

                <div style="padding:2px 6px; font-size:11px; font-weight:600; color:#2f6fb3; border-right:1px solid #2f6fb3; line-height:1.2;">
                    Organization:
                </div>

                <div style="padding:2px 6px; font-size:11px; line-height:1.2;">
                    {{ $project->organization->name }}
                </div>

            </div>
        </div>

        <div class="row" style="padding:0; min-height:unset; border-bottom:2px transparent;">
            <div style="display:grid; grid-template-columns:25% 35% 15% 25%; width:100%;">

                <div style="padding:2px 6px; font-size:11px; font-weight:600; color:#2f6fb3; border-right:1px solid #2f6fb3; line-height:1.2;">
                    Inclusive Date(s) of Activity:
                </div>

                <div style="padding:2px 6px; font-size:11px; line-height:1.2;">
                    {{ $data['inclusive_dates'] }}
                </div>

                <div style="padding:2px 6px; font-size:11px; font-weight:600; color:#2f6fb3; border-right:1px solid #2f6fb3; line-height:1.2;">
                    Venue:
                </div>

                <div style="padding:2px 6px; font-size:11px; line-height:1.2;">
                    {{ $data['venue'] }}
                </div>

            </div>
        </div>

        <div class="row" style="padding:0; min-height:unset;">
            <div style="display:grid; grid-template-columns:25% 35% 15% 25%; width:100%;">

                <div style="padding:2px 6px; font-size:11px; font-weight:600; color:#2f6fb3; border-right:1px solid #2f6fb3; line-height:1.2;">
                    Accommodation (for Stay-in):
                </div>

                <div style="padding:2px 6px; font-size:11px; line-height:1.2;">
                    {{ $data['accommodation'] }}
                </div>

                <div style="padding:2px 6px; font-size:11px; font-weight:600; color:#2f6fb3; border-right:1px solid #2f6fb3; line-height:1.2;">
                    Address:
                </div>

                <div style="padding:2px 6px; font-size:11px; line-height:1.2;">
                    {{ $data['address'] }}
                </div>

            </div>
        </div>

        <div class="row" style="padding:0; min-height:unset;">

            <div style="display:grid; grid-template-columns:25% 1fr 5% 10% 15% 25%; width:100%;">

                {{-- ROW 1 --}}

                <div style="padding:2px 6px; font-size:11px; color:#2f6fb3; border-right:1px solid #2f6fb3; line-height:1.2;">
                    Date of Departure:
                </div>

                <div style="padding:2px 6px; font-size:11px; border-right:1px transparent #2f6fb3;">
                    {{ $data['departure_date_formatted'] }}
                </div>

                <div style="padding:2px 6px; font-size:11px; border-right:1px solid #2f6fb3; color:#2f6fb3;">
                    Time:
                </div>

                <div style="padding:2px 6px; font-size:11px; border-right:1px transparent #2f6fb3;">
                    {{ $data['departure_time'] }}
                </div>

                {{-- COL 5 (STACKED) --}}
                <div style="padding:2px 6px; font-size:11px; color:#2f6fb3;  border-right:1px solid #2f6fb3;">
                    Mode/ Carrier:<br>
                    Plate Number:<br>
                    Flight Number:
                </div>

                {{-- COL 6 (STACKED) --}}
                <div style="padding:2px 6px; font-size:11px;">
                    {{ $data['departure_mode'] ?? 'Own or Public Transport' }}<br>
                    {{ $data['departure_plate'] ?? '' }}<br>
                    {{ $data['departure_flight'] ?? '' }}
                </div>


                {{-- ROW 2 (GAP for COL 1–4, aligns with middle of col 5–6) --}}

                <div style="height:18px; border-right:1px solid #2f6fb3;"></div>
                <div ></div>
                <div style=" border-right:1px solid #2f6fb3;"></div>
                <div></div>

                {{-- COL 5–6 CONTINUE --}}
                <div style=" border-right:1px solid #2f6fb3;" ></div>
                <div></div>


                {{-- ROW 3 --}}

                <div style="padding:2px 6px; font-size:11px; color:#2f6fb3; border-right:1px solid #2f6fb3; line-height:1.2;">
                    Expected Date of Return:
                </div>

                <div style="padding:2px 6px; font-size:11px; border-right:1px transparent #2f6fb3;">
                    {{ $data['return_date_formatted'] }}
                </div>

                <div style="padding:2px 6px; font-size:11px; border-right:1px solid #2f6fb3; color:#2f6fb3;">
                    Time:
                </div>

                <div style="padding:2px 6px; font-size:11px; border-right:1px transparent #2f6fb3;">
                    {{ $data['return_time'] }}
                </div>

                {{-- EMPTY FOR COL 5–6 --}}
                <div style="padding:2px 6px; font-size:11px; color:#2f6fb3;  border-right:1px solid #2f6fb3;">
                    Mode/ Carrier:<br>
                    Plate Number:<br>
                    Flight Number:
                </div>
                <div style="padding:2px 6px; font-size:11px; ">
                    {{ $data['return_mode'] ?? 'Own or Public Transport' }}<br>
                    {{ $data['return_plate'] ?? '' }}<br>
                    {{ $data['return_flight'] ?? '' }}
                </div>

            </div>

        </div>


        <div class="row" style="padding:6px 8px; border-bottom:2px transparent;">

            <div style="text-align:center; font-weight:700; font-size:12px; margin-bottom:4px;">
                TRAVEL AGREEMENT
            </div>

            <div style="font-size:11px; line-height:1.35; text-align:justify;">

                <div style="text-indent:20px; margin-bottom:6px;">
                    In connection with my participation in the aforementioned activity and destination, I agree with and abide by the rules and regulations set and imposed by the University for my own safety and for the welfare of everyone participating in the off-campus activity.
                </div>

                <ol style="margin:0; padding-left:18px;">
                    <li>No riding on the top load or boarding platform of the vehicle</li>
                    <li>Male and female students must be housed in separate rooms</li>
                    <li>Strictly no smoking and drinking of alcoholic beverages during the activity</li>
                    <li>No Gambling</li>
                    <li>Strict observance of the curfew imposed by my Moderator/Faculty supervisor (for stay-in/out-of-town activities)</li>
                    <li>All other provisions for security and safety of participants stated in the Implementing Guidelines for Off-Campus Activities</li>
                </ol>

                <div style="margin-top:8px; margin-bottom:4px;">
                    Further, I hereby warrant and represent that:
                </div>

                <ol style="margin:0; padding-left:18px;">
                    <li>I am voluntarily participating in the aforementioned activity;</li>
                    <li>I certify that I am in good physical health and physically able to participate in the described activity;</li>
                    <li>I acknowledge that I have discussed my travel and my participation with my parents/legal guardian who in turn gives his/her full consent of my participation in the activity;</li>
                    <li>I shall strictly follow/abide by the duly approved schedule of our trip; In the event where unavoidable circumstances occur and that changes are made in the schedule upon the discretion of my organization, I shall immediately notify my parents/guardian of these changes;</li>
                    <li>I shall not deviate from the established and approved itinerary during the entire duration of the activity; In the event where I am permitted by my parents to stay behind/proceed to another destination after the activity, I declare that the University is no longer responsible for me; Further, I acknowledge that I am solely responsible for my actions and safety once I am already left on my own; (parents must write these remarks below);</li>
                    <li>In the event where I am allowed by my parents to use/drive my own vehicle or ride in a vehicle used by a co-participant of the activity, I shall travel in convoy with the main vehicle loading all other participants; (parents must write these remarks below);</li>
                    <li>I shall respect the duly designated school official (faculty/staff member) who shall represent the University in the enforcement of the guidelines stated herein and in the implementing guidelines for off-campus activities; and</li>
                    <li>Finally, I recognize the authority of the University to impose sanction on me for any misrepresentation and for failure to abide by the rules and regulations for off-campus activities.</li>
                </ol>

                <div style="margin-top:8px; text-indent:20px; margin-bottom: 10px">
                    I have read, understood and acknowledge full acceptance and conformity with the foregoing conditions.
                </div>

            </div>

        </div>



        {{-- ROW 1 --}}
        <div class="row" style="padding:0; min-height:unset;">
            <div style="display:grid; grid-template-columns:20% 30% 20% 30%; width:100%; margin-bottom:5px">

                <div style="padding:2px 6px; font-size:11px;">Student’s Name:</div>
                <div style="padding:2px 6px; border-bottom:1px solid #000;"></div>

                <div style="padding:2px 6px; font-size:11px; text-align:right">Course and Year:</div>
                <div style="padding:2px 6px; border-bottom:1px solid #000;"></div>

            </div>
        </div>

        {{-- ROW 2 --}}
        <div class="row" style="padding:0; min-height:unset; margin-bottom:6px">
            <div style="display:grid; grid-template-columns:20% 80%; width:100%;">

                <div style="padding:2px 6px; font-size:11px;">
                    Student’s Address:
                </div>

                <div style="padding:2px 6px; border-bottom:1px solid #000;"></div>

            </div>
        </div>


        {{-- ROW 3 --}}
        <div class="row" style="padding:0; min-height:unset;">
            <div style="display:grid; grid-template-columns:20% 20% 20% 20% 10% 10%; width:100%;  ">

                <div style="padding:2px 6px; font-size:11px;">Student’s Mobile Number:</div>
                <div style="padding:2px 6px; border-bottom:1px solid #000;"></div>

                <div style="padding:2px 6px; font-size:11px; text-align:right">Signature:</div>
                <div style="padding:2px 6px; border-bottom:1px solid #000;"></div>

                <div style="padding:2px 6px; font-size:11px; text-align:right">Date:</div>
                <div style="padding:2px 6px; border-bottom:1px solid #000;"></div>

            </div>
        </div>



        {{-- ROW 4 --}}
        <div class="row" style="padding:4px 8px;">
            <div style="text-align:center; font-weight:700; font-size:12px;">
                PARENT’S CONSENT
            </div>
        </div>

        {{-- ROW 5 --}}
        <div class="row" style="padding:6px 8px;">
            <div style="font-size:11px; text-align:justify;">
                The undersigned parent/ guardian grants consent to my son/ daughter __________________________ (name of student) to participate in the aforementioned activity. Further, I have read, understood and denote full acceptance and conformity with the foregoing conditions.
            </div>
        </div>

        {{-- ROW 6 --}}

        <div class="row" style="padding:0; min-height:unset; margin-bottom:6px;">
            <div style="display:grid; grid-template-columns:20% 30% 25% 25%; width:100%;">

                <div style="padding:2px 6px; font-size:11px;">Parent/ Guardian’s Name:</div>
                <div style="padding:2px 6px; border-bottom:1px solid #000;"></div>

                <div style="padding:2px 6px; font-size:11px; text-align:right;">Relationship:</div>
                <div style="padding:2px 6px; border-bottom:1px solid #000;"></div>

            </div>
        </div>

        {{-- ROW 7 --}}
        <div class="row" style="padding:0; min-height:unset; margin-bottom:8px;">
            <div style="display:grid; grid-template-columns:20% 20% 20% 20% 10% 10%; width:100%;">

                <div style="padding:2px 6px; font-size:11px; text-align:right">Contact Number:</div>
                <div style="padding:2px 6px; border-bottom:1px solid #000;"></div>

                <div style="padding:2px 6px; font-size:11px; text-align:right;">Signature:</div>
                <div style="padding:2px 6px; border-bottom:1px solid #000;"></div>

                <div style="padding:2px 6px; font-size:11px; text-align:right;">Date:</div>
                <div style="padding:2px 6px; border-bottom:1px solid #000;"></div>

            </div>
        </div>

        {{-- ROW 8 --}}
        <div class="row" style="padding:0; min-height:unset; margin-bottom:2px;">
            <div style="display:grid; grid-template-columns:100%; width:100%;">

                <div style="padding:2px 6px; font-size:11px;">
                    Parent’s Remarks (if any):
                    <span style="display:inline-block; border-bottom:1px solid #000; width:80%;"></span>
                </div>

            </div>
        </div>

        {{-- ROW 9 --}}
        <div class="row" style="padding:4px 8px;">
            <div style="text-align:center; font-size:10px; font-style:italic;">
                (Please submit the whole form to your organization.)
            </div>
        </div>





    </div>

</div>

</body>
</html>