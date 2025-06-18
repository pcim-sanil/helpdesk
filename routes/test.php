<?php

use App\Domains\AutoProcessEmail\AuroProcessEmailService;
use App\Domains\AutoProcessEmail\Jobs\CzFunnerzJob;
use App\Http\Controllers\TicketController;
use App\Services\LanguageDectorService;
use App\Services\MobileService;
use Illuminate\Support\Facades\Route;

Route::get('/test', [TicketController::class, 'index']);

Route::get('/c0', function () {
    // run job
    $autoProcessEmailService = new AuroProcessEmailService;
    $emails = $autoProcessEmailService->getEmailsToBeProcessed();

    $autoProcessableEmailData = $emails->first();

    dd($autoProcessableEmailData);

    if ($emails->count() > 0) {
        $autoProcessableEmailData = $emails->first();
        CzFunnerzJob::dispatch($autoProcessableEmailData);
    } else {
        dd('No emails to process');
    }
});

Route::get('/c1', function () {
    $autoProcessEmailService = new AuroProcessEmailService;
    $emails = $autoProcessEmailService->getEmailsToBeProcessed();

    $autoProcessableEmailData = $emails->first();

    $mobileService = new MobileService;

    $emailContent = $autoProcessableEmailData->email_subject.' '.$autoProcessableEmailData->email_content;

    dump($emailContent);

    $mobileNumbers = $mobileService->extractMobiles($emailContent, $autoProcessableEmailData->country_iso_alpha2);

    // detect language

    dump($mobileNumbers);

    $emailContent = 'Vážený zákazníku,

děkujeme, že jste se na nás obrátili.

Abychom vám mohli pomoci přesněji, mohli byste uvést číslo svého mobilního telefonu spolu se stručným popisem problému, se kterým se setkáváte?

Těšíme se, že to za vás co nejrychleji vyřešíme.

S pozdravem,';
    $languageDetector = new LanguageDectorService; // cs,hr,no

    $language = $languageDetector->detectLanguage($emailContent, $autoProcessableEmailData->detect_languages);

    dd($language);

    dd($mobileNumbers);
});
