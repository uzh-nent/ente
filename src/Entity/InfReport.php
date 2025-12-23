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

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class InfReport
{
    use IdTrait;
    use AttributionTrait;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    private ?Probe $probe = null;

    #[ORM\ManyToOne(targetEntity: LeadingCode::class)]
    private ?LeadingCode $leadingCode = null;

    #[ORM\ManyToOne(targetEntity: Organism::class)]
    private ?Organism $organism = null;

    #[ORM\ManyToOne(targetEntity: Specimen::class)]
    private ?Specimen $specimen = null;

    #[ORM\Column(type: Types::STRING, enumType: Interpretation::class, nullable: true)]
    private ?Interpretation $interpretation = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTime $sentAt = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $documentId = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?string $payload = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?string $response = null;

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

    public function getSentAt(): ?\DateTime
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTime $sentAt): void
    {
        $this->sentAt = $sentAt;
    }

    public function getDocumentId(): ?string
    {
        return $this->documentId;
    }

    public function setDocumentId(?string $documentId): void
    {
        $this->documentId = $documentId;
    }

    public function getPayload(): ?string
    {
        return $this->payload;
    }

    public function setPayload(?string $payload): void
    {
        $this->payload = $payload;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(?string $response): void
    {
        $this->response = $response;
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
