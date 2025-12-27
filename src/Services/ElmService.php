<?php

namespace App\Services;

use App\Entity\ElmReport;
use App\Services\Elm\ApiBuilder;
use App\Services\Elm\ApiClient;

readonly class ElmService
{
    public function __construct(private ApiClient $apiClient, private ApiBuilder $apiBuilder)
    {
    }


    public function send(ElmReport $report): void
    {
        $report->setDiagnosticReportId($report->getId());

        $payload = $this->apiBuilder->build($report->getProbe(), $report);
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $report->setRequestJson($json);

        $responseJson = $this->apiClient->sendDocumentReference($json);
        $report->setResponseJson($responseJson);
    }
}
