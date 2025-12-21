<?php

namespace App\Enum;

enum AnalysisType: string
{
    case TYPING = "TYPING";
    case STEC = "EC_STEC";
    case EPEC = "EC_EPEC";
    case ETEC = "EC_ETEC";
    case EIEC = "EC_EIEC";
    case EAEC = "EC_EAEC";
}
