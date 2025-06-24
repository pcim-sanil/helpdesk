<?php

namespace App\Domains\AutoProcessEmail;

enum AutoProcessResponseTypeEnum: string
{
    case MOBILE_NUMBER_NOT_FOUND = 'MOBILE_NUMBER_NOT_FOUND';
    case SUBSCRIPTION_NOT_FOUND = 'SUBSCRIPTION_NOT_FOUND';
    case UNSUBSCRIBED = 'UNSUBSCRIBED';
    case CASE_FORWARDED = 'CASE_FORWARDED';
    case REFUND_REQUESTED = 'REFUND_REQUESTED';
    case LEAVE_AS_IS = 'LEAVE_AS_IS';
}
