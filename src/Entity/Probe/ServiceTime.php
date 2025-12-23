<?php

namespace App\Entity\Probe;

use App\Entity\Organization;
use App\Enum\AnalysisType;
use App\Enum\LaboratoryFunction;
use App\Enum\Pathogen;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait ServiceTime
{
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?\DateTimeImmutable $receivedAt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $analysisStartAt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?\DateTimeImmutable $finishedAt = null;

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

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeImmutable $finishedAt): void
    {
        $this->finishedAt = $finishedAt;
    }


}
