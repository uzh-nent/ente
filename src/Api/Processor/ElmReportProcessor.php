<?php

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\ElmReport;
use App\Entity\Observation;
use App\Entity\Probe;
use App\Entity\User;
use App\Enum\LaboratoryFunction;
use App\Helper\DoctrineHelper;
use App\Services\ElmService;
use App\Services\Interfaces\ElmServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @implements ProcessorInterface<ElmReport, ElmReport>
 */
readonly class ElmReportProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<ElmReport, ElmReport> $persistProcessor
     */
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private TokenStorageInterface $tokenStorage,
        private ElmServiceInterface $elmService,
        private ManagerRegistry $registry
    ) {
    }

    /**
     * @param ElmReport $data
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

        $this->elmService->send($data);
        DoctrineHelper::persistAndFlush($this->registry, $data);

        return $result;
    }
}
