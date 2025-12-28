<?php

namespace App\Services\Elm;

class ApiParser
{
    public function checkOperationOutcomeSuccessful(string $json): bool
    {
        $payload = json_decode($json, true);

        // if different resource type, then the operation was successful
        if ($payload['resourceType'] !== 'OperationOutcome') {
            return true;
        }

        if (!isset($payload["issue"])) {
            return false;
        }

        foreach ($payload['issue'] as $issue) {
            if ($issue['severity'] === 'error') {
                return false;
            }
        }

        return true;
    }

    public function parseDocumentReference(string $json, ?string &$documentId = null): bool
    {
        $payload = json_decode($json, true);
        if ($payload['resourceType'] !== 'DocumentReference') {
            return false;
        }

        if (!isset($payload["content"]) || !is_array($payload["content"])) {
            return false;
        }

        foreach ($payload['content'] as $content) {
            if (isset($content['attachment']['url'])) {
                $documentId = $content['attachment']['url'];
                return true;
            }
        }

        return false;
    }
}
