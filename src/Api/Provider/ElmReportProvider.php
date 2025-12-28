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
        private ElmServiceInterface $elmService
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // Post is validated in the processor
        if ($operation instanceof Get) {
            if (isset($context['poll']) && isset($context['id'])) {
                $repository = $this->managerRegistry->getRepository(ElmReport::class);
                /** @var ElmReport $elmReport */
                $elmReport = $repository->find($context['id']);
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
