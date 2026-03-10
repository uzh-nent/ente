<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum InterpretationMeta : string implements TranslatableInterface
{
    case NO_GROWTH = 'NO_GROWTH';
    case MIXED_CULTURE = 'MIXED_CULTURE';
    case NEGATIVE = 'NEGATIVE';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, [], 'enum_interpretation_meta');
    }
}
