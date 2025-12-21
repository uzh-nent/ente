<?php

namespace App\Entity\Probe;

use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

trait AnimalKeeperCopy
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $animalKeeperName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $animalKeeperGivenName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $animalKeeperFamilyName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $animalKeeperAddressLines = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $animalKeeperCity = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $animalKeeperPostalCode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $animalKeeperCountryCode = null;

    public function getAnimalKeeperName(): ?string
    {
        return $this->animalKeeperName;
    }

    public function setAnimalKeeperName(?string $animalKeeperName): void
    {
        $this->animalKeeperName = $animalKeeperName;
    }

    public function getAnimalKeeperGivenName(): ?string
    {
        return $this->animalKeeperGivenName;
    }

    public function setAnimalKeeperGivenName(?string $animalKeeperGivenName): void
    {
        $this->animalKeeperGivenName = $animalKeeperGivenName;
    }

    public function getAnimalKeeperFamilyName(): ?string
    {
        return $this->animalKeeperFamilyName;
    }

    public function setAnimalKeeperFamilyName(?string $animalKeeperFamilyName): void
    {
        $this->animalKeeperFamilyName = $animalKeeperFamilyName;
    }

    public function getAnimalKeeperAddressLines(): ?string
    {
        return $this->animalKeeperAddressLines;
    }

    public function setAnimalKeeperAddressLines(?string $animalKeeperAddressLines): void
    {
        $this->animalKeeperAddressLines = $animalKeeperAddressLines;
    }

    public function getAnimalKeeperCity(): ?string
    {
        return $this->animalKeeperCity;
    }

    public function setAnimalKeeperCity(?string $animalKeeperCity): void
    {
        $this->animalKeeperCity = $animalKeeperCity;
    }

    public function getAnimalKeeperPostalCode(): ?string
    {
        return $this->animalKeeperPostalCode;
    }

    public function setAnimalKeeperPostalCode(?string $animalKeeperPostalCode): void
    {
        $this->animalKeeperPostalCode = $animalKeeperPostalCode;
    }

    public function getAnimalKeeperCountryCode(): ?string
    {
        return $this->animalKeeperCountryCode;
    }

    public function setAnimalKeeperCountryCode(?string $animalKeeperCountryCode): void
    {
        $this->animalKeeperCountryCode = $animalKeeperCountryCode;
    }


}
