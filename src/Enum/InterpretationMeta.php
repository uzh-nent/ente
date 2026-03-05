<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum InterpretationMeta : string
{
    case NO_GROWTH = 'NO_GROWTH';
    case MIXED_CULTURE = 'MIXED_CULTURE';
}
