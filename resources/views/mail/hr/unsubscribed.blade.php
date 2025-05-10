<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Potvrda o otkazivanju pretplate</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #333; margin-top: 0;">Pretplata je otkazana</h2>
                            <p style="color: #555; font-size: 16px;">
                                Poštovani kupče,
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Hvala vam što ste nas kontaktirali.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Potvrđujemo da je vaša pretplata sa <strong>{{BRAND_NAME}}</strong> povezanom sa brojem mobilnog telefona <strong>{{MOBILE_NUMBER}}</strong> uspešno otkazana.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Ako imate dodatnih pitanja ili vam je potrebna pomoć, ne oklevajte da se obratite.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Srdačan pozdrav,<br>
                                <span style="color: #333;">Tim za podršku</span>
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