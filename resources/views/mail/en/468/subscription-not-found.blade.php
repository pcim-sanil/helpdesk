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
                            <h2 style="color: #333; margin-top: 0;">Dear Customer,</h2>
                            <p style="color: #555; font-size: 16px;">
                                Thank you for contacting us. We are happy to provide you with a clear overview of your subscription and assist you with any questions you may have.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Momoxxio is an innovative mobile content service that provides you access to a wide range of entertaining and interesting content, including news, games, e-books, music, 
                                <br/>horoscopes, and more. Enjoy the latest updates directly on your phone and have fun anytime, anywhere – for just 4.98 EUR per week. If you wish to unsubscribe, simply send STOP MOMO to 6060 (available for Telekom, A1, and Telemach users).
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Based on our records, it appears that the number <strong>{{ $MOBILE_NUMBER ?? '' }}</strong> has never been subscribed to our service. Consequently, there have been no charges associated with this number.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                However, if you have evidence or any supporting documentation that contradicts this, we kindly request you to provide us with proof of payment. This will enable us to conduct a thorough investigation and address the matter appropriately.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Rest assured that we have made every effort to provide you with the necessary information and ensure transparency throughout the process.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                If you have any additional questions or need further assistance, please don't hesitate to contact our dedicated customer support team.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Thank you for your understanding and cooperation.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Best regards,<br>
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