<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Refund Request</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #333; margin-top: 0;">Dear Customer,</h2>
                            <p style="color: #555; font-size: 16px;">
                                Thank you for contacting us. We are happy to provide you with an overview of your subscription and help you with any questions you may have.
                                <br/>Pixipals is a fun and user-friendly gaming platform that offers unlimited access to more than 90 exciting HTML5 games - no downloads or installations required. 
                                <br/>Subscribers can enjoy the content anytime and anywhere, completely ad-free, for just 4 EUR/week. You can cancel your subscription at any time through https://p.pixipals.com/sk/un/ or by email at sk@pixipals.com, including the mobile number you wish to unsubscribe.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                We have received your request and our team is currently reviewing it. We will inform you about the resolution as soon as possible.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Best regards,<br>
                                <span style="color: #333;">Support | Customer Service</span>
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
