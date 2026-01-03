<?php

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Observation;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @implements ProcessorInterface<Observation, Observation>
 */
readonly class ObservationProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<Observation, Observation> $persistProcessor
     */
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    /**
     * @param Observation $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($operation instanceof Post || $operation instanceof Patch || $operation instanceof Put) {
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();
            $data->attribute($user);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
