<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum Pathogen: string implements TranslatableInterface
{
    case SALMONELLA = 'SALMONELLA';
    case SHIGELLA = 'SHIGELLA';
    case YERSINIA_PESTIS = 'YERSINIA_PESTIS';
    case LISTERIA_MONOCYTOGENES = 'LISTERIA_MONOCYTOGENES';
    case VIBRIO_CHOLERAE = 'VIBRIO_CHOLERAE';
    case ESCHERICHIA_COLI = 'ESCHERICHIA_COLI';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, [], 'enum_pathogen');
    }
}
