<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Numéro de mobile introuvable</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #333; margin-top: 0;">Nous avons besoin de votre numéro de mobile</h2>
                            <p style="color: #555; font-size: 16px;">
                                Cher client,
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Merci de nous avoir contactés.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Afin de vous aider plus efficacement, pourriez-vous nous fournir votre numéro de téléphone portable ainsi qu'une brève description du problème que vous rencontrez ?
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Nous espérons pouvoir résoudre ce problème dans les plus brefs délais.
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