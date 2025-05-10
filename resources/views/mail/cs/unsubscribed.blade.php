<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Předplatné zrušeno</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #333; margin-top: 0;">Předplatné zrušeno</h2>
                            <p style="color: #555; font-size: 16px;">
                                Vážený zákazníku,
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                děkujeme, že jste nás kontaktovali.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Potvrzujeme, že vaše předplatné služby <strong>{{BRAND_NAME}}</strong> propojené s mobilním číslem <strong>{{MOBILE_NUMBER}}</strong> bylo úspěšně zrušeno.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Pokud máte další otázky nebo potřebujete pomoc, neváhejte se na nás obrátit.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                S pozdravem,<br>
                                <span style="color: #333;">Tým zákaznické podpory</span>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="font-family: Arial, sans-serif; color: #333; font-size: 14px; line-height: 1.4;">
        {!! $SENDER_EMAIL_PREVIEW ?? '' !!}
    </div>
</body>
</html>