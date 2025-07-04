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
                            <p style="color: #555; font-size: 16px;">Dear Customer,</p>
                            <p style="color: #555; font-size: 16px;">
                                Thank you for reaching out to us. We're happy to provide you with a clear overview of your subscription and assist you with any questions you may have.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Pixipals is a fun and easy-to-use gaming platform that offers unlimited access to over 90 exciting HTML5 games—no downloads, no ads, just pure entertainment. 
                                <br/>Subscribers can enjoy the content anytime, anywhere, for just 9.00 EUR per week. 
                                <br/>To unsubscribe, simply send STOP to the short number 67803 from which you receive messages, and you will get a confirmation once your subscription has ended.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Based on our records, it appears that the number <strong>{{ $MOBILE_NUMBER ?? 'provided mobile number' }}</strong> has never been subscribed to our service. As a result, no charges have been associated with this number.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                However, if you have evidence or any supporting documentation that contradicts this statement, we kindly request you to provide us with the proof of charge. This will enable us to conduct a thorough investigation and address the matter accordingly.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Rest assured, we have made every effort to provide you with the necessary information and ensure transparency throughout this process.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Should you have any further questions or require additional assistance, please do not hesitate to contact our dedicated Customer Care team.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Thank you for your understanding and cooperation.
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