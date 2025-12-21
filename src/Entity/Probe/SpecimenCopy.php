<?php

namespace App\Entity\Probe;

use App\Enum\CodeSystem;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

trait SpecimenCopy
{
    #[ORM\Column(type: Types::STRING, enumType: CodeSystem::class, nullable: true)]
    private ?CodeSystem $specimenSystem = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $specimenCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $specimenDisplayName = null;

    public function getSpecimenSystem(): ?CodeSystem
    {
        return $this->specimenSystem;
    }

    public function setSpecimenSystem(?CodeSystem $specimenSystem): void
    {
        $this->specimenSystem = $specimenSystem;
    }

    public function getSpecimenCode(): ?string
    {
        return $this->specimenCode;
    }

    public function setSpecimenCode(?string $specimenCode): void
    {
        $this->specimenCode = $specimenCode;
    }

    public function getSpecimenDisplayName(): ?string
    {
        return $this->specimenDisplayName;
    }

    public function setSpecimenDisplayName(?string $specimenDisplayName): void
    {
        $this->specimenDisplayName = $specimenDisplayName;
    }
}
