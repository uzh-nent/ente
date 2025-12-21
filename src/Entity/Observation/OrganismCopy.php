<?php

namespace App\Entity\Observation;

use App\Enum\CodeSystem;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

trait OrganismCopy
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?CodeSystem $organismSystem = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $organismCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $organismDisplayName = null;
}
