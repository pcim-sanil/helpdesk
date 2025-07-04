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
                            <h2 style="color: #333; margin-top: 0;">Odhlásenie z predplatnej služby</h2>
                            <p style="color: #555; font-size: 16px;">
                                Dobrý deň,
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Ďakujeme, že ste nás kontaktovali. Radi vám poskytneme prehľad o vašom predplatnom a pomôžeme vám s akýmikoľvek otázkami, ktoré by ste mohli mať.
                                Pixipals je zábavná a užívateľsky prívetivá herná platforma, ktorá ponúka neobmedzený prístup k viac ako 90 vzrušujúcim hrám HTML5 – nie je potrebné žiadne sťahovanie ani inštalácia. Predplatitelia si môžu vychutnať obsah kedykoľvek a kdekoľvek, úplne bez reklám, len za 4 EUR/týždeň. Odhlásiť sa môžete kedykoľvek prostredníctvom https://p.pixipals.com/sk/un/ alebo e-mailom na adresu sk@pixipals.com, pričom uveďte aj mobilné číslo, z ktorého sa chcete odhlásiť.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Chceli by sme vás informovať, že číslo <strong>{{ $MOBILE_NUMBER ?? 'XXXXXXXX' }}</strong> bolo odhlásené z našej služby <strong>{{ $BRAND_NAME ?? '' }}</strong> k dátumu XXXXX.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Snažili sme sa vám poskytnúť primerané informácie o tejto záležitosti.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Prijmite s pozdravom,<br>
                                <span style="color: #333;">Podpora l Zákaznícka podpora</span>
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