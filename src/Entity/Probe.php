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
use App\Entity\Probe\ServiceRequest;
use App\Entity\Probe\ServiceTime;
use App\Entity\Probe\SpecimenMeta;
use App\Entity\Traits\AttributionTrait;
use App\Entity\Traits\CommentTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    processor: ProbeProcessor::class,
    normalizationContext: ['groups' => ['time:read', 'attribution:read', 'orderer:read', 'animal-keeper:read', 'patient:read', 'comment:read', 'probe:read']],
    denormalizationContext: ['groups' => ['orderer:write', 'animal-keeper:write', 'patient:write', 'comment:write', 'probe:write']]
)]
#[Get]
#[Post]
#[Patch]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: [
    'identifier' => SearchFilterInterface::STRATEGY_ISTART, 'requisitionIdentifier' => SearchFilterInterface::STRATEGY_ISTART,
    'pathogen' => SearchFilterInterface::STRATEGY_EXACT,
    'orderer' => SearchFilterInterface::STRATEGY_EXACT, 'patient' => SearchFilterInterface::STRATEGY_EXACT, 'animalKeeper' => SearchFilterInterface::STRATEGY_EXACT, 'practitioner' => SearchFilterInterface::STRATEGY_EXACT,
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

    /**
     * @var Collection<int, Observation>
     */
    #[Groups(['probe:collections'])]
    #[ORM\OneToMany(targetEntity: Observation::class, mappedBy: 'probe')]
    private Collection $observations;

    public function __construct()
    {
        $this->observations = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Observation>
     */
    public function getObservations(): Collection
    {
        return $this->observations;
    }
}
