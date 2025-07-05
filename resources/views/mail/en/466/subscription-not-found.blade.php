<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Active Subscription Not Found</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #333; margin-top: 0;">Active Subscription Not Found</h2>
                            <p style="color: #555; font-size: 16px;">
                                Dear Customer,
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Thank you for contacting us. We are happy to provide you with a clear overview of your subscription and help you with any questions you may have.
                                <br/>Pixipals is a fun and user-friendly gaming platform that offers unlimited access to more than 90 exciting HTML5 games - no downloads or installations required. 
                                <br/>Subscribers can enjoy the content anytime and anywhere, completely ad-free, for just 4 EUR/week. You can unsubscribe at any time through https://p.pixipals.com/sk/un/ or by email at sk@pixipals.com, including the mobile number you wish to unsubscribe.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Based on our records, it appears that the number <strong>{{ $MOBILE_NUMBER ?? '' }}</strong> was never subscribed to our service. As a result, there were no charges associated with this number.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                However, if you have evidence or any supporting documentation that contradicts this statement, we kindly request you to provide proof of the charge. This will allow us to conduct a thorough investigation and address this matter accordingly.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                We assure you that we have made every effort to provide you with the necessary information and ensure transparency throughout this process.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                If you have any further questions or need additional assistance, please don't hesitate to contact our dedicated customer care team.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Thank you for your understanding and cooperation.
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