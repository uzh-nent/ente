<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatorInterface;

enum AdministrativeGender : string
{
    case MALE = 'MALE';
    case FEMALE = 'FEMALE';
    case OTHER = 'OTHER';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, [], 'enum_administrative_gender');
    }
}
