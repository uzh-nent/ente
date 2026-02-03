<?php

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Probe;
use App\Entity\User;
use App\Enum\LaboratoryFunction;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @implements ProcessorInterface<Probe, Probe>
 */
readonly class ProbeProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<Probe, Probe> $persistProcessor
     */
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private ManagerRegistry $registry,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    /**
     * @param Probe $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($operation instanceof Post) {
            $nextIdentifier = $this->registry->getRepository(Probe::class)->getNextIdentifier($data->getLaboratoryFunction());
            $data->setIdentifier($nextIdentifier);
        }

        if ($operation instanceof Post || $operation instanceof Patch || $operation instanceof Put) {
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();
            $data->attribute($user);

            if ($data->getFinishedAt() && !$data->getFinishedBy()) {
                $data->setFinishedBy($user);
            } elseif (!$data->getFinishedAt()) {
                $data->setFinishedBy(null);
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
