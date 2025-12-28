<?php

namespace App\Services\Elm;

use App\Entity\ElmReport;
use Psr\Log\LoggerInterface;

readonly class ApiClient
{
    public function __construct(private string $baseUrl, private string $elmBaseUrl, private string $elmSSLCertPath, private string $elmSSLKeyPath)
    {
    }

    public function validateDocumentReference(string $json, ?string &$error = null): string
    {
        return $this->performApiCall("/DocumentReference/\$Validate", $json, $error);
    }

    public function sendDocumentReference(string $json, ?string &$error = null): string
    {
        return $this->performApiCall("/DocumentReference/", $json, $error);
    }

    public function getDocumentReference(ElmReport $report, ?string &$error = null): string
    {
        return $this->performApiCall("/DocumentReference/" . $report->getDocumentReferenceId(), null, $error);
    }

    private function performApiCall(string $endpoint, ?string $payload = null, ?string &$error = null): string
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->elmBaseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->elmSSLCertPath);
        curl_setopt($ch, CURLOPT_SSLKEY, $this->elmSSLKeyPath);

        curl_setopt($ch, CURLOPT_REFERER, $this->baseUrl);

        // post if payload is set
        if ($payload) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json+fhir'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }

        curl_setopt($ch, CURLOPT_HEADER, 0); // do not return headers
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // store response in a variable

        $responseJson = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = 'Sending document failed: ' . curl_error($ch);
        }

        return $responseJson;
    }
}
