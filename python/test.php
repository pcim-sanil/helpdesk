<?php



function detectLanguage(?string $text, array $allowedLanguages = ['en']): string
{
    if (empty($text)) {
        return 'en';
    }

    $escaped_text = strip_tags($text);
    $escaped_text = escapeshellarg($escaped_text);

    $allowed_languages_str = escapeshellarg(implode(',', $allowedLanguages));

    $pythonPath = __DIR__ . '/detect_language.py';

    if (!file_exists($pythonPath)) {
        return 'en';
    }

    $command = "python3 " . $pythonPath . " " . $escaped_text . " " . $allowed_languages_str;
    $output = trim(shell_exec($command));

    return !empty($output) ? $output : 'en';
}

// The text you want to detect the language for
$text = 'Kjære kunde,

Takk for at du kontaktet oss.

For å hjelpe deg mer nøyaktig, kan du oppgi mobilnummeret ditt (for eksempel +47 912 34 567) sammen med en kort beskrivelse av problemet du opplever?

Vi ser frem til å løse dette for deg så raskt som mulig.

Vennlig hilsen,
Kundeservice';

// Run the Python script and capture the output
$language = detectLanguage($text, ['sr','hr','fr', 'it', 'en','no','cs']);

echo "Detected language code: $language\n";
