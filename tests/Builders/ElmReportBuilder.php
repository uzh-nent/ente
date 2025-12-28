<?php

namespace App\Tests\Builders;

use App\Entity\ElmReport;
use App\Entity\Probe;
use App\Enum\Interpretation;
use App\Tests\Helpers\CodeRepository;

/**
 * @extends AbstractBuilder<ElmReport>
 */
class ElmReportBuilder extends AbstractBuilder
{
    public function __construct()
    {
        $elmReport = new ElmReport();
        $elmReport->setId('00000000-9f74-4985-9acb-e6aa25661169');
        $elmReport->setEffectiveAt(new \DateTimeImmutable());

        parent::__construct($elmReport);
    }

    public function withProbe(Probe $probe): self
    {
        $this->entity->setProbe($probe);

        return $this;
    }

    public function withSalmonellaComplete(): self
    {
        $this->entity->setLeadingCode(CodeRepository::getLeadingCodeSalSerovar());
        $this->entity->setSpecimen(CodeRepository::getSpecimenMicrobial());
        $this->entity->setOrganism(CodeRepository::getOrganismSalComplete());
        $this->entity->setInterpretation(Interpretation::POS);

        return $this;
    }
}
