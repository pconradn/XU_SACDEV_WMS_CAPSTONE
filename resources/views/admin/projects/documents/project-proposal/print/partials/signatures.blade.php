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


<div style="border:1px solid #2f6fb3; margin-top:10px;">

    {{-- PROJECT HEADER --}}
    <div style="padding:6px; text-align:center; border-bottom:1px solid #2f6fb3;">
        <div style="font-weight:600; font-size:13px;">
            {{ $project->title }}
        </div>

        <div style="font-size:11px;">
            {{ $proposal->start_date ? \Carbon\Carbon::parse($proposal->start_date)->format('M d, Y') : '—' }}
            —
            {{ $proposal->end_date ? \Carbon\Carbon::parse($proposal->end_date)->format('M d, Y') : '—' }}
        </div>
    </div>


    {{-- SIGNATORIES HEADER --}}
    <div style="
        background:#2f6fb3;
        color:#fff;
        text-align:center;
        font-weight:600;
        font-size:12px;
        padding:5px;
    ">
        SIGNATORIES
    </div>


    {{-- GRID --}}
    <div style="display:grid; grid-template-columns:1fr 1fr;">

        {{-- LEFT --}}
        <div style="border-right:1px solid #2f6fb3; padding:8px;">

            <div style="font-size:11px; margin-bottom:4px;">
                <strong>Prepared by:</strong>
            </div>

            {!! approvalLine('project_head', $sigs) !!}

            <div style="margin-top:8px; font-weight:600;">
                {{ sig('project_head', $sigs)?->user?->name ?? '—' }}
            </div>

            <div style="font-size:11px;">
                Project Head
            </div>

        </div>


        {{-- RIGHT --}}
        <div style="padding:0;">

            <div style="padding:8px; border-bottom:1px solid #2f6fb3;">
                <strong>Endorsed by:</strong>
            </div>

            <div style="
                display:grid;
                grid-template-columns:1fr 1fr;
            ">

                {{-- ROW 1 --}}

                <div style="padding:8px; border-bottom:1px solid #2f6fb3;">
                    {!! approvalLine('treasurer', $sigs) !!}
                    <div style="margin-top:6px; font-weight:600;">
                        {{ sig('treasurer', $sigs)?->user?->name ?? '—' }}
                    </div>
                    <div style="font-size:11px;">Treasurer</div>
                </div>

                <div style="padding:8px; border-bottom:1px solid #2f6fb3;">

                </div>

                {{-- ROW 2 --}}
                <div style="padding:8px; border-right:1px solid #2f6fb3;">
                    {!! approvalLine('president', $sigs) !!}
                    <div style="margin-top:6px; font-weight:600;">
                        {{ sig('president', $sigs)?->user?->name ?? '—' }}
                    </div>
                    <div style="font-size:11px;">President</div>
                </div>

                <div style="padding:8px;">
                    {!! approvalLine('moderator', $sigs) !!}
                    <div style="margin-top:6px; font-weight:600;">
                        {{ sig('moderator', $sigs)?->user?->name ?? '—' }}
                    </div>
                    <div style="font-size:11px;">Moderator</div>
                </div>

            </div>

        </div>

    </div>


    {{-- APPROVAL ROW --}}
    <div style="
        display:grid;
        grid-template-columns:1fr 1fr;
        border-top:1px solid #2f6fb3;
    ">

        {{-- SACDEV --}}
        <div style="padding:8px; border-right:1px solid #2f6fb3;">

            <strong style="font-size:11px;">Approved by:</strong>

            {!! approvalLine('sacdev_admin', $sigs) !!}

            <div style="margin-top:6px; font-weight:600;">
                {{ sig('sacdev_admin', $sigs)?->user?->name ?? '—' }}
            </div>

            <div style="font-size:11px;">
                SACDEV Head
            </div>

        </div>


        {{-- REMARKS --}}
        <div style="padding:8px;">

            <strong style="font-size:11px;">OSA-SACDEV Remarks</strong>

            <div style="margin-top:6px; font-size:12px;">
                {{ $document->remarks ?? '' }}
            </div>

        </div>

    </div>


    {{-- DOC ID --}}
    <div style="
        font-size:10px;
        text-align:right;
        padding:4px 6px;
        border-top:1px solid #2f6fb3;
    ">
        Document ID: PP-{{ $document->id }}
    </div>

</div>

@endif


    {{-- ================= STUDENT AGREEMENT PAGE ================= --}}
    <div style="
        page-break-before: always;
        break-before: page;
        font-family: 'Times New Roman', serif;
        font-size:12px;
        color:#000;
        margin-top:10px;
    ">

        {{-- HEADER --}}
        <div style="
            border-top:2px solid #2f6fb3;
            border-bottom:1px solid #2f6fb3;
            padding:6px 10px;
            display:grid;
            grid-template-columns: 1fr 1fr 1fr;
            font-size:11px;
        ">

            <div>
                <strong>Project Name/Title</strong><br>
                {{ $project->title }}
            </div>

            <div style="text-align:center;">
                <strong>Type</strong><br>
                Project Proposal
            </div>

            <div style="text-align:right;">
                <strong>Date</strong><br>
                {{ $document->updated_at?->format('M d, Y') ?? '—' }}
            </div>

        </div>


        {{-- TITLE --}}
        <div style="
            background:#2f6fb3;
            color:#fff;
            text-align:center;
            font-weight:700;
            font-size:13px;
            padding:6px;
            margin-top:6px;
        ">
            STUDENT AGREEMENT FORM
            <div style="font-size:10px; font-weight:400;">
                (Original Signature Required)
            </div>
        </div>


        {{-- CONTENT --}}
        <div style="padding:10px; line-height:1.5;">

            {{-- 1 --}}
            <div style="margin-bottom:10px;">
                <strong>1. Acknowledgment of Responsibilities</strong><br>
                I, the undersigned student, hereby acknowledge that I am responsible for submitting all post-documentation
                requirements for the project that I lead. I understand that failure to submit these requirements may result
                in my not being cleared in the Office of Student Affairs.
            </div>

            {{-- 2 --}}
            <div style="margin-bottom:10px;">
                <strong>2. Understanding of Consequences</strong><br>
                I understand that if I do not meet the submission requirements, I may face the following consequences:
                <ul style="margin-top:4px; padding-left:18px;">
                    <li>Inability to take my exams.</li>
                    <li>Inability to obtain other essential school documents.</li>
                    <li>The student organization I represent may not be able to initiate or start new projects.</li>
                </ul>
            </div>

            {{-- 3 --}}
            <div style="margin-bottom:10px;">
                <strong>3. Commitment to Compliance</strong><br>
                I commit to fulfilling my responsibilities regarding the timely submission of all required documentation
                related to my project. I acknowledge that this is crucial for my academic progress and standing within the institution.
            </div>

            {{-- 4 --}}
            <div style="margin-bottom:20px;">
                <strong>4. Acceptance of Terms</strong><br>
                By signing below, I confirm that I have read and understood the terms outlined in this agreement. I agree
                to comply with these requirements and accept the consequences should I fail to do so.
            </div>


            {{-- SIGNATURE BLOCK --}}
            <div style="margin-top:40px;">


                <div style="margin-top:30px; font-weight:700; font-size:13px;">
                    {{ sig('project_head', $sigs)?->user?->name ?? '—' }}
                </div>

                <div style="font-size:11px;">
                    Project Head
                </div>

                <div style="margin-top:6px; font-size:11px;">
                    Date Signed:
                    {{ sig('project_head', $sigs)?->signed_at?->format('M d, Y') ?? '—' }}
                </div>


                {{-- CONTACT INFO --}}
                @php
                    $officer = sig('project_head', $sigs)?->user->officerEntries()->first() ?? null;
                    //dd(sig('project_head', $sigs)?->user->officerEntries()->first()->mobile_number)
                @endphp

                <div style="margin-top:10px; font-size:11px;">
                    Mobile Number: {{ $officer->mobile_number ?? '—' }}<br>
                    Email Address: {{ $officer->email ?? '—' }}
                </div>

            </div>

                        {{-- SIGNATURE BLOCK --}}
            <div style="margin-top:20px;">

                <div style="font-weight:100;">
                    Witnesses:
                </div>

                <div style="margin-top:30px; font-weight:700; font-size:13px;">
                    {{ sig('president', $sigs)?->user?->name ?? '—' }}
                </div>

                <div style="font-size:11px;">
                    President
                </div>

                <div style="margin-top:6px; font-size:11px;">
                    Date Signed:
                    {{ sig('president', $sigs)?->signed_at?->format('M d, Y') ?? '—' }}
                </div>



            </div>

                        {{-- SIGNATURE BLOCK --}}
            <div style="margin-top:10px;">


                <div style="margin-top:30px; font-weight:700; font-size:13px;">
                    {{ sig('treasurer', $sigs)?->user?->name ?? '—' }}
                </div>

                <div style="font-size:11px;">
                    Treasurer
                </div>

                <div style="margin-top:6px; font-size:11px;">
                    Date Signed:
                    {{ sig('treasurer', $sigs)?->signed_at?->format('M d, Y') ?? '—' }}
                </div>



            </div>

                        {{-- SIGNATURE BLOCK --}}
            <div style="margin-top:10px;">


                <div style="margin-top:30px; font-weight:700; font-size:13px;">
                    {{ sig('sacdev_admin', $sigs)?->user?->name ?? '—' }}
                </div>

                <div style="font-size:11px;">
                    SACDEV Head
                </div>

                <div style="margin-top:6px; font-size:11px;">
                    Date Signed:
                    {{ sig('sacdev_admin', $sigs)?->signed_at?->format('M d, Y') ?? '—' }}
                </div>


            </div>

        </div>

    </div>