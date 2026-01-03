<?php

namespace App\Services\Elm;

use App\Entity\ElmReport;

readonly class ApiClient
{
    public function __construct(private string $baseUrl, private string $elmBaseUrl, private string $elmSSLCertPath, private string $elmSSLKeyPath)
    {
    }

    public function validateDocumentReference(string $json, ?string &$error = null): string
    {
        return $this->performApiCall("/DocumentReference/\$Validate", $json, $error);
    }

    public function validateBundle(string $json, ?string &$error = null): string
    {
        // compared to validate document reference, validating the bundle is more localized
        // this includes more localized errors (i.e. there is a separate entry for the missing patient.gender)
        // however, there are also many false errors thrown (e.g. if patient.gender is missing, 5 other errors appear)
        // so overall, not really more useful than a document reference
        return $this->performApiCall("/Bundle/\$Validate", $json, $error);
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
