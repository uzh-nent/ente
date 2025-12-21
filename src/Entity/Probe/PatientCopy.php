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

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $patientAddressLines = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $patientCity = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $patientPostalCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $patientCountryCode = null;

    public function getPatientBirthDate(): ?\DateTime
    {
        return $this->patientBirthDate;
    }

    public function setPatientBirthDate(?\DateTime $patientBirthDate): void
    {
        $this->patientBirthDate = $patientBirthDate;
    }

    public function getPatientAhvNumber(): ?string
    {
        return $this->patientAhvNumber;
    }

    public function setPatientAhvNumber(?string $patientAhvNumber): void
    {
        $this->patientAhvNumber = $patientAhvNumber;
    }

    public function getPatientGivenName(): ?string
    {
        return $this->patientGivenName;
    }

    public function setPatientGivenName(?string $patientGivenName): void
    {
        $this->patientGivenName = $patientGivenName;
    }

    public function getPatientFamilyName(): ?string
    {
        return $this->patientFamilyName;
    }

    public function setPatientFamilyName(?string $patientFamilyName): void
    {
        $this->patientFamilyName = $patientFamilyName;
    }

    public function getPatientAddressLines(): ?string
    {
        return $this->patientAddressLines;
    }

    public function setPatientAddressLines(?string $patientAddressLines): void
    {
        $this->patientAddressLines = $patientAddressLines;
    }

    public function getPatientCity(): ?string
    {
        return $this->patientCity;
    }

    public function setPatientCity(?string $patientCity): void
    {
        $this->patientCity = $patientCity;
    }

    public function getPatientPostalCode(): ?string
    {
        return $this->patientPostalCode;
    }

    public function setPatientPostalCode(?string $patientPostalCode): void
    {
        $this->patientPostalCode = $patientPostalCode;
    }

    public function getPatientCountryCode(): ?string
    {
        return $this->patientCountryCode;
    }

    public function setPatientCountryCode(?string $patientCountryCode): void
    {
        $this->patientCountryCode = $patientCountryCode;
    }
}
