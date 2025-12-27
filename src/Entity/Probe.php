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
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\ProbeProcessor;
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
use App\Enum\ProbeStatus;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    processor: ProbeProcessor::class,
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
#[ApiFilter(ExistsFilter::class, properties: ['finishedAt'])]
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
    private string $identifier = '';

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getReceivedDate(): ?\DateTimeImmutable
    {
        return $this->receivedDate;
    }

    public function setReceivedDate(?\DateTimeImmutable $receivedDate): void
    {
        $this->receivedDate = $receivedDate;
    }

    public function getAnalysisStartDate(): ?\DateTimeImmutable
    {
        return $this->analysisStartDate;
    }

    public function setAnalysisStartDate(?\DateTimeImmutable $analysisStartDate): void
    {
        $this->analysisStartDate = $analysisStartDate;
    }
}
