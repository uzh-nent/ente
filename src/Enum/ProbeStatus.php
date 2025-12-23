<?php

namespace App\Enum;

enum ProbeStatus : string
{
    case CREATED = 'CREATED';
    case STARTED = 'STARTED';
    case ABORTED = 'ABORTED';
    case FINISHED = 'FINISHED';
}
