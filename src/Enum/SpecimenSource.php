<?php

namespace App\Enum;

enum SpecimenSource : string
{
    case HUMAN = 'HUMAN';
    case ANIMAL = 'ANIMAL';
    case FOOD = 'FOOD'; // Lebensmittel
    case FEED = 'FEED'; // Futtermittel
    case ENVIRONMENT = 'ENVIRONMENT';
    case LABORATORY_STRAIN = 'LABORATORY_STRAIN'; // Laborstamm
}
