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
                            <h2 style="color: #333; margin-top: 0;">Szanowny Kliencie,</h2>
                            <p style="color: #555; font-size: 16px;">
                                Dziękujemy za kontakt z nami. Chętnie przedstawimy Ci przejrzysty przegląd Twojej subskrypcji i pomożemy w razie jakichkolwiek pytań.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Workout ON to platforma fitness z jednorazowym dostępem zaprojektowana, aby wspierać Twoje cele zdrowotne dzięki filmom z ćwiczeniami prowadzonym przez ekspertów, planom posiłków, motywującej muzyce, e-bookom i narzędziom do śledzenia kondycji. Niezależnie od tego, czy chcesz zwiększyć wytrzymałość, zbudować siłę czy zwiększyć elastyczność, nasze treści są tutaj, aby Cię poprowadzić. Usługa kosztuje 30,75 zł i nie odnawia się automatycznie — nie jest wymagane anulowanie.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Na podstawie naszych danych wydaje się, że numer <strong>{{ $MOBILE_NUMBER ?? '' }}</strong> nigdy nie był subskrybowany w naszej usłudze. W związku z tym z tym numerem nie powiązano żadnych opłat.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Jeśli jednak masz dowody lub jakąkolwiek dokumentację pomocniczą, która przeczy temu stwierdzeniu, uprzejmie prosimy o dostarczenie nam dowodu opłaty. Umożliwi nam to przeprowadzenie dokładnego dochodzenia i odpowiednie zajęcie się sprawą.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Niech Pan będzie spokojny, dołożyliśmy wszelkich starań, aby dostarczyć Panu niezbędnych informacji i zapewnić przejrzystość w całym procesie.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Jeśli masz jakiekolwiek pytania lub potrzebujesz dodatkowej pomocy, nie wahaj się skontaktować z naszym oddanym zespołem ds. obsługi klienta.
                            </p>
                            <p style="color: #555; font-size: 16px;">
                                Dziękujemy za zrozumienie i współpracę.
                            </p>
                            <p style="margin-top: 32px; color: #888; font-size: 15px;">
                                Z poważaniem,<br>
                                <span style="color: #333;">Wsparcie | Obsługa klienta</span>
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