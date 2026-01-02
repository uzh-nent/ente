<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AnalysisType: string implements TranslatableInterface
{
    case IDENTIFICATION = "IDENTIFICATION";

    /**
     * synonyms: VTEC, EHEC = enterohämorrhagischer E.Coli
     * STEC is the dangerous kind which may damager the Nieren
     */
    case STEC = "EC_STEC";
    case EPEC = "EC_EPEC";
    case ETEC = "EC_ETEC";
    case EIEC = "EC_EIEC"; // genum close to shigella, hence may produce false-positives

    /**
     * synonym AEggEC
     * needs separate PCR test (while STEC, EPEC, ETEC, EIEC are done in the same test)
     */
    case EAEC = "EC_EAEC";

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, [], 'enum_analysis_type');
    }
}
