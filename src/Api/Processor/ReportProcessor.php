<?php

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Report;
use App\Entity\Observation;
use App\Entity\Probe;
use App\Entity\User;
use App\Enum\LaboratoryFunction;
use App\Helper\DoctrineHelper;
use App\Services\ElmService;
use App\Services\Interfaces\ElmServiceInterface;
use App\Services\Interfaces\FileServiceInterface;
use App\Services\Interfaces\PdfServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @implements ProcessorInterface<Report, Report>
 */
readonly class ReportProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<Report, Report> $persistProcessor
     */
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private TokenStorageInterface $tokenStorage,
        private PdfServiceInterface $pdfService,
        private FileServiceInterface $fileService,
        private ManagerRegistry $registry
    ) {
    }

    /**
     * @param Report $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$operation instanceof Post) {
            throw new \RuntimeException('Only POST operations are supported.');
        }

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $data->attribute($user);

        $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        $pdf = $this->pdfService->generateReport($data);
        $filename = $this->fileService->saveFile($data->getProbe()->getIdentifier() . "-" . $data->getTitle(), $pdf);
        $data->setFilename($filename);
        DoctrineHelper::persistAndFlush($this->registry, $data);

        return $result;
    }
}
