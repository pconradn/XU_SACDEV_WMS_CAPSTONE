<table style="width:100%; border-collapse:collapse; font-size:12px; table-layout:fixed;">

    <tr>

        <td style="width:55%; vertical-align:top; border:0px solid #ccc; padding:12px; padding-top:25px;">

            <table style="width:100%; border-collapse:collapse;">

                <tr>

                    <td style="border:0px solid #ccc; width:70px; padding-right:10px;" rowspan="3">
                        <img src="{{ asset('xulogo.png') }}" style="width:55px; height:55px;">
                    </td>

                    <td style="border:0px solid #ccc; font-weight:bold; padding-bottom:2px;">
                        Xavier University
                    </td>

                </tr>

                <tr>

                    <td style="border:0px solid #ccc; font-weight:bold; padding-bottom:2px;">
                        Ateneo de Cagayan
                    </td>

                </tr>

                <tr>

                    <td style="border:0px solid #ccc;">
                        Corrales Avenue 9000 Cagayan de Oro City
                    </td>

                </tr>

            </table>

        </td>


        <td style="width:35%; vertical-align:top; border:0px solid #ccc; padding:12px;">

            <table style="width:100%; border-collapse:collapse;">

                <tr style="border:0px solid #ccc;">
                    <td style="text-align:right; font-size:11px; padding-bottom:6px; ">
                        Form A4 (2023 Edition)
                    </td>
                </tr>


            </table>

            <table style="width:100%; border-collapse:collapse; border:0px solid #ccc;">


                <tr>
                    <td style="border:0px solid #ccc;">

                        <table style="width:100%; border-collapse:collapse; border:0px solid #ccc;">

                            <tr>
                                <td style="border:0px solid #ccc; text-align:right; font-weight:bold; width:180px; padding-right:6px;">
                                    DISBURSEMENT REQUEST:
                                </td>

                                <td style="border:0px solid #ccc; border-bottom:1px solid black;"></td>
                            </tr>


                        </table>

                        <table style="width:50%; border-collapse:collapse;">



                            <tr>
                                <td style="border:0px solid #ccc; text-align:left; padding-left:25px;">
                                    Date:
                                </td>

                                <td style="border:0px solid #ccc; border-bottom:1px solid black;">
                                    {{ \Carbon\Carbon::parse($dvDate)->format('F d, Y') }}
                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>

            </table>

        </td>

    </tr>


<tr>

    <td style="vertical-align:top; border:0px solid #ccc; padding-top:20px;">

        <table style="width:100%; border-collapse:collapse;">

            <tr>
                <td style="border:0px solid #ccc; font-weight:bold; padding-bottom:6px;">
                    TO: XU Finance Office
                </td>
            </tr>

            <tr>
                <td style="border:0px solid #ccc; padding-bottom:4px;">

                    <strong>Please pay</strong>

                    <span style="
                        display:inline-block;
                        border-bottom:1px solid black;
                        width:260px;
                        text-align:center;
                        margin-left:8px;
                    ">
                        {{ $projectHead?->name }}
                    </span>

                </td>
            </tr>

            <tr>
                <td style="border:0px solid #ccc; border-top:0px">

                    <span style="
                        display:inline-block;
                        width:260px;
                        text-align:center;
                        margin-left:72px;
                        font-size:10px;
                        border:0px solid #ccc;
                        border-top:0px
                    ">
                        Project Head
                    </span>

                </td>
            </tr>

        </table>

    </td>


    <td style="vertical-align:top; border:0px solid #ccc; padding:0px;">

        <table style="border:0px solid #ccc; width:100%; border-collapse:collapse; font-size:12px;">

            <colgroup>
                <col style="width:110px;">  
                <col style="width:100px;">  
                <col style="width:90px;">   
            </colgroup>

            <tr>

                <td colspan="2"
                    style="
                        border:2px solid black;
                        border-bottom:1px solid black;
                        padding:6px;
                        color:#0070c0;
                        font-weight:bold;
                    ">
                    SAP DOCUMENTS:
                </td>

                <td
                    style="
                        border:2px solid black;
                        border-left:none;
                        border-bottom:1px solid black;
                        text-align:center;
                        padding:6px;
                    ">
                    Date
                </td>

            </tr>

            <tr>

                <td
                    style="
                        border-left:2px solid black;
                        border-bottom:1px solid black;
                        padding:6px;
                    ">
                    AP INVOICE #
                </td>

                <td
                    style="
                        border-bottom:1px solid black;
                    ">
                </td>

                <td
                    style="
                        border-right:2px solid black;
                        border-bottom:1px solid black;
                    ">
                </td>

            </tr>

            <tr>

                <td
                    style="
                        border-left:2px solid black;
                        border-bottom:2px solid black;
                        padding:6px;
                    ">
                    DISB VOUCHER #
                </td>

                <td
                    style="
                        border-bottom:2px solid black;
                    ">
                </td>

                <td
                    style="
                        border-right:2px solid black;
                        border-bottom:2px solid black;
                    ">
                </td>

            </tr>

        </table>

    </td>

</tr>

</table>