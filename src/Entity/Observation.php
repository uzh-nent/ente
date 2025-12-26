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
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\ProbeProcessor;
use App\Entity\Traits\AttributionTrait;
use App\Entity\Traits\CommentTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use App\Enum\AnalysisType;
use App\Enum\Interpretation;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    processor: ProbeProcessor::class,
    normalizationContext: ['groups' => ['comment:write', 'observation:write']],
    denormalizationContext: ['groups' => ['time:read', 'attribution:read', 'comment:read', 'observation:read']]
)]
#[Get]
#[Post]
#[Patch]
#[GetCollection]
#[ApiFilter(SearchFilter::class, properties: ['probe' => SearchFilterInterface::STRATEGY_EXACT])]
#[ApiFilter(OrderFilter::class, properties: ['analysisStopAt'])]
class Observation
{
    use IdTrait;
    use TimeTrait;
    use AttributionTrait;
    use CommentTrait;

    #[ORM\Column(type: Types::STRING, enumType: AnalysisType::class)]
    #[Groups(['observation:read', 'observation:write'])]
    private ?AnalysisType $analysisType = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['observation:read', 'observation:write'])]
    private ?\DateTimeImmutable $analysisStopAt = null;

    #[ORM\Column(type: Types::STRING, enumType: Interpretation::class, nullable: true)]
    #[Groups(['observation:read', 'observation:write'])]
    private ?Interpretation $interpretation = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['observation:read', 'observation:write'])]
    private ?string $interpretationText = null;

    #[ORM\ManyToOne(targetEntity: Organism::class)]
    #[ApiProperty(readableLink: false, writableLink: false)]
    #[Groups(['observation:read', 'observation:write'])]
    private ?Organism $organism = null;

    #[ORM\ManyToOne(targetEntity: Probe::class)]
    #[ApiProperty(readableLink: false, writableLink: false)]
    #[Groups(['observation:read', 'observation:write'])]
    private ?Probe $probe = null;

    public function getAnalysisType(): ?AnalysisType
    {
        return $this->analysisType;
    }

    public function setAnalysisType(?AnalysisType $analysisType): void
    {
        $this->analysisType = $analysisType;
    }

    public function getAnalysisStopAt(): ?\DateTimeImmutable
    {
        return $this->analysisStopAt;
    }

    public function setAnalysisStopAt(?\DateTimeImmutable $analysisStopAt): void
    {
        $this->analysisStopAt = $analysisStopAt;
    }

    public function getInterpretation(): ?Interpretation
    {
        return $this->interpretation;
    }

    public function setInterpretation(?Interpretation $interpretation): void
    {
        $this->interpretation = $interpretation;
    }

    public function getOrganism(): ?Organism
    {
        return $this->organism;
    }

    public function setOrganism(?Organism $organism): void
    {
        $this->organism = $organism;
    }

    public function getProbe(): ?Probe
    {
        return $this->probe;
    }

    public function setProbe(?Probe $probe): void
    {
        $this->probe = $probe;
    }
}
