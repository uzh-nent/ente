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

    use OrdererCopy;
    use AnimalKeeperCopy;
    use PatientCopy;
    use SpecimenCopy;

    #[ORM\Column(type: Types::STRING, enumType: LaboratoryFunction::class, nullable: true)]
    private ?LaboratoryFunction $laboratoryFunction = null;

    // Orderer
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $ordererIdentifier = null;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    private ?Organization $orderer = null;

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

    // Analysis
    #[ORM\Column(type: Types::STRING, enumType: Pathogen::class, nullable: true)]
    private ?Pathogen $pathogen = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $receivedAt = null;
}
