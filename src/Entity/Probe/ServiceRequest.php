<?php

namespace App\Entity\Probe;

use ApiPlatform\Metadata\ApiProperty;
use App\Entity\Organization;
use App\Entity\Practitioner;
use App\Enum\AnalysisType;
use App\Enum\LaboratoryFunction;
use App\Enum\Pathogen;
use App\Extension\SerializerExtension;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait ServiceRequest
{
    use OrdererOrgCopy;
    use OrdererPracCopy;

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
    private ?string $requisitionIdentifier = null;

    #[ORM\ManyToOne(targetEntity: Organization::class, inversedBy: 'probes')]
    #[Groups(['probe:read', 'probe:write'])]
    #[ApiProperty(readableLink: false, writableLink: false)]
    private ?Organization $ordererOrg = null;

    #[ORM\ManyToOne(targetEntity: Practitioner::class, inversedBy: 'probes')]
    #[Groups(['probe:read', 'probe:write'])]
    #[ApiProperty(readableLink: false, writableLink: false)]
    private ?Practitioner $ordererPrac = null;

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

    public function getRequisitionIdentifier(): ?string
    {
        return $this->requisitionIdentifier;
    }

    public function setRequisitionIdentifier(?string $requisitionIdentifier): void
    {
        $this->requisitionIdentifier = $requisitionIdentifier;
    }

    public function getOrdererOrg(): ?Organization
    {
        return $this->ordererOrg;
    }

    public function setOrdererOrg(?Organization $ordererOrg): void
    {
        $this->ordererOrg = $ordererOrg;
    }

    public function getOrdererPrac(): ?Practitioner
    {
        return $this->ordererPrac;
    }

    public function setOrdererPrac(?Practitioner $ordererPrac): void
    {
        $this->ordererPrac = $ordererPrac;
    }
}
