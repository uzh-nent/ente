<?php

namespace App\Services\Elm;

use Psr\Log\LoggerInterface;

readonly class ApiClient
{
    public function __construct(private LoggerInterface $logger, private string $baseUrl, private string $elmBaseUrl, private string $elmSSLCertPath, private string $elmSSLKeyPath)
    {
    }

    public function sendDocumentReference(string $json, bool $onlyValidate = false): ?string
    {
        $ch = curl_init();

        $urlSuffix = $onlyValidate ? "\$Validate" : "";
        curl_setopt($ch, CURLOPT_URL, $this->elmBaseUrl . "/DocumentReference/" . $urlSuffix);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->elmSSLCertPath);
        curl_setopt($ch, CURLOPT_SSLKEY, $this->elmSSLKeyPath);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json+fhir'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_REFERER, $this->baseUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0); // do not return headers
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // store response in a variable

        $responseJson = curl_exec($ch);
        if (curl_errno($ch)) {
            $this->logger->error('Sending document failed: ' . curl_error($ch));
            return null;
        }
        curl_close($ch);

        return $responseJson;
    }
}
