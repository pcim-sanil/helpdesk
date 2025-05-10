<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Abonnement introuvable</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #333; margin-top: 0;">Abonnement introuvable</h2>
                            <p style="color: #555; font-size: 16px;">
                                Cher client,
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Merci de nous avoir contactés.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Nous vous informons que votre demande est actuellement en cours d'examen. Notre équipe étudie les détails et vous recontactera dans les meilleurs délais.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Nous vous remercions de votre patience et nous vous répondrons dans les meilleurs délais.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Cordialement,<br>
                                <span style="color: #333;">L'équipe d'assistance</span>
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