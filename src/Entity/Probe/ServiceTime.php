<?php

namespace App\Entity\Probe;

use ApiPlatform\Metadata\ApiProperty;
use App\Entity\User;
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

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?\DateTimeImmutable $analysisStopDate = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?\DateTimeImmutable $closedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Groups(['probe:read'])] // written in probe processor
    #[ApiProperty(readableLink: false, writableLink: false)]
    private ?User $closedBy = null;

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

    public function getAnalysisStopDate(): ?\DateTimeImmutable
    {
        return $this->analysisStopDate;
    }

    public function setAnalysisStopDate(?\DateTimeImmutable $analysisStopDate): void
    {
        $this->analysisStopDate = $analysisStopDate;
    }

    public function getClosedAt(): ?\DateTimeImmutable
    {
        return $this->closedAt;
    }

    public function setClosedAt(?\DateTimeImmutable $closedAt): void
    {
        $this->closedAt = $closedAt;
    }

    public function getClosedBy(): ?User
    {
        return $this->closedBy;
    }

    public function setClosedBy(?User $closedBy): void
    {
        $this->closedBy = $closedBy;
    }
}
