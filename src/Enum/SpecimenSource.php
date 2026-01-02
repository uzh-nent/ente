<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum SpecimenSource : string implements TranslatableInterface
{
    case HUMAN = 'HUMAN';
    case ANIMAL = 'ANIMAL';
    case FOOD = 'FOOD'; // Lebensmittel
    case FEED = 'FEED'; // Futtermittel
    case ENVIRONMENT = 'ENVIRONMENT';
    case LABORATORY_STRAIN = 'LABORATORY_STRAIN'; // Laborstamm

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, [], 'enum_specimen_source');
    }
}
