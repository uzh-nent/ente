<?php

namespace App\Enum;

enum ElmApiStatus : string
{
    case CREATING = 'CREATING';
    case VALIDATION_ERROR = 'VALIDATION_ERROR'; // network error etc
    case VALIDATION_ISSUES = 'VALIDATION_ISSUES'; // failed validation
    case SEND_ERROR = 'SEND_ERROR';
    case SEND_ISSUES = 'SEND_ISSUES';
    case IN_PROGRESS = 'IN_PROGRESS';
    case SUCCESSFUL = 'SUCCESSFUL';
    case FAILED = 'FAILED';
}
