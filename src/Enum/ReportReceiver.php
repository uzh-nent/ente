<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;

enum ReportReceiver : string
{
    case PROBE_ORDERER_ORG = 'PROBE_ORDERER_ORG';
    case PROBE_ORDERER_PRAC = 'PROBE_ORDERER_PRAC';
    case ORGANIZATION = 'ORGANIZATION';
    case PRACTITIONER = 'PRACTITIONER';
}
