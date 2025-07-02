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
                            <h2 style="color: #333; margin-top: 0;">Cześć,</h2>
                            <p style="color: #555; font-size: 16px;">
                                Chcielibyśmy wyrazić naszą wdzięczność za Twoje zapytanie.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Z przyjemnością przedstawimy Ci przejrzysty przegląd Twojej subskrypcji i pomożemy w przypadku jakichkolwiek pytań.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                MyGym Club to usługa przesyłania strumieniowego wideo oferująca najlepsze treningi, wskazówki dotyczące treningu, energetyzującą muzykę, zdrowe przepisy i poradniki fitness.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                MyGym Club to jednorazowa usługa w cenie 30,75 zł (30 dni nieograniczonego dostępu).
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Chcielibyśmy poinformować, że numer <strong>{{ $MOBILE_NUMBER ?? '' }}</strong> został wypisany z naszej usługi od <strong>{{ $BRAND_NAME ?? '' }}</strong>.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Dołożyliśmy wszelkich starań, aby udzielić Ci odpowiednich informacji w tej sprawie.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Prosimy o przyjęcie naszych serdecznych pozdrowień,<br>
                                <span style="color: #333;">Pomoc l Obsługa klienta</span>
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