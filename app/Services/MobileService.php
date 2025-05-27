<?php

namespace App\Services;

use libphonenumber\Leniency;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberMatcher;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;

class MobileService
{
    /**
     * Extract *valid* mobile numbers from a block of text.
     *
     * @param  string  $region  ISO 3166-1 alpha-2 country code (e.g. "AU", "PH", "GB", "IE", "MY", "NZ", "SG", "CH", "AE")
     * @return string[] Array of E.164 numbers (e.g. "+61412345678")
     */
    public function extractMobiles(string $text, string $region = 'AU'): array
    {
        $util = PhoneNumberUtil::getInstance();
        $matcher = new PhoneNumberMatcher($util, $text, $region, Leniency::POSSIBLE(), 1000);

        $found = [];

        foreach ($matcher as $match) {
            $numberProto = $match->number();

            // Only include *valid* mobile (or fixed_line_or_mobile) numbers
            $type = $util->getNumberType($numberProto);
            if (! in_array($type, [PhoneNumberType::MOBILE, PhoneNumberType::FIXED_LINE_OR_MOBILE], true)) {
                continue;
            }

            // Format as E.164, e.g. "+61412345678"
            $e164 = $util->format($numberProto, PhoneNumberFormat::E164);

            // Dedupe
            $found[$e164] = $e164;
        }

        return array_values($found);
    }
}
