<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum AnalysisType: string implements TranslatableInterface
{
    case IDENTIFICATION = "IDENTIFICATION";

    /**
     * synonyms: VTEC, EHEC = enterohämorrhagischer E.Coli
     * STEC is the dangerous kind which may damage the Nieren
     */
    case EC_STEC = "EC_STEC";
    case EC_EPEC = "EC_EPEC";
    case EC_ETEC = "EC_ETEC";
    case EC_EIEC = "EC_EIEC"; // genum close to shigella, hence may produce false-positives
    /**
     * synonym AEggEC
     * needs separate PCR test (while STEC, EPEC, ETEC, EIEC are done in the same test)
     */
    case EC_EAEC = "EC_EAEC";

    case VB_TOXIN = "VB_TOXIN"; // the toxin is evaluated separately and needs to be reported fast, because it is a major risk. it does not on its own allow to identify the organism.

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, [], 'enum_analysis_type');
    }
}
