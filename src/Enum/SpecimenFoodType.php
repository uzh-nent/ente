<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum SpecimenFoodType : string implements TranslatableInterface
{
    case POULTRY = 'POULTRY'; // Geflügel
    case MEAT = 'MEAT'; // Fleisch
    case DAIRY = 'DAIRY'; // Milchprodukte
    case EGG = 'EGG';
    case FISH = 'FISH';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, [], 'enum_specimen_food_type');
    }
}
