<?php

namespace App\Enum;

enum CodeSystem: string
{
    case LOINC = 'LOINC';
    case SNOMED = 'SNOMED';
    case NENT = 'NENT';
}
