<?php

namespace App\Entity\Probe;

use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

trait OrdererCopy
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $ordererName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $ordererAddressLines = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $ordererCity = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $orderPostalCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $ordererCountryCode = null;
}
