<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum InterpretationGroup : string implements TranslatableInterface
{
    case POS = 'POS';
    case POS_NEG = 'POS-NEG';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, [], 'enum_interpretation_group');
    }
}
