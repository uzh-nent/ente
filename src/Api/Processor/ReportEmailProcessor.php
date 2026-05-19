<?php

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\ElmReport;
use App\Entity\ReportEmail;
use App\Entity\User;
use App\Helper\DoctrineHelper;
use App\Services\Interfaces\ElmServiceInterface;
use App\Services\Interfaces\EmailServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @implements ProcessorInterface<ReportEmail, ReportEmail>
 */
readonly class ReportEmailProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<ReportEmail, ReportEmail> $persistProcessor
     */
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private TokenStorageInterface $tokenStorage,
        private EmailServiceInterface $emailService,
        private ManagerRegistry $registry
    ) {
    }

    /**
     * @param ReportEmail $data
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

        $this->emailService->send($data);
        DoctrineHelper::persistAndFlush($this->registry, $data);

        return $result;
    }
}
