<?php

/*
 * This file is part of the baupen project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Entity\Probe\AnimalKeeperCopy;
use App\Entity\Probe\OrdererCopy;
use App\Entity\Probe\PatientCopy;
use App\Entity\Probe\SpecimenCopy;
use App\Entity\Traits\AddressTrait;
use App\Entity\Traits\AttributionTrait;
use App\Entity\Traits\CommentTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use App\Enum\LaboratoryFunction;
use App\Enum\Pathogen;
use App\Enum\SpecimenAnimalType;
use App\Enum\SpecimenFoodType;
use App\Enum\SpecimenSource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * An Email is a sent email to the specified receivers.
 */
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Probe
{
    use IdTrait;
    use TimeTrait;
    use AttributionTrait;
    use CommentTrait;

    use OrdererCopy;
    use AnimalKeeperCopy;
    use PatientCopy;
    use SpecimenCopy;

    // Orderer
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $ordererIdentifier = null;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    private ?Organization $orderer = null;

    // Service
    #[ORM\Column(type: Types::STRING, enumType: LaboratoryFunction::class, nullable: true)]
    private ?LaboratoryFunction $laboratoryFunction = null;

    #[ORM\Column(type: Types::STRING, enumType: Pathogen::class, nullable: true)]
    private ?Pathogen $pathogen = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $receivedAt = null;

    // Source
    // unknown, other => use specimenSourceText, specimenText, specimenTypeText, specimenLocation
    // laboratory_strain, feed, environment => use specimenText, specimenTypeText, specimenLocation
    // food => use specimenText, specimenFoodType, specimenTypeText, specimenLocation
    // animal => use specimenText, use specimenAnimalType, specimenTypeText, link animalKeeper
    // human => link specimen, use specimenText, use specimenIsolate, link patient
    //
    // shared logic: there is often structured options that can be selected (e.g. specimenAnimalType),
    // but there always needs to be a fallback freetext field (i.e. specimenText)
    // if the structured option is not set, and neither the fallback text, then this means "unknown"
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?SpecimenSource $specimenSource = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $specimenSourceText = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $specimenText = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $specimenTypeText = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $specimenLocation = null;

    #[ORM\Column(type: Types::STRING, enumType: SpecimenFoodType::class,nullable: true)]
    private ?SpecimenFoodType $specimenFoodType = null;

    #[ORM\Column(type: Types::STRING, enumType: SpecimenAnimalType::class, nullable: true)]
    private ?SpecimenAnimalType $specimenAnimalType = null;

    #[ORM\ManyToOne(targetEntity: AnimalKeeper::class)]
    private ?AnimalKeeper $animalKeeper = null;

    #[ORM\ManyToOne(targetEntity: Patient::class)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(targetEntity: Specimen::class)]
    private ?Specimen $specimen = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $specimenIsolate = null;

    public function getOrdererIdentifier(): ?string
    {
        return $this->ordererIdentifier;
    }

    public function setOrdererIdentifier(?string $ordererIdentifier): void
    {
        $this->ordererIdentifier = $ordererIdentifier;
    }

    public function getOrderer(): ?Organization
    {
        return $this->orderer;
    }

    public function setOrderer(?Organization $orderer): void
    {
        $this->orderer = $orderer;
    }

    public function getLaboratoryFunction(): ?LaboratoryFunction
    {
        return $this->laboratoryFunction;
    }

    public function setLaboratoryFunction(?LaboratoryFunction $laboratoryFunction): void
    {
        $this->laboratoryFunction = $laboratoryFunction;
    }

    public function getPathogen(): ?Pathogen
    {
        return $this->pathogen;
    }

    public function setPathogen(?Pathogen $pathogen): void
    {
        $this->pathogen = $pathogen;
    }

    public function getReceivedAt(): ?\DateTimeImmutable
    {
        return $this->receivedAt;
    }

    public function setReceivedAt(?\DateTimeImmutable $receivedAt): void
    {
        $this->receivedAt = $receivedAt;
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
