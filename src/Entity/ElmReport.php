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

use App\Entity\ElmReport\ElmPayload;
use App\Entity\Traits\AttributionTrait;
use App\Entity\Traits\IdTrait;
use App\Enum\ElmApiStatus;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class ElmReport
{
    use IdTrait;
    use AttributionTrait;
    use ElmPayload;

    #[ORM\ManyToOne(targetEntity: Probe::class)]
    private ?Probe $probe = null;

    #[ORM\ManyToOne(targetEntity: Observation::class)]
    private ?Observation $observation = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $sentAt = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $diagnosticReportId = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?string $requestJson = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?string $validationResponseJson = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?string $sendResponseJson = null;

    #[ORM\Column(type: Types::STRING, enumType: ElmApiStatus::class)]
    private ElmApiStatus $apiStatus = ElmApiStatus::CREATING;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $apiQueueStatus = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $documentReferenceId = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?string $lastDocumentReferenceResponseJson = null;

    public function getProbe(): Probe
    {
        return $this->probe;
    }

    public function setProbe(Probe $probe): void
    {
        $this->probe = $probe;
    }

    public function getObservation(): Observation
    {
        return $this->observation;
    }

    public function setObservation(Observation $observation): void
    {
        $this->observation = $observation;
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

    public function getValidationResponseJson(): ?string
    {
        return $this->validationResponseJson;
    }

    public function setValidationResponseJson(?string $validationResponseJson): void
    {
        $this->validationResponseJson = $validationResponseJson;
    }

    public function getSendResponseJson(): ?string
    {
        return $this->sendResponseJson;
    }

    public function setSendResponseJson(?string $sendResponseJson): void
    {
        $this->sendResponseJson = $sendResponseJson;
    }

    public function getApiStatus(): ElmApiStatus
    {
        return $this->apiStatus;
    }

    public function setApiStatus(ElmApiStatus $apiStatus): void
    {
        $this->apiStatus = $apiStatus;
    }

    public function getApiQueueStatus(): ?string
    {
        return $this->apiQueueStatus;
    }

    public function setApiQueueStatus(?string $apiQueueStatus): void
    {
        $this->apiQueueStatus = $apiQueueStatus;
    }

    public function getDocumentReferenceId(): ?string
    {
        return $this->documentReferenceId;
    }

    public function setDocumentReferenceId(?string $documentReferenceId): void
    {
        $this->documentReferenceId = $documentReferenceId;
    }

    public function getLastDocumentReferenceResponseJson(): ?string
    {
        return $this->lastDocumentReferenceResponseJson;
    }

    public function setLastDocumentReferenceResponseJson(?string $lastStatusResponseJson): void
    {
        $this->lastDocumentReferenceResponseJson = $lastStatusResponseJson;
    }
}
