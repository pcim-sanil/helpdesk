<?php

namespace App\Features\AutoProcessEmail;

enum AutoProcessResponseTypeEnum: string
{
    case MOBILE_NUMBER_NOT_FOUND = 'MOBILE_NUMBER_NOT_FOUND';
    case SUBSCRIPTION_NOT_FOUND = 'SUBSCRIPTION_NOT_FOUND';
    case UNSUBSCRIBED = 'UNSUBSCRIBED';
    case CASE_FORWARDED = 'CASE_FORWARDED';
}
