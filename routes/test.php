<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Helpdesk\TicketModel;
use App\Models\Helpdesk\CompanyInfoModel;
use App\Models\Helpdesk\SmsServiceShortCodeModel;
Route::get('/test', function () {



    $smsService = SmsServiceShortCodeModel::find(129);

    dump($smsService->companyInfo->toArray());

    exit;
    $companyInfo = CompanyInfoModel::find(231);

    dump(count($companyInfo->tickets->toArray()));


    exit;
    $ticket = TicketModel::find(3025170);

    dump($ticket->companyInfo?->toArray());

    exit;
});
