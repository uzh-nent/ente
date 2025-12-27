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
use App\Entity\Traits\IdTrait;
use App\Enum\Interpretation;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class ElmReport
{
    use IdTrait;
    use AttributionTrait;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    private ?Probe $probe = null;

    #[ORM\ManyToOne(targetEntity: Observation::class)]
    private ?Observation $observation = null;

    #[ORM\ManyToOne(targetEntity: LeadingCode::class)]
    private ?LeadingCode $leadingCode = null;

    #[ORM\ManyToOne(targetEntity: Organism::class)]
    private ?Organism $organism = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $organismText = null;

    #[ORM\ManyToOne(targetEntity: Specimen::class)]
    private ?Specimen $specimen = null;

    #[ORM\Column(type: Types::STRING, enumType: Interpretation::class, nullable: true)]
    private ?Interpretation $interpretation = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $effectiveDate = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $sentAt = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $diagnosticReportId = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?string $requestJson = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?string $responseJson = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $apiId = null;

    // per default in-progress, then need to poll API until done
    #[ORM\Column(type: Types::STRING)]
    private ?bool $apiStatus;

    public function getProbe(): ?Probe
    {
        return $this->probe;
    }

    public function setProbe(?Probe $probe): void
    {
        $this->probe = $probe;
    }

    public function getObservation(): ?Observation
    {
        return $this->observation;
    }

    public function setObservation(?Observation $observation): void
    {
        $this->observation = $observation;
    }

    public function getLeadingCode(): ?LeadingCode
    {
        return $this->leadingCode;
    }

    public function setLeadingCode(?LeadingCode $leadingCode): void
    {
        $this->leadingCode = $leadingCode;
    }

    public function getOrganism(): ?Organism
    {
        return $this->organism;
    }

    public function setOrganism(?Organism $organism): void
    {
        $this->organism = $organism;
    }

    public function getOrganismText(): ?string
    {
        return $this->organismText;
    }

    public function setOrganismText(?string $organismText): void
    {
        $this->organismText = $organismText;
    }

    public function getSpecimen(): ?Specimen
    {
        return $this->specimen;
    }

    public function setSpecimen(?Specimen $specimen): void
    {
        $this->specimen = $specimen;
    }

    public function getInterpretation(): ?Interpretation
    {
        return $this->interpretation;
    }

    public function setInterpretation(?Interpretation $interpretation): void
    {
        $this->interpretation = $interpretation;
    }

    public function getEffectiveDate(): ?\DateTimeImmutable
    {
        return $this->effectiveDate;
    }

    public function setEffectiveDate(?\DateTimeImmutable $effectiveDate): void
    {
        $this->effectiveDate = $effectiveDate;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTimeImmutable $sentAt): void
    {
        $this->sentAt = $sentAt;
    }

    public function getDiagnosticReportId(): ?string
    {
        return $this->diagnosticReportId;
    }

    public function setDiagnosticReportId(?string $diagnosticReportId): void
    {
        $this->diagnosticReportId = $diagnosticReportId;
    }

    public function getRequestJson(): ?string
    {
        return $this->requestJson;
    }

    public function setRequestJson(?string $requestJson): void
    {
        $this->requestJson = $requestJson;
    }

    public function getResponseJson(): ?string
    {
        return $this->responseJson;
    }

    public function setResponseJson(?string $responseJson): void
    {
        $this->responseJson = $responseJson;
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    public function setApiId(?string $apiId): void
    {
        $this->apiId = $apiId;
    }

    public function getApiStatus(): ?bool
    {
        return $this->apiStatus;
    }

    public function setApiStatus(?bool $apiStatus): void
    {
        $this->apiStatus = $apiStatus;
    }
}
