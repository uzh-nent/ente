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

use App\Entity\Traits\AttributionTrait;
use App\Entity\Traits\CommentTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimeTrait;
use App\Enum\AnalysisType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Observation
{
    use IdTrait;
    use TimeTrait;
    use AttributionTrait;
    use CommentTrait;

    #[ORM\Column(type: Types::STRING, enumType: AnalysisType::class)]
    private ?AnalysisType $analysisType = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $analysisStartAt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $analysisStopAt = null;

    #[ORM\ManyToOne(targetEntity: Interpretation::class)]
    private ?Interpretation $interpretation = null;

    #[ORM\ManyToOne(targetEntity: Organism::class)]
    private ?Organism $organism = null;

    public function getAnalysisType(): ?AnalysisType
    {
        return $this->analysisType;
    }

    public function setAnalysisType(?AnalysisType $analysisType): void
    {
        $this->analysisType = $analysisType;
    }

    public function getAnalysisStartAt(): ?\DateTimeImmutable
    {
        return $this->analysisStartAt;
    }

    public function setAnalysisStartAt(?\DateTimeImmutable $analysisStartAt): void
    {
        $this->analysisStartAt = $analysisStartAt;
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
}
