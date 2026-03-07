<?php

namespace App\Tests\Builders;

use App\Entity\Observation;
use App\Entity\Probe;
use App\Enum\Interpretation;

/**
 * @extends AbstractBuilder<Observation>
 */
class ObservationBuilder extends AbstractBuilder
{
    public function __construct(Probe $probe)
    {
        $observation = new Observation();
        $observation->setProbe($probe);
        $observation->setPathogen($probe->getPathogen());
        $observation->setPathogenName($probe->getPathogenName());
        $observation->setAnalysisType($probe->getAnalysisTypes()[0]);
        $observation->setInterpretation(Interpretation::POS);
        $observation->setEffectiveAt(new \DateTimeImmutable());

        parent::__construct($observation);
    }
}
