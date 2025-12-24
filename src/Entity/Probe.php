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
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Entity\Probe\AnimalKeeperCopy;
use App\Entity\Probe\OrdererCopy;
use App\Entity\Probe\PatientCopy;
use App\Entity\Probe\ServiceRequest;
use App\Entity\Probe\ServiceTime;
use App\Entity\Probe\SpecimenMeta;
use App\Entity\Traits\AttributionTrait;
use App\Entity\Traits\CommentTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use App\Enum\LaboratoryFunction;
use App\Enum\Pathogen;
use App\Enum\ProbeStatus;
use App\Enum\SpecimenAnimalType;
use App\Enum\SpecimenFoodType;
use App\Enum\SpecimenSource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ['groups' => ['orderer:write', 'animal-keeper:write', 'patient:write', 'comment:write', 'probe:write']],
    denormalizationContext: ['groups' => ['attribution:read', 'orderer:read', 'animal-keeper:read', 'patient:read', 'comment:read', 'probe:read']]
)]
#[Get]
#[Post]
#[Patch]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: [
    'identifier' => SearchFilterInterface::STRATEGY_IPARTIAL, 'ordererIdentifier' => SearchFilterInterface::STRATEGY_IPARTIAL,
    'pathogen' => SearchFilterInterface::STRATEGY_EXACT,
    'orderer' => SearchFilterInterface::STRATEGY_EXACT, 'patient' => SearchFilterInterface::STRATEGY_EXACT, 'animalKeeper' => SearchFilterInterface::STRATEGY_EXACT,
])]
#[ApiFilter(OrderFilter::class, properties: ['identifier'])]
class Probe
{
    use IdTrait;
    use TimeTrait;
    use AttributionTrait;
    use CommentTrait;

    use ServiceRequest;
    use SpecimenMeta;
    use ServiceTime;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Groups(['probe:read'])]
    private ?string $identifier = null;

    #[ORM\Column(type: Types::STRING, enumType: ProbeStatus::class, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?ProbeStatus $status = null;

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getStatus(): ?ProbeStatus
    {
        return $this->status;
    }

    public function setStatus(?ProbeStatus $status): void
    {
        $this->status = $status;
    }

    public function getReceivedAt(): ?\DateTimeImmutable
    {
        return $this->receivedAt;
    }

    public function setReceivedAt(?\DateTimeImmutable $receivedAt): void
    {
        $this->receivedAt = $receivedAt;
    }

    public function getAnalysisStartAt(): ?\DateTimeImmutable
    {
        return $this->analysisStartAt;
    }

    public function setAnalysisStartAt(?\DateTimeImmutable $analysisStartAt): void
    {
        $this->analysisStartAt = $analysisStartAt;
    }
}
