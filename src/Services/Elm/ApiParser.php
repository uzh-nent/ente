<?php

namespace App\Services\Elm;

use App\Enum\ElmApiStatus;

class ApiParser
{
    public function setApiStatusFromQueueStatus(?string $status): ElmApiStatus
    {
        return match ($status) {
            'completed' => ElmApiStatus::COMPLETED,
            'failed' => ElmApiStatus::FAILED,
            default => ElmApiStatus::QUEUED, // such as 'in-progress'
        };
    }

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

    public function tryParseDocumentReference(string $json, ?string &$status = null, ?string &$documentId = null): bool
    {
        $payload = json_decode($json, true);
        if ($payload['resourceType'] !== 'DocumentReference') {
            return false;
        }

        if (isset($payload["content"]) && is_array($payload["content"])) {
            foreach ($payload['content'] as $content) {
                if (isset($content['attachment']['url'])) {
                    $documentId = $content['attachment']['url'];
                    if (str_starts_with($documentId, 'urn:uuid:')) {
                        $documentId = substr($documentId, 9);
                    }

                    break;
                }
            }
        }

        if (isset($payload["extension"]) && is_array($payload["extension"])) {
            foreach ($payload['extension'] as $extension) {
                if (isset($extension['url']) && $extension['url'] !== "http://fhir.ch/ig/ch-elm/StructureDefinition/ch-ext-elm-status") {
                    continue;
                }

                if (isset($extension['extension']) && is_array($extension['extension'])) {
                    foreach ($extension['extension'] as $innerExtension) {
                        if (isset($innerExtension['url']) && $innerExtension['url'] === "status" && isset($innerExtension['valueCode'])) {
                            $status = $innerExtension['valueCode'];
                            break;
                        }
                    }
                }
            }
        }

        return true;
    }
}
