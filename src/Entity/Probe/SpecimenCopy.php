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
}
