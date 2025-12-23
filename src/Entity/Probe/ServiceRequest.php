<?php

namespace App\Entity\Probe;

use App\Entity\Organization;
use App\Enum\LaboratoryFunction;
use App\Enum\Pathogen;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait ServiceRequest
{
    // Service
    #[ORM\Column(type: Types::STRING, enumType: LaboratoryFunction::class, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?LaboratoryFunction $laboratoryFunction = null;

    #[ORM\Column(type: Types::STRING, enumType: Pathogen::class, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?Pathogen $pathogen = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?string $pathogenName = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?\DateTimeImmutable $receivedAt = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?string $ordererIdentifier = null;

    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'probes')]
    #[Groups(['probe:read', 'probe:write'])]
    private ?Organization $orderer = null;

    public function getLaboratoryFunction(): ?LaboratoryFunction
    {
        return $this->laboratoryFunction;
    }

    public function setLaboratoryFunction(?LaboratoryFunction $laboratoryFunction): void
    {
        $this->laboratoryFunction = $laboratoryFunction;
    }

    public function getPathogen(): ?Pathogen
    {
        return $this->pathogen;
    }

    public function setPathogen(?Pathogen $pathogen): void
    {
        $this->pathogen = $pathogen;
    }

    public function getPathogenName(): ?string
    {
        return $this->pathogenName;
    }

    public function setPathogenName(?string $pathogenName): void
    {
        $this->pathogenName = $pathogenName;
    }

    public function getReceivedAt(): ?\DateTimeImmutable
    {
        return $this->receivedAt;
    }

    public function setReceivedAt(?\DateTimeImmutable $receivedAt): void
    {
        $this->receivedAt = $receivedAt;
    }


    public function getOrdererIdentifier(): ?string
    {
        return $this->ordererIdentifier;
    }

    public function setOrdererIdentifier(?string $ordererIdentifier): void
    {
        $this->ordererIdentifier = $ordererIdentifier;
    }

    public function getOrderer(): ?Organization
    {
        return $this->orderer;
    }

    public function setOrderer(?Organization $orderer): void
    {
        $this->orderer = $orderer;
    }
}
