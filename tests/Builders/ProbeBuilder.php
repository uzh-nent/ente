<?php

namespace App\Tests\Builders;

use App\Entity\ElmReport;
use App\Entity\Probe;
use App\Entity\Specimen;
use App\Enum\AnalysisType;
use App\Enum\LaboratoryFunction;
use App\Enum\Pathogen;
use App\Enum\SpecimenSource;
use phpDocumentor\Reflection\Types\This;

/**
 * @extends AbstractBuilder<Probe>
 */
class ProbeBuilder extends AbstractBuilder
{
    public function __construct()
    {
        $probe = new Probe();
        $probe->setReceivedDate(new \DateTimeImmutable());
        $probe->setAnalysisStartDate(new \DateTimeImmutable());

        parent::__construct($probe);
    }

    public function withReferenceLaboratoryOrder(): self
    {
        $this->entity->setLaboratoryFunction(LaboratoryFunction::REFERENCE);
        $this->entity->setIdentifier("N26-0057");
        $this->entity->setPathogen(Pathogen::SALMONELLA);
        $this->entity->setAnalysisTypes([AnalysisType::IDENTIFICATION]);

        $organization = new OrganizationBuilder()->build();
        $this->entity->setRequisitionIdentifier('R-12341234');
        $this->entity->setOrdererOrg($organization);
        $this->entity->copyOrdererOrgFrom($organization);

        return $this;
    }

    public function withHumanProbe(?Specimen $specimen = null): self
    {
        $this->entity->setSpecimenCollectionDate(new \DateTimeImmutable());
        $this->entity->setSpecimenSource(SpecimenSource::HUMAN);
        $this->entity->setSpecimen($specimen);

        $patient = new PatientBuilder()->build();
        $this->entity->setPatient($patient);
        $this->entity->copyPatientFrom($patient);

        return $this;
    }
}
