<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum SpecimenAnimalType : string implements TranslatableInterface
{
    case CATTLE = 'CATTLE'; // Rind
    case PIG = 'PIG'; // Schwein
    case CHICKEN = 'CHICKEN'; // Huhn
    case BIRD = 'BIRD'; // Vogel
    case REPTILIAN = 'REPTILIAN'; // Reptil

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, [], 'enum_specimen_animal_type');
    }
}
