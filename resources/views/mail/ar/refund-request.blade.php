<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>طلب استرداد الاشتراك</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0; direction: rtl;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td style="text-align: right;">
                            <p style="color: #555; font-size: 16px;">عزيزي {CALLER_NAME}،</p>
                            <p style="color: #555; font-size: 16px;">
                                نشكرك على التواصل معنا بخصوص طلب استرداد اشتراكك. نحن نقدر سعة صدرك وتفهمك بينما نراجع طلبك بعناية.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                بعد مراجعة حسابك، تأكدنا من أن الاشتراك المعني تم تفعيله في <strong>{{ $ACTIVATION_DATE ?? '[Activation Date]' }}</strong>.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                إذا كانت لديك أي أسئلة إضافية، يرجى الرد على هذا البريد الإلكتروني وسنكون سعداء بمساعدتك.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                أطيب التحيات،<br>
                                <span style="color: #333;">فريق دعم الفواتير العالمية</span>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div style="font-family: Arial, sans-serif; color: #333; font-size: 14px; line-height: 1.4; padding:30px; direction: rtl; text-align: right;">
        {!! $SENDER_EMAIL_PREVIEW ?? '' !!}
    </div>
</body>
</html>
