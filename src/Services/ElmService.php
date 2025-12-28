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

        $payload = $this->apiBuilder->build($report->getProbe(), $report);
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $report->setRequestJson($json);

        if (!$this->validateDocumentReference($report)) {
            return;
        }

        if (!$this->sendDocumentReference($report)) {
            return;
        }

        $report->setApiStatus(ElmApiStatus::IN_PROGRESS);
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

        $successful = $this->apiParser->parseDocumentReference($responseJson, $documentId);
        if (!$successful) {
            $this->logger->error("validateDocumentReference failed for " . $report->getId() . " with " . $error);
            $report->setApiStatus(ElmApiStatus::VALIDATION_ERROR);

            return false;
        }

        $report->setSendResponseDocumentReferenceId($documentId);

        return true;
    }

    public function checkProgress(ElmReport $report): void
    {
        // TODO: Implement checkProgress() method.
    }
}
