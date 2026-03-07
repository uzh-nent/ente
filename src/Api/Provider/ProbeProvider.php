<?php

declare(strict_types=1);

namespace App\Api\Provider;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\ElmReport;
use App\Entity\Probe;
use App\Enum\ElmApiStatus;
use App\Helper\DoctrineHelper;
use App\Services\Interfaces\ElmServiceInterface;
use App\Services\Interfaces\ExportServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * @implements ProviderInterface<Probe>
 */
readonly class ProbeProvider implements ProviderInterface
{
    public function __construct(
        /** @var ProviderInterface<Probe> */
        #[Autowire(service: 'api_platform.doctrine.orm.state.collection_provider')]
        private ProviderInterface $collectionProvider,
        private ExportServiceInterface $exportService
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $probes = $this->collectionProvider->provide($operation, $uriVariables, $context);

        /** @var ?Request $request */
        $request = $context['request'] ?? null;
        if ($request?->headers->get('Accept') === ExportServiceInterface::MIME_EXCEL) {
            if ($probes instanceof Paginator) {
                $probes = iterator_to_array($probes->getIterator());
            }
            return $this->exportAsExcel($probes);
        }

        return $probes;
    }

    /**
     * @param Probe[] $probes
     * @return Response
     */
    private function exportAsExcel(array $probes): Response
    {
        $header = [
            'Identifier',
            'Requisition identifier',
            'Analysis Start Date',
        ];

        $content = [];

        foreach ($probes as $probe) {
            $content[] = [
                $probe->getIdentifier(),
                $probe->getRequisitionIdentifier(),
                $probe->getAnalysisStartDate()?->format('Y-m-d H:i:s') ?? '',
            ];
        }

        return $this->exportService->exportAsExcel('probes.xlsx', $header, $content);
    }
}
