<div style="border:1px solid #000; margin-bottom:10px;">

    {{-- TOP STRIP --}}
    <div style="display:flex; justify-content:space-between; align-items:center; padding:6px 10px; font-size:11px;">

        {{-- LEFT --}}
        <div style="font-style:italic;">
            Your org letterhead here. Please use A4 paper.
        </div>

        {{-- RIGHT --}}
        <div style="background:#2f6fb3; color:#fff; padding:4px 10px; font-weight:bold;">
            Form A1 <span style="font-weight:normal;">(2023 Edition)</span>
        </div>

    </div>

    {{-- TITLE --}}
    <div style="text-align:center; padding:10px 0;">
        <div style="font-size:18px; font-weight:bold; letter-spacing:0.5px;">
            PROJECT PROPOSAL
        </div>
        <div style="font-size:11px;">
            (Please accomplish 4 copies)
        </div>
    </div>

    {{-- BLUE SECTION HEADER --}}
    <div style="background:#2f6fb3; color:#fff; text-align:center; font-weight:bold; padding:4px;">
        PROJECT DEFINITION
    </div>

    {{-- PROJECT TITLE --}}
    <div style="padding:8px 10px;">

        <div style="font-size:12px; margin-bottom:4px;">
            <strong>Name/ Title of Project:</strong>
        </div>

        <div style="text-align:center; font-weight:bold; font-size:13px;">
            {{ $project->title }}
        </div>

    </div>

</div>