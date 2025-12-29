<?php

declare(strict_types=1);

namespace App\Api\Provider;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\State\ProviderInterface;
use App\Entity\ElmReport;
use App\Enum\ElmApiStatus;
use App\Helper\DoctrineHelper;
use App\Services\Interfaces\ElmServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @implements ProviderInterface<ElmReport>
 */
readonly class ElmReportProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.item_provider')]
        private ProviderInterface   $itemProvider,
        #[Autowire(service: 'api_platform.doctrine.orm.state.collection_provider')]
        private ProviderInterface   $collectionProvider,
        private ManagerRegistry     $managerRegistry,
        private ElmServiceInterface $elmService,
        private RequestStack $requestStack,

    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // Post is validated in the processor
        if ($operation instanceof Get) {
            $request = $this->requestStack->getCurrentRequest();
            if ($request->query->get('poll') !== null && isset($uriVariables['id'])) {
                $repository = $this->managerRegistry->getRepository(ElmReport::class);
                /** @var ElmReport $elmReport */
                $elmReport = $repository->find($uriVariables['id']);
                if ($elmReport && $elmReport->getApiStatus() === ElmApiStatus::QUEUED) {
                    $this->elmService->checkProgress($elmReport);
                    DoctrineHelper::persistAndFlush($this->managerRegistry, $elmReport);
                }
            }
        }

        if ($operation instanceof GetCollection) {
            return $this->collectionProvider->provide($operation, $uriVariables, $context);
        } else {
            return $this->itemProvider->provide($operation, $uriVariables, $context);
        }
    }
}
