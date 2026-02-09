<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum MethodType: string implements TranslatableInterface
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

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, [], 'enum_method_type');
    }
}
