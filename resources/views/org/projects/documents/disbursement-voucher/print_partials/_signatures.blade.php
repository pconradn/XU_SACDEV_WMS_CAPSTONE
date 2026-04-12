<table class="no-border">

<div style="margin-top:0px;">

    <div style="
        display:grid;
        grid-template-columns: 3fr 2fr;
        font-size:12px;
    ">

        {{-- LEFT --}}
        <div style="
            
            padding:3px;
        ">

            <strong>Requested by:</strong>

            <div style="margin-top:0px; text-align:center;">
                <div style="border-bottom:1px solid #000; display:inline-block; min-width:260px; font-weight:600;">
                    {{ $sigs['project_head']->user->name ?? ' ' }}
                </div>
                <div style="margin-top:4px;">Project Head</div>
            </div>

            <div style="margin-top:9px; text-align:center;">
                <div style="border-bottom:1px solid #000; display:inline-block; min-width:260px; font-weight:600;">
                    {{ $sigs['treasurer']->user->name ?? ' ' }}
                </div>
                <div style="margin-top:4px;">Treasurer</div>
            </div>

            <div style="margin-top:9px; text-align:center;">
                <div style="border-bottom:1px solid #000; display:inline-block; min-width:260px; font-weight:600;">
                    {{ $sigs['president']->user->name ?? ' ' }}
                </div>
                <div style="margin-top:4px;">President</div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div style="padding:8px;">

            <strong >Checked against Budget/Funding:__________________</strong>

            {{-- empty space intentionally --}}
            <div style="height:120px;"></div>

        </div>

    </div>


    

</div>
</table>
















