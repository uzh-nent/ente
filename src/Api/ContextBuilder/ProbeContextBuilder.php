<?php

namespace App\Api\ContextBuilder;

use ApiPlatform\State\SerializerContextBuilderInterface;
use App\Entity\Probe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class ProbeContextBuilder implements SerializerContextBuilderInterface
{
    public function __construct(private SerializerContextBuilderInterface $decorated, private RequestStack $requestStack)
    {
    }

    /**
     * @param array<string, mixed>|null $extractedAttributes
     */
    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        $request = $this->requestStack->getCurrentRequest();
        if ($resourceClass === Probe::class && $request->query->has('collections') && true === $normalization) {
            $context['groups'][] = 'probe:collections';
            $context['groups'][] = 'observation:read';
        }

        return $context;
    }
}
