<?php

namespace App\Entity\Probe;

use App\Entity\Organization;
use App\Enum\AnalysisType;
use App\Enum\LaboratoryFunction;
use App\Enum\Pathogen;
use App\Extension\SerializerExtension;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait ServiceRequest
{
    use OrdererCopy;

    #[ORM\Column(type: Types::STRING, enumType: LaboratoryFunction::class)]
    #[Groups(['probe:read', 'probe:write'])]
    private LaboratoryFunction $laboratoryFunction = LaboratoryFunction::REFERENCE;

    #[ORM\Column(type: Types::STRING, enumType: Pathogen::class, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?Pathogen $pathogen = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?string $pathogenName = null;

    /**
     * @var AnalysisType[]
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY, enumType: AnalysisType::class, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private array $analysisTypes = [];

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(['probe:read', 'probe:write'])]
    private ?string $ordererIdentifier = null;

    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'probes')]
    #[Groups(['probe:read', 'probe:write'])]
    private ?Organization $orderer = null;

    public function getLaboratoryFunction(): LaboratoryFunction
    {
        return $this->laboratoryFunction;
    }

    public function setLaboratoryFunction(LaboratoryFunction $laboratoryFunction): void
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

    /**
     * @return AnalysisType[]
     */
    public function getAnalysisTypes(): array
    {
        return $this->analysisTypes;
    }

    /**
     * @param AnalysisType[] $analysisTypes
     */
    public function setAnalysisTypes(array $analysisTypes): void
    {
        $this->analysisTypes = SerializerExtension::unserializeEnumArray(AnalysisType::class, $analysisTypes);
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
