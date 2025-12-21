<?php

namespace App\Enum;

enum SpecimenFoodType : string
{
    case POULTRY = 'POULTRY'; // Geflügel
    case MEAT = 'ANIMAL'; // Fleisch
    case DAIRY = 'DAIRY'; // Milchprodukte
    case EGG = 'EGG'; // Eiprodukte
    case FISH = 'FISH';
}
