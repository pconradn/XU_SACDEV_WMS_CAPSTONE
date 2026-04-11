<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SACDEV Account Credentials</title>
</head>
<body style="margin:0; padding:0; background-color:#f1f5f9; font-family:Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9; padding:20px 0;">
        <tr>
            <td align="center">

                <table width="100%" max-width="520" cellpadding="0" cellspacing="0"
                       style="background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #e2e8f0;">

                    {{-- HEADER --}}
                    <tr>
                        <td style="background:#0f172a; padding:16px 20px;">
                            <span style="color:#ffffff; font-size:14px; font-weight:bold;">
                                SACDEV System
                            </span>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding:24px 20px; color:#0f172a;">

                            <p style="margin:0 0 12px 0; font-size:14px;">
                                Hello <strong>{{ $name }}</strong>,
                            </p>

                            <p style="margin:0 0 16px 0; font-size:13px; color:#475569;">
                                You have been assigned a role in the SACDEV Project Workflow System.
                            </p>

                            {{-- CREDENTIAL BOX --}}
                            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:14px; margin-bottom:16px;">
                                
                                <div style="font-size:12px; color:#64748b; margin-bottom:4px;">
                                    Login Email
                                </div>
                                <div style="font-size:13px; font-weight:600; margin-bottom:10px;">
                                    {{ $email }}
                                </div>

                                <div style="font-size:12px; color:#64748b; margin-bottom:4px;">
                                    Temporary Password
                                </div>
                                <div style="font-size:13px; font-weight:600;">
                                    {{ $tempPassword }}
                                </div>

                            </div>

                            <p style="margin:0 0 12px 0; font-size:13px; color:#475569;">
                                You will be required to change your password upon your first login.
                            </p>

                            <p style="margin:0 0 16px 0; font-size:12px; color:#94a3b8;">
                                If this account was not intended for you, please contact the SACDEV administrator immediately.
                            </p>

                            <p style="margin:0; font-size:13px;">
                                Thank you.
                            </p>

                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="padding:14px 20px; background:#f8fafc; font-size:11px; color:#94a3b8;">
                            SACDEV Organization Management System
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>