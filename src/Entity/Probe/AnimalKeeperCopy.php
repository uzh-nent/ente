<?php

namespace App\Entity\Probe;

use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

trait AnimalKeeperCopy
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $animalKeeperGivenName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $animalKeeperFamilyName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $animalKeeperAddressLines = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $animalKeeperCity = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $animalKeeperPostalCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $animalKeeperCountryCode = null;
}
