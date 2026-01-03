<?php

namespace App\Entity\Probe;

use App\Entity\Patient;
use App\Enum\AdministrativeGender;
use App\Services\Elm\ApiBuilder\Dto\AddressDto;
use App\Services\Elm\ApiBuilder\Dto\PersonDto;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait PatientCopy
{
    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?\DateTimeImmutable $patientBirthDate = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $patientAhvNumber = null;

    #[ORM\Column(type: Types::TEXT, enumType: AdministrativeGender::class, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?AdministrativeGender $patientGender = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $patientGivenName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $patientFamilyName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $patientAddressLines = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $patientCity = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $patientPostalCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['patient:read', 'patient:write'])]
    private ?string $patientCountryCode = null;

    public function getPatientBirthDate(): ?\DateTimeImmutable
    {
        return $this->patientBirthDate;
    }

    public function setPatientBirthDate(?\DateTimeImmutable $patientBirthDate): void
    {
        $this->patientBirthDate = $patientBirthDate;
    }

    public function getPatientAhvNumber(): ?string
    {
        return $this->patientAhvNumber;
    }

    public function getPatientAhvNumberFormatted(): ?string
    {
        if (!$this->patientAhvNumber || strlen($this->patientAhvNumber) !== 13) {
            return $this->patientAhvNumber;
        }

        return substr($this->patientAhvNumber, 0, 3) . '.' . substr($this->patientAhvNumber, 3, 4) . '.' . substr($this->patientAhvNumber, 7, 4) . '.' . substr($this->patientAhvNumber, 11);
    }

    public function setPatientAhvNumber(?string $patientAhvNumber): void
    {
        $this->patientAhvNumber = $patientAhvNumber;
    }

    public function getPatientGender(): ?AdministrativeGender
    {
        return $this->patientGender;
    }

    public function setPatientGender(?AdministrativeGender $patientGender): void
    {
        $this->patientGender = $patientGender;
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

    public function copyPatientFrom(Patient $patient): void
    {
        $this->patientBirthDate = $patient->getBirthDate();
        $this->patientAhvNumber = $patient->getAhvNumber();
        $this->patientGender = $patient->getGender();
        $this->patientGivenName = $patient->getGivenName();
        $this->patientFamilyName = $patient->getFamilyName();
        $this->patientAddressLines = $patient->getAddressLines();
        $this->patientCountryCode = $patient->getCountryCode();
        $this->patientCity = $patient->getCity();
        $this->patientPostalCode = $patient->getPostalCode();
    }

    public function writePatientAddressTo(AddressDto $target): void
    {
        $target->setAddressLines($this->patientAddressLines);
        $target->setCity($this->patientCity);
        $target->setPostalCode($this->patientPostalCode);
        $target->setCountryCode($this->patientCountryCode);
    }

    public function writePatientPersonTo(PersonDto $target): void
    {
        $target->setGivenName($this->patientGivenName);
        $target->setFamilyName($this->patientFamilyName);
    }

    public function getPatientFullAddress(?callable $genderTranslator): string
    {
        $fullName = trim($this->getPatientGivenName() . " " . $this->getPatientFamilyName());
        if ($genderTranslator && $this->getPatientGender()) {
            $fullName .= " (" . $genderTranslator($this->getPatientGender()) . ")";
        }

        $countryPrefix = $this->getPatientCountryCode() === 'CH' ? "" : $this->getPatientCountryCode() . " ";
        $city = trim($this->getPatientPostalCode() . " " . $this->getPatientCity());

        return join("\n", array_filter([
            $fullName,
            $this->getPatientAddressLines(),
            $countryPrefix . $city
        ]));
    }
}
