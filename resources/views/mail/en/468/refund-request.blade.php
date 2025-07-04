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
                                Thank you for reaching out to us. We are pleased to provide you with a clear overview of your subscription and assist you with any questions you may have.
                                <br/>Momoxxio is an innovative mobile content service that gives you access to a wide range of entertaining and interesting content, including news, games, e-books, music, horoscopes, and more. 
                                <br/>Enjoy the latest updates directly on your phone and have fun anytime, anywhere—for just 4.98 EUR per week. To unsubscribe, simply send STOP MOMO to 6060 (available for Telekom, A1, and Telemach users).
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                We have received your request, and our team is currently reviewing it. We will inform you of the resolution as soon as possible.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Kind regards,<br>
                                <span style="color: #333;">Support | Customer Care</span>
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
