<?php

namespace App\Services;

use App\Entity\ElmReport;
use App\Enum\ElmApiStatus;
use App\Services\Elm\ApiBuilder;
use App\Services\Elm\ApiClient;
use App\Services\Elm\ApiParser;
use App\Services\Interfaces\ElmServiceInterface;
use Psr\Log\LoggerInterface;

readonly class ElmService implements ElmServiceInterface
{
    public function __construct(private ApiClient $apiClient, private ApiBuilder $apiBuilder, private ApiParser $apiParser, private LoggerInterface $logger)
    {
    }


    public function send(ElmReport $report): void
    {
        $report->setDiagnosticReportId($report->getId());
        $report->setSentAt(new \DateTimeImmutable());

        $payload = $this->apiBuilder->build($report->getProbe(), $report);
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $report->setRequestJson($json);

        // validate document reference instead of bundle as error reporting of bundle is not more useful
        if (!$this->validateDocumentReference($report)) {
            return;
        }

        if (!$this->sendDocumentReference($report)) {
            return;
        }

        $report->setApiStatus(ElmApiStatus::QUEUED);
    }

    private function validateDocumentReference(ElmReport $report): bool
    {
        $error = null;
        $responseJson = $this->apiClient->validateDocumentReference($report->getRequestJson(), $error);
        $report->setValidationResponseJson($responseJson);

        if ($error) {
            $this->logger->error("validateDocumentReference error for " . $report->getId() . " with " . $error);
            $report->setApiStatus(ElmApiStatus::VALIDATION_ERROR);

            return false;
        }

        $successful = $this->apiParser->checkOperationOutcomeSuccessful($responseJson);
        if (!$successful) {
            $this->logger->error("validateDocumentReference issues for " . $report->getId() . " with " . $error);
            $report->setApiStatus(ElmApiStatus::VALIDATION_ISSUES);

            return false;
        }

        return true;
    }


    private function sendDocumentReference(ElmReport $report): bool
    {
        $error = null;
        $responseJson = $this->apiClient->sendDocumentReference($report->getRequestJson(), $error);
        $report->setSendResponseJson($responseJson);

        if ($error) {
            $this->logger->error("sendDocumentReference error for " . $report->getId() . " with " . $error);
            $report->setApiStatus(ElmApiStatus::SEND_ERROR);

            return false;
        }

        $successful = $this->apiParser->checkOperationOutcomeSuccessful($responseJson);
        if (!$successful) {
            $this->logger->error("sendDocumentReference issues for " . $report->getId() . " with " . $error);
            $report->setApiStatus(ElmApiStatus::SEND_ISSUES);

            return false;
        }

        $successful = $this->apiParser->tryParseDocumentReference($responseJson, $status, $documentId);
        if (!$successful || !$documentId || !$status) {
            $this->logger->error("sendDocumentReference document parse failed for " . $report->getId() . " with " . $error);
            $report->setApiStatus(ElmApiStatus::SEND_ERROR);

            return false;
        }

        $report->setApiQueueStatus($status);
        $report->setDocumentReferenceId($documentId);
        $report->setApiStatus($this->apiParser->setApiStatusFromQueueStatus($status));

        return true;
    }

    public function checkProgress(ElmReport $report): void
    {
        $error = null;
        $responseJson = $this->apiClient->getDocumentReference($report, $error);
        $report->setLastDocumentReferenceResponseJson($responseJson);

        if ($error) {
            $this->logger->error("getDocumentReference error for " . $report->getId() . " with " . $error);
            return;
        }

        $successful = $this->apiParser->checkOperationOutcomeSuccessful($responseJson);
        if (!$successful) {
            $this->logger->error("getDocumentReference issues for " . $report->getId() . " with " . $error);
            return;
        }

        $successful = $this->apiParser->tryParseDocumentReference($responseJson, $status);
        if (!$successful || !$status) {
            $this->logger->error("getDocumentReference failed for " . $report->getId() . " with " . $error);
            return;
        }

        $report->setApiQueueStatus($status);
        $report->setApiStatus($this->apiParser->setApiStatusFromQueueStatus($status));
    }
}
