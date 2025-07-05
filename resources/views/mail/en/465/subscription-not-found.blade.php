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
                                Thank you for contacting us. We are pleased to provide you with a clear overview of your subscription and assist you with any questions.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                MyGym Club is a video streaming service offering the best workouts, training tips, energizing music, healthy recipes, and fitness guides.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                MyGym Club is a one-time service priced at 30.75 PLN (30 days of unlimited access).
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Based on our records, it appears that the number <strong>{{ $MOBILE_NUMBER ?? '' }}</strong> has never been subscribed to our service. Therefore, no charges have been associated with this number.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                However, if you have evidence or any supporting documentation that contradicts this statement, we kindly request you to provide us with proof of payment. This will enable us to conduct a thorough investigation and address the matter appropriately.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Please be assured that we have made every effort to provide you with the necessary information and ensure transparency throughout this process.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                If you have any further questions or need additional assistance, please don't hesitate to contact our dedicated customer support team.
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