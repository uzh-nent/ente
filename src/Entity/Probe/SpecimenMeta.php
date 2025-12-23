<?php

namespace App\Entity\Probe;

use App\Entity\AnimalKeeper;
use App\Entity\Patient;
use App\Entity\Specimen;
use App\Enum\SpecimenAnimalType;
use App\Enum\SpecimenFoodType;
use App\Enum\SpecimenSource;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ORM\Mapping as ORM;

trait SpecimenMeta
{
    use AnimalKeeperCopy;
    use PatientCopy;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?\DateTimeImmutable $specimenDate = null;

    // unknown, other => use specimenSourceText, specimenText, specimenTypeText, specimenLocation
    // laboratory_strain, feed, environment => use specimenText, specimenTypeText, specimenLocation
    // food => use specimenText, specimenFoodType, specimenTypeText, specimenLocation
    // animal => use specimenText, use specimenAnimalType, specimenTypeText, link animalKeeper, use animalName
    // human => link specimen, use specimenText, use specimenIsolate, link patient
    //
    // shared logic: there is often structured options that can be selected (e.g. specimenAnimalType),
    // but there always needs to be a fallback freetext field (i.e. specimenText)
    // if the structured option is not set, and neither the fallback text, then this means "unknown"
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?SpecimenSource $specimenSource = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?string $specimenSourceText = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?string $specimenText = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?string $specimenTypeText = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?string $specimenLocation = null;

    #[ORM\Column(type: Types::STRING, enumType: SpecimenFoodType::class, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?SpecimenFoodType $specimenFoodType = null;

    #[ORM\Column(type: Types::STRING, enumType: SpecimenAnimalType::class, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?SpecimenAnimalType $specimenAnimalType = null;

    #[ORM\ManyToOne(targetEntity: AnimalKeeper::class, inversedBy: 'probes')]
    #[Groups(['probe:read', 'probe:write'])]
    private ?AnimalKeeper $animalKeeper = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?string $animalName = null;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'probes')]
    #[Groups(['probe:read', 'probe:write'])]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(targetEntity: Specimen::class, inversedBy: 'probes')]
    #[Groups(['probe:read', 'probe:write'])]
    private ?Specimen $specimen = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?bool $specimenIsolate = null;

    public function getSpecimenDate(): ?\DateTimeImmutable
    {
        return $this->specimenDate;
    }

    public function setSpecimenDate(?\DateTimeImmutable $specimenDate): void
    {
        $this->specimenDate = $specimenDate;
    }

    public function getSpecimenSource(): ?SpecimenSource
    {
        return $this->specimenSource;
    }

    public function setSpecimenSource(?SpecimenSource $specimenSource): void
    {
        $this->specimenSource = $specimenSource;
    }

    public function getSpecimenSourceText(): ?string
    {
        return $this->specimenSourceText;
    }

    public function setSpecimenSourceText(?string $specimenSourceText): void
    {
        $this->specimenSourceText = $specimenSourceText;
    }

    public function getSpecimenText(): ?string
    {
        return $this->specimenText;
    }

    public function setSpecimenText(?string $specimenText): void
    {
        $this->specimenText = $specimenText;
    }

    public function getSpecimenTypeText(): ?string
    {
        return $this->specimenTypeText;
    }

    public function setSpecimenTypeText(?string $specimenTypeText): void
    {
        $this->specimenTypeText = $specimenTypeText;
    }

    public function getSpecimenLocation(): ?string
    {
        return $this->specimenLocation;
    }

    public function setSpecimenLocation(?string $specimenLocation): void
    {
        $this->specimenLocation = $specimenLocation;
    }

    public function getSpecimenFoodType(): ?SpecimenFoodType
    {
        return $this->specimenFoodType;
    }

    public function setSpecimenFoodType(?SpecimenFoodType $specimenFoodType): void
    {
        $this->specimenFoodType = $specimenFoodType;
    }

    public function getSpecimenAnimalType(): ?SpecimenAnimalType
    {
        return $this->specimenAnimalType;
    }

    public function setSpecimenAnimalType(?SpecimenAnimalType $specimenAnimalType): void
    {
        $this->specimenAnimalType = $specimenAnimalType;
    }

    public function getAnimalKeeper(): ?AnimalKeeper
    {
        return $this->animalKeeper;
    }

    public function setAnimalKeeper(?AnimalKeeper $animalKeeper): void
    {
        $this->animalKeeper = $animalKeeper;
    }

    public function getAnimalName(): ?string
    {
        return $this->animalName;
    }

    public function setAnimalName(?string $animalName): void
    {
        $this->animalName = $animalName;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): void
    {
        $this->patient = $patient;
    }

    public function getSpecimen(): ?Specimen
    {
        return $this->specimen;
    }

    public function setSpecimen(?Specimen $specimen): void
    {
        $this->specimen = $specimen;
    }

    public function getSpecimenIsolate(): ?bool
    {
        return $this->specimenIsolate;
    }

    public function setSpecimenIsolate(?bool $specimenIsolate): void
    {
        $this->specimenIsolate = $specimenIsolate;
    }
}
