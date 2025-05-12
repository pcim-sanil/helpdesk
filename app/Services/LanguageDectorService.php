<?php

namespace App\Services;


use Throwable;

class LanguageDectorService
{
    public const ENGLISH_LANGUAGE = 'en';
    public const SERBIAN_LANGUAGE = 'sr';
    public const CROATIAN_LANGUAGE = 'hr';
    public const CZECH_LANGUAGE = 'cs';
    public const FRENCH_LANGUAGE = 'fr';
    public const ITALIAN_LANGUAGE = 'it';
    public const ARABIC_LANGUAGE = 'ar';
    public const GERMAN_LANGUAGE = 'de';
    public const NORWEGIAN_LANGUAGE = 'no';
    public const DANISH_LANGUAGE = 'da';
    public const SWEDISH_LANGUAGE = 'sv';

    /**
     * Detect the language of the text.
     * 
     *  English: en
     *  Serbian: sr  , hr
     *  Croatian: hr , sr
     *  Czech: cs
     *  French: fr
     *  Italian: it
     *  Arabic: ar
     *  German: de
     *  Norwegian: no
     * 
     * @param string|null $text
     * @param array $allowedLanguages
     * @return string
     */
    public function detectLanguage(?string $text, array $allowedLanguages = ['en']): string
    {
        if (empty($text)) {
            return self::ENGLISH_LANGUAGE;
        }

        try {
            $escaped_text = strip_tags($text);
            $escaped_text = escapeshellarg($escaped_text);

            $allowed_languages_str = escapeshellarg(implode(',', $allowedLanguages));

            $pythonPath = base_path('python/detect_language.py');

            if (!file_exists($pythonPath)) {
                return self::ENGLISH_LANGUAGE;
            }

            $command = "python3 " . $pythonPath . " " . $escaped_text . " " . $allowed_languages_str;
            $output = trim(shell_exec($command));

            return !empty($output) ? $output : self::ENGLISH_LANGUAGE;
        } catch (Throwable $e) {
            // write log if needed..
        }

        return self::ENGLISH_LANGUAGE;
    }
}
