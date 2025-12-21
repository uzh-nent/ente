<?php

namespace App\Entity\Observation;

use App\Enum\CodeSystem;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

trait InterpretationCopy
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?CodeSystem $interpretationSystem = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $interpretationCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $interpretationDisplayName = null;
}
