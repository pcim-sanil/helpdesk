<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Abonnement gekündigt</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #333; margin-top: 0;">Abonnement gekündigt</h2>
                            <p style="color: #555; font-size: 16px;">
                                Sehr geehrter Kunde,
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Vielen Dank für Ihre Kontaktaufnahme.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Wir bestätigen, dass Ihr Abonnement bei <strong>{{BRAND_NAME}}</strong> mit der Mobilfunknummer <strong>{{MOBILE_NUMBER}}</strong> erfolgreich gekündigt wurde.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Bei weiteren Fragen oder wenn Sie Hilfe benötigen, wenden Sie sich bitte an uns.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Mit freundlichen Grüßen,<br>
                                <span style="color: #333;">Ihr Support-Team</span>
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