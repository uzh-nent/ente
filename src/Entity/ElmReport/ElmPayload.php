<?php

namespace App\Entity\ElmReport;

use App\Entity\LeadingCode;
use App\Entity\Organism;
use App\Entity\Specimen;
use App\Enum\Interpretation;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait ElmPayload
{
    #[ORM\ManyToOne(targetEntity: LeadingCode::class)]
    private ?LeadingCode $leadingCode = null;

    #[ORM\ManyToOne(targetEntity: Organism::class)]
    private ?Organism $organism = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $organismText = null;

    #[ORM\ManyToOne(targetEntity: Specimen::class)]
    private ?Specimen $specimen = null;

    #[ORM\Column(type: Types::STRING, enumType: Interpretation::class, nullable: true)]
    private ?Interpretation $interpretation = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $effectiveAt = null;

    public function getLeadingCode(): LeadingCode
    {
        return $this->leadingCode;
    }

    public function setLeadingCode(LeadingCode $leadingCode): void
    {
        $this->leadingCode = $leadingCode;
    }

    public function getOrganism(): ?Organism
    {
        return $this->organism;
    }

    public function setOrganism(?Organism $organism): void
    {
        $this->organism = $organism;
    }

    public function getOrganismText(): ?string
    {
        return $this->organismText;
    }

    public function setOrganismText(?string $organismText): void
    {
        $this->organismText = $organismText;
    }

    public function getSpecimen(): ?Specimen
    {
        return $this->specimen;
    }

    public function setSpecimen(?Specimen $specimen): void
    {
        $this->specimen = $specimen;
    }

    public function getInterpretation(): ?Interpretation
    {
        return $this->interpretation;
    }

    public function setInterpretation(?Interpretation $interpretation): void
    {
        $this->interpretation = $interpretation;
    }

    public function getEffectiveAt(): ?\DateTimeImmutable
    {
        return $this->effectiveAt;
    }

    public function setEffectiveAt(?\DateTimeImmutable $effectiveAt): void
    {
        $this->effectiveAt = $effectiveAt;
    }
}
