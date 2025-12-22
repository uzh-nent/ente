<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum CodeSystem: string implements TranslatableInterface
{
    case LOINC = 'LOINC';
    case SNOMED = 'SNOMED';
    case NENT = 'NENT';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, [], 'enum_code_system');
    }
}
