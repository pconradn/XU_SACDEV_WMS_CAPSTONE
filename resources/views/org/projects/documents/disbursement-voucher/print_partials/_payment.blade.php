<table style="width:100%; border-collapse:collapse; font-size:12px;">

    <tr>

        <!-- AS LABEL -->
        <td style="border:0px solid #ccc; width:50px; vertical-align:top; padding-top:15px; padding-left:50px">
            as
        </td>


        <!-- PAYMENT TYPE -->
        <td style="width:56%; border:0px solid #ccc;" >

            <table style="width:100%; border-collapse:collapse;">

                <tr style="border:0px solid #ccc;">
                    <td style="border:0px solid #ccc; width:22px;">
                        <span class="checkbox">
                            {{ $paymentType=='reimbursement'?'X':'' }}
                        </span>
                    </td>

                    <td style="border:0px solid #ccc;">
                        reimbursement refund (attach summary and receipts)
                    </td>
                </tr>


                <tr>
                    <td style="border:0px solid #ccc;">
                        <span class="checkbox">
                            {{ $paymentType=='goods_services'?'X':'' }}
                        </span>
                    </td>

                    <td style="border:0px solid #ccc;">
                        payment for goods/services (attach supporting documents)
                    </td>
                </tr>


                <tr>
                    <td style="border:0px solid #ccc;">
                        <span class="checkbox">
                            {{ $paymentType=='honoraria'?'X':'' }}
                        </span>
                    </td>

                    <td style="border:0px solid #ccc;">
                        payroll item/honoraria (cut-off is every 10th and 25th of the month)
                    </td>
                </tr>


                <tr>
                    <td style="border:0px solid #ccc;">
                        <span class="checkbox">
                            {{ $paymentType=='advance'?'X':'' }}
                        </span>
                    </td>

                    <td style="border:0px solid #ccc;">
                        advance for liquidation due on
                        <span style="display:inline-block; border-bottom:1px solid black; width:140px;"></span>
                    </td>
                </tr>


                <tr>
                    <td style="border:0px solid #ccc;">
                        <span class="checkbox">
                            {{ $paymentType=='others'?'X':'' }}
                        </span>
                    </td>

                    <td style="border:0px solid #ccc;">
                        Others
                        <span style="display:inline-block; border-bottom:1px solid black; width:200px;"></span>
                    </td>
                </tr>

            </table>

        </td>


        <!-- PAYMENT MODE -->
        <td style=" width:35%; vertical-align:top; border:0px solid #ccc;">

            <table style="width:100%; border-collapse:collapse;">

                <tr>
                    <td style="width:22px; border:0px solid #ccc;">
                        <span class="checkbox">
                            {{ $paymentMode=='cash'?'X':'' }}
                        </span>
                    </td>

                    <td style="border:0px solid #ccc;">
                        cash
                    </td>
                </tr>


                <tr>
                    <td style="border:0px solid #ccc;">
                        <span class="checkbox" >
                            {{ $paymentMode=='check'?'X':'' }}
                        </span>
                    </td>

                    <td style="border:0px solid #ccc;">
                        check
                        <br>

                        <span style="font-size:10px;">
                            (cost of reissuance due to error shall be for account of requesting party)
                        </span>
                    </td>
                </tr>


                <tr>
                    <td style="border:0px solid #ccc;">
                        <span class="checkbox">
                            {{ $paymentMode=='payroll_credit'?'X':'' }}
                        </span>
                    </td>

                    <td style="border:0px solid #ccc;">
                        payroll credit
                    </td>
                </tr>


                <tr>
                    <td style="border:0px solid #ccc;">
                        <span class="checkbox">
                            {{ $paymentMode=='fund_transfer'?'X':'' }}
                        </span>
                    </td>

                    <td style="border:0px solid #ccc;">
                        fund transfer
                    </td>
                </tr>

            </table>

        </td>

    </tr>

</table>

<br>