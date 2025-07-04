<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Active Subscription Not Found</title>
</head>
<body style="background-color: #f6f6f6; font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f6f6; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
                    <tr>
                        <td>
                            <h2 style="color: #333; margin-top: 0;">Spoštovani kupec,</h2>
                            <p style="color: #555; font-size: 16px;">
                                hvala, ker ste se obrnili na nas. Z veseljem vam bomo zagotovili jasen pregled vaše naročnine in vam pomagali pri morebitnih vprašanjih.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Momoxxio je inovativna storitev mobilnih vsebin, ki vam omogoča dostop do široke palete zabavnih in zanimivih vsebin, vključno z novicami, igrami, e-knjigami, glasbo, 
                                <br/>horoskopi in še več. Uživajte v najnovejših posodobitvah neposredno na telefonu in se zabavajte kadar koli in kjer koli – za samo 4,98 EUR na teden. Če se želite odjaviti, preprosto pošljite STOP MOMO na 6060 (na voljo za uporabnike Telekoma, A1 in Telemacha).
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Na podlagi naših evidenc se zdi, da številka <strong>{{ $MOBILE_NUMBER ?? '' }}</strong> ni bila nikoli naročena na našo storitev. Posledično s to številko niso bili povezani nobeni stroški.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Če pa imate dokaze ali kakršno koli podporno dokumentacijo, ki nasprotuje tej trditvi, vas vljudno prosimo, da nam predložite dokazilo o plačilu. To nam bo omogočilo, da izvedemo temeljito preiskavo in ustrezno obravnavamo zadevo.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Bodite prepričani, da smo se potrudili, da vam zagotovimo potrebne informacije i zagotovimo preglednost v celotnem postopku.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Če imate dodatna vprašanja ali potrebujete dodatno pomoč, se obrnite na našo namensko ekipo za pomoč strankam.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Zahvaljujemo se vam za razumevanje in sodelovanje.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Lep pozdrav,<br>
                                <span style="color: #333;">Podpora | Skrb za stranke</span>
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