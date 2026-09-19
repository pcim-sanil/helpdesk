<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Unsubscribed Confirmation</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <p style="color: #555; font-size: 16px;">Hello {CALLER_NAME},</p>
                            <p style="color: #555; font-size: 16px;">Thank you for your email.</p>
                            <p style="color: #555; font-size: 16px;">
                                We can confirm your subscription with <strong>{{ $BRAND_NAME ?? '' }}</strong> for the number provided <strong>{{ $MOBILE_NUMBER ?? '' }}</strong> has been terminated.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                If you have any further questions or need assistance, please do not hesitate to contact the helpdesk on <strong>{{ $SERVICE_1300 ?? '' }}</strong>.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Kind regards,<br>
                                <span style="color: #333;">Global Billing Support Team</span>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="font-family: Arial, sans-serif; color: #333; font-size: 14px; line-height: 1.4; padding:30px;">
        {!! $SENDER_EMAIL_PREVIEW ?? '' !!}
    </div>
</body>
</html>
