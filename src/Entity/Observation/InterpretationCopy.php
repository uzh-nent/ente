<?php

namespace App\Entity\Observation;

use App\Enum\CodeSystem;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

trait InterpretationCopy
{
    #[ORM\Column(type: Types::STRING, enumType: CodeSystem::class, nullable: true)]
    private ?CodeSystem $interpretationSystem = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $interpretationCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $interpretationDisplayName = null;

    public function getInterpretationSystem(): ?CodeSystem
    {
        return $this->interpretationSystem;
    }

    public function setInterpretationSystem(?CodeSystem $interpretationSystem): void
    {
        $this->interpretationSystem = $interpretationSystem;
    }

    public function getInterpretationCode(): ?string
    {
        return $this->interpretationCode;
    }

    public function setInterpretationCode(?string $interpretationCode): void
    {
        $this->interpretationCode = $interpretationCode;
    }

    public function getInterpretationDisplayName(): ?string
    {
        return $this->interpretationDisplayName;
    }

    public function setInterpretationDisplayName(?string $interpretationDisplayName): void
    {
        $this->interpretationDisplayName = $interpretationDisplayName;
    }
}
