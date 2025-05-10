<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mobilnummer ikke funnet</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #333; margin-top: 0;">Vi trenger ditt mobilnummer</h2>
                            <p style="color: #555; font-size: 16px;">
                                Kjære kunde,
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Takk for at du kontaktet oss.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                For å hjelpe deg mer nøyaktig, kan du oppgi mobilnummeret ditt sammen med en kort beskrivelse av problemet du opplever?
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Vi ser frem til å løse dette for deg så raskt som mulig.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Vennlig hilsen,<br>
                                <span style="color: #333;">Kundeserviceteamet</span>
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