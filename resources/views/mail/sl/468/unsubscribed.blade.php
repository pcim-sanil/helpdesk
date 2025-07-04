<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Unsubscribed Confirmation</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #333; margin-top: 0;">Pozdravljeni,</h2>
                            <p style="color: #555; font-size: 16px;">
                                Hvala, ker ste se obrnili na nas. Z veseljem vam bomo zagotovili jasen pregled vaše naročnine in vam pomagali pri morebitnih vprašanjih.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Momoxxio je inovativna storitev mobilnih vsebin, ki vam omogoča dostop do široke palete zabavnih in zanimivih vsebin, vključno z novicami, igrami, e-knjigami, glasbo, horoskopi in še več. 
                                <br/>Uživajte v najnovejših posodobitvah neposredno na telefonu in se zabavajte kadar koli in kjer koli – za samo 4,98 EUR na teden. Če se želite odjaviti, preprosto pošljite STOP MOMO na 6060 (na voljo za uporabnike Telekoma, A1 in Telemacha).
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Obveščamo vas, da je bila številka <strong>{{ $MOBILE_NUMBER ?? '' }}</strong> odjavljena iz naše storitve z dne <strong>{{ $BRAND_NAME ?? '' }}</strong>.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Prizadevali smo si, da bi vam zagotovili ustrezne informacije o tej zadevi.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Lepo pozdravljeni,<br>
                                <span style="color: #333;">Podpora l Skrb za stranke</span>
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