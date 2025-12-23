<?php

namespace App\Enum;

enum ProbeStatus : string
{
    case CREATED = 'CREATED';
    case ANALYSIS_STARTED = 'ANALYSIS_STARTED';
    case ABORTED = 'ABORTED';
    case FINISHED = 'FINISHED';
}
