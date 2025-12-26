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

use ApiPlatform\Doctrine\Common\Filter\SearchFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Entity\Traits\CodedIdentifierTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use App\Enum\InterpretationGroup;
use App\Enum\Pathogen;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    denormalizationContext: ['groups' => ['coded-identifier:read', 'leading-code:read']],
    paginationEnabled: false
)]
#[Get]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: ['pathogen' => SearchFilterInterface::STRATEGY_EXACT])]
#[ApiFilter(OrderFilter::class, properties: ['displayName'])]
class LeadingCode
{
    use IdTrait;
    use TimeTrait;
    use CodedIdentifierTrait;

    #[ORM\Column(type: Types::STRING, enumType: Pathogen::class, nullable: true)]
    #[Groups(['leading-code:read'])]
    private ?Pathogen $pathogen = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['leading-code:read'])]
    private ?string $organismGroup = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['leading-code:read'])]
    private ?string $specimenGroup = null;

    #[ORM\Column(type: Types::STRING, enumType: InterpretationGroup::class, nullable: true)]
    #[Groups(['leading-code:read'])]
    private ?InterpretationGroup $interpretationGroup = null;

    #[ORM\ManyToOne(targetEntity: Specimen::class)]
    #[Groups(['leading-code:read'])]
    #[ApiProperty(readableLink: false, writableLink: false)]
    private ?Specimen $specimen = null;

    public function getPathogen(): ?Pathogen
    {
        return $this->pathogen;
    }

    public function setPathogen(?Pathogen $pathogen): void
    {
        $this->pathogen = $pathogen;
    }

    public function getOrganismGroup(): ?string
    {
        return $this->organismGroup;
    }

    public function setOrganismGroup(?string $organismGroup): void
    {
        $this->organismGroup = $organismGroup;
    }

    public function getSpecimenGroup(): ?string
    {
        return $this->specimenGroup;
    }

    public function setSpecimenGroup(?string $specimenGroup): void
    {
        $this->specimenGroup = $specimenGroup;
    }

    public function getInterpretationGroup(): ?InterpretationGroup
    {
        return $this->interpretationGroup;
    }

    public function setInterpretationGroup(?InterpretationGroup $interpretationGroup): void
    {
        $this->interpretationGroup = $interpretationGroup;
    }

    public function getSpecimen(): ?Specimen
    {
        return $this->specimen;
    }

    public function setSpecimen(?Specimen $specimen): void
    {
        $this->specimen = $specimen;
    }

    public function isDuplicateOf(LeadingCode $other): bool
    {
        return $this->getSystem() === $other->getSystem() && $this->getCode() === $other->getCode();
    }
}
