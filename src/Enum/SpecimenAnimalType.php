<?php

namespace App\Enum;

enum SpecimenAnimalType : string
{
    case CATTLE = 'CATTLE'; // Rind
    case PIG = 'PIG'; // Schwein
    case CHICKEN = 'CHICKEN'; // Huhn
    case BIRD = 'BIRD'; // Vogel
    case REPTILIAN = 'REPTILIAN'; // Reptil
}
