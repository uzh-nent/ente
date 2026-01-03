<?php

namespace App\Enum;

enum ReportReceiver : string
{
    case PROBE_ORDERER_ORG = 'PROBE_ORDERER_ORG';
    case PROBE_ORDERER_PRAC = 'PROBE_ORDERER_PRAC';
    case ORGANIZATION = 'ORGANIZATION';
    case PRACTITIONER = 'PRACTITIONER';
}
