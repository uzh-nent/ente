<?php

namespace App\Entity\Observation;

use App\Enum\CodeSystem;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

trait OrganismCopy
{
    #[ORM\Column(type: Types::STRING, enumType: CodeSystem::class, nullable: true)]
    private ?CodeSystem $organismSystem = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $organismCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $organismDisplayName = null;

    public function getOrganismSystem(): ?CodeSystem
    {
        return $this->organismSystem;
    }

    public function setOrganismSystem(?CodeSystem $organismSystem): void
    {
        $this->organismSystem = $organismSystem;
    }

    public function getOrganismCode(): ?string
    {
        return $this->organismCode;
    }

    public function setOrganismCode(?string $organismCode): void
    {
        $this->organismCode = $organismCode;
    }

    public function getOrganismDisplayName(): ?string
    {
        return $this->organismDisplayName;
    }

    public function setOrganismDisplayName(?string $organismDisplayName): void
    {
        $this->organismDisplayName = $organismDisplayName;
    }
}
