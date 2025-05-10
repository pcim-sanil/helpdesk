<?php

use App\Services\MobileService;

describe('MobileService', function () {
    beforeEach(function () {
        $this->service = new MobileService();
    });

    test('it extracts Philippines (63) mobile numbers correctly', function () {
        $service = new MobileService();

        // Test with various formats (63XXXXXXXXXX or 0XXXXXXXXXX)
        $text = "Contact me at 091 712 34567 or +639181234567 or 639191234567 6391912 34567 639191234567 639191234567";
        $result = $service->extractMobiles($text, 'PH');

        expect($result)->toBe([
            '+639171234567',
            '+639181234567',
            '+639191234567',
        ]);
    });

    test('it extracts Singapore (65) mobile numbers correctly', function () {
        $service = new MobileService();

        // Test with various formats (659XXXXXXX or 09XXXXXXX or 9XXXXXXX)
        $text = "SG numbers: 6591234567, 091234567, 91234567";
        $result = $service->extractMobiles($text, 'SG');

        expect($result)->toBe([
            '+6591234567'
        ]);
    });

    test('it extracts UK (44) mobile numbers correctly', function () {
        $service = new MobileService();

        // Test with various formats (447XXXXXXXXX or 07XXXXXXXXX or 7XXXXXXXXX)
        $text = "UK numbers: 447911123456, 07911123456, 7911123456";
        $result = $service->extractMobiles($text, 'GB');

        expect($result)->toBe([
            '+447911123456'
        ]);
    });

    test('it extracts Ireland (353) mobile numbers correctly', function () {
        $service = new MobileService();

        // Test with various formats (353XXXXXXXXX or 0XXXXXXXXXX or XXXXXXXXXX)
        $text = "IE numbers: 353851234567, 0851234568, 0871234569";
        $result = $service->extractMobiles($text, 'IE');

        expect($result)->toBe([
            '+353851234567',
            '+353851234568',
            '+353871234569',
        ]);
    });

    test('it extracts Malaysia (60) mobile numbers correctly', function () {
        $service = new MobileService();

        // Test with various formats (60XXXXXXXXX or 0XXXXXXXXX or XXXXXXXXX)
        $text = "MY numbers: 601234567890, 0123456789, 123456789";
        $result = $service->extractMobiles($text, 'MY');

        expect($result)->toBe([
            '+60123456789'
        ]);
    });

    test('it extracts New Zealand (64) mobile numbers correctly', function () {
        $service = new MobileService();

        // Test with various formats (64XXXXXXXXX or 0XXXXXXXXX or XXXXXXXXX)
        $text = "NZ numbers: 64212345678, 0212345678, 212345678";
        $result = $service->extractMobiles($text, 'NZ');

        expect($result)->toBe([
            '+64212345678'
        ]);
    });

    test('it extracts UAE (971) mobile numbers correctly', function () {
        $service = new MobileService();

        // Test with various formats (971XXXXXXXXX or 0XXXXXXXXX or XXXXXXXXX)
        $text = "UAE numbers: 
            +971501234567 (Etisalat), 
            971521234567 (du), 
            0541234567 (Etisalat),
            0551234567,
            0561234567 (du),
            581234567 (Etisalat)";
        $result = $service->extractMobiles($text, 'AE');

        expect($result)->toBe([
            '+971501234567',
            '+971521234567',
            '+971541234567',
            '+971551234567',
            '+971561234567',
            '+971581234567'
        ]);
    });

    test('it extracts Switzerland (41) mobile numbers correctly', function () {
        $service = new MobileService();

        // Test with various formats (41XXXXXXXXX or 0XXXXXXXXX or XXXXXXXXX)
        $text = "CH numbers: 41791234567, 0791234567, 791234567";
        $result = $service->extractMobiles($text, 'CH');

        expect($result)->toBe([
            '+41791234567'
        ]);
    });

    test('it extracts Australia (61) mobile numbers correctly', function () {
        $service = new MobileService();

        // Test with various formats (614XXXXXXXX or 04XXXXXXXX or 4XXXXXXXX)
        $text = "AU numbers: 61412345678, 0412345678, 412345678";
        $result = $service->extractMobiles($text, 'AU');

        expect($result)->toBe([
            '+61412345678'
        ]);
    });

    test('it handles text with no valid mobile numbers', function () {
        $service = new MobileService();

        $text = "No mobile numbers here, just some text 123 456";
        $result = $service->extractMobiles($text, 'AU');

        expect($result)->toBe([]);
    });

    test('it deduplicates repeated numbers', function () {
        $service = new MobileService();

        $text = "Same number twice: 0412345678 and +61412345678";
        $result = $service->extractMobiles($text, 'AU');

        expect($result)->toBe(['+61412345678']);
    });

    test('it handles messy text with special characters', function () {
        $service = new MobileService();

        $text = "Number is (0412) 345-678 or maybe 0412.345.678!";
        $result = $service->extractMobiles($text, 'AU');

        expect($result)->toBe([
            '+61412345678'
        ]);
    });

    test('it extracts numbers from HTML email content', function () {
        $service = new MobileService();

        $htmlContent = '
            <div style="font-family: Arial, sans-serif;">
                <p>Dear Support,</p>
                <p>Please contact me at any of these numbers:</p>
                <ul>
                    <li><strong>Mobile:</strong> +61 412 345 678</li>
                    <li><a href="tel:+61413345678">+61 413 345 678</a></li>
                </ul>
                <div class="signature">
                    <p>Best regards,<br>
                    John Doe<br>
                    Tel: 0414 345 678</p>
                </div>
            </div>
        ';

        $result = $service->extractMobiles($htmlContent, 'AU');

        expect($result)->toBe([
            '+61412345678',
            '+61413345678',
            '+61414345678'
        ]);
    });

    test('it handles email with mixed content and formatting', function () {
        $service = new MobileService();

        $emailContent = '
            <html>
            <body>
                <div>
                    <p>Meeting details:</p>
                    <table border="1">
                        <tr>
                            <td>Primary Contact:</td>
                            <td>0487 688 943</td>
                        </tr>
                        <tr>
                            <td>Alternative:</td>
                            <td><a href="tel:+61487688944">+61 (487) 688 944</a></td>
                        </tr>
                    </table>
                    <p style="color: gray;">
                        Emergency: 0487.688.945 or <span class="phone">+61 487 688 946</span>
                    </p>
                    <p>
                        Sanil487688947Xas 
                        Jai487688 948Xas 
                    </p>
                </div>
            </body>
            </html>
        ';

        $result = $service->extractMobiles($emailContent, 'AU');

        expect($result)->toBe([
            '+61487688943',
            '+61487688944',
            '+61487688945',
            '+61487688946',
            '+61487688947',
            '+61487688948',
        ]);
    });

    test('extracts czech mobile numbers correctly', function () {
        $service = new MobileService();

        $text = 'Dobrý den,

        děkujeme za Váš zájem o naše služby. Pokud máte jakékoliv dotazy, neváhejte nás kontaktovat na telefonním čísle +420 602 123 456 nebo e-mailem na info@vasefirma.cz.

        602 123 457
        
        Přejeme hezký den!
        
        S pozdravem,
        Tým VašeFirma';
        $result = $service->extractMobiles($text, 'CZ');

        expect($result)->toBe([
            '+420602123456',
            '+420602123457'
        ]);
    });

    test('extracts serbian mobile numbers correctly', function () {
        $service = new MobileService();

        $text = "Poštovani,
                    zahvaljujemo na interesovanju za naše usluge. Za sve dodatne informacije i podršku, slobodno nas kontaktirajte na broj +381 60 123 4567 ili putem e-pošte na info@vasafirma.rs.
                    60 123 4568 and 60 1234569 and 381601234560
                Srdačno,
                Tim VašaFirma";

        $result = $service->extractMobiles($text, 'RS');

        expect($result)->toBe([
            '+381601234567',
            '+381601234568',
            '+381601234569',
            '+381601234560'
        ]);
    });

    test('extracts norwegian mobile numbers correctly', function () {
        $service = new MobileService();

        $text = "Kjære kunde,

                Takk for at du kontaktet oss.

                For å hjelpe deg mer nøyaktig, kan du oppgi mobilnummeret ditt (for eksempel +47 912 34 567) sammen med en kort beskrivelse av problemet du opplever?

                Vi ser frem til å løse dette for deg så raskt som mulig, 912 34 568 and 91234569

                Vennlig hilsen,
                Kundeservice";

        $result = $service->extractMobiles($text, 'NO');

        expect($result)->toBe([
            '+4791234567',
            '+4791234568',
            '+4791234569'
        ]);
    });
});
