<?php

namespace App\Entity\Probe;

use ApiPlatform\Metadata\ApiProperty;
use App\Entity\Organization;
use App\Entity\User;
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
    private ?\DateTimeImmutable $receivedDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?\DateTimeImmutable $analysisStartDate = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?\DateTimeImmutable $finishedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['probe:read'])] // written in probe processor
    #[ApiProperty(readableLink: false, writableLink: false)]
    private ?User $finishedBy = null;

    public function getReceivedDate(): \DateTimeImmutable
    {
        return $this->receivedDate;
    }

    public function setReceivedDate(\DateTimeImmutable $receivedDate): void
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

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeImmutable $finishedAt): void
    {
        $this->finishedAt = $finishedAt;
    }

    public function getFinishedBy(): ?User
    {
        return $this->finishedBy;
    }

    public function setFinishedBy(?User $finishedBy): void
    {
        $this->finishedBy = $finishedBy;
    }
}
