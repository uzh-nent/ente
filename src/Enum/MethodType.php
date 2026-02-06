<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum MethodType: string
{
    case SOP = "SOP"; // SOP is the Standard Operating Procedure

    // SOP may be composed out of the following methods, or combined with
    case AGGLUTINATION = "AGGLUTINATION";
    case BIOCHEMISTRY = "BIOCHEMISTRY";
    case PCR = "PCR";
    case RTPCR = "RTPCR";
    case MLST = "MLST";
    case WGS = "WGS";
    case GDS = "GDS";
}
