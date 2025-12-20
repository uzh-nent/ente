<?php

namespace App\Entity\Probe;

use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

trait PatientCopy
{
    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTime $patientBirthDate = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $patientAhvNumber = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $patientGivenName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $patientFamilyName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $patientAddressLines = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $patientCity = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $patientPostalCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $patientCountryCode = null;
}
