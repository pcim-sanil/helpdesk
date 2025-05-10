<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mobile Number Not Found</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #333; margin-top: 0;">We Need Your Mobile Number</h2>
                            <p style="color: #555; font-size: 16px;">
                                Thank you for reaching out to us.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                In order to assist you more accurately, could you please provide your mobile phone number along with a brief description of the issue you're experiencing?
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                We look forward to resolving this for you as quickly as possible.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Kind regards,<br>
                                <span style="color: #333;">The Support Team</span>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="font-family: Arial, sans-serif; color: #333; font-size: 14px; line-height: 1.4;">
        {!! $SENDER_EMAIL_PREVIEW ?? '' !!}
    </div>
</body>
</html>