<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Refund Request</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #333; margin-top: 0;">Vážený zákazník,</h2>
                            <p style="color: #555; font-size: 16px;">
                                Ďakujeme, že ste sa na nás obrátili. Radi vám poskytneme prehľad o vašom predplatnom a pomôžeme vám s akýmikoľvek otázkami, ktoré by ste mohli mať.
                                Pixipals je zábavná a užívateľsky prívetivá herná platforma, ktorá ponúka neobmedzený prístup k viac ako 90 vzrušujúcim hrám HTML5 – nie je potrebné nič sťahovať ani inštalovať. Predplatitelia si môžu obsah vychutnať kedykoľvek a kdekoľvek, úplne bez reklám, len za 4 EUR/týždeň. Odber môžete kedykoľvek zrušiť prostredníctvom https://p.pixipals.com/sk/un/ alebo e-mailom na adrese sk@pixipals.com, pričom uveďte aj číslo mobilného telefónu, z ktorého sa chcete odhlásiť.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Dostali sme vašu žiadosť a náš tím ju momentálne posudzuje. Čo najskôr vás budeme informovať o riešení.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                S pozdravom,<br>
                                <span style="color: #333;">Podpora | Zákaznícka podpora</span>
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
