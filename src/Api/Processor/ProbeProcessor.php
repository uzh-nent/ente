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
            $identifierPrefix = $data->getLaboratoryFunction() === LaboratoryFunction::PRIMARY ? 'P' : 'R';
            $identifierPrefix .= (new \DateTime())->format("YY");

            $lastProbe = $this->registry->getRepository(Probe::class)->createQueryBuilder('p')
                ->where('p.identifier LIKE :prefix')
                ->setParameter('prefix', $identifierPrefix . '%')
                ->orderBy('p.identifier', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            if ($lastProbe) {
                $lastId = substr($lastProbe->getIdentifier(), 4);
                $nextId = (int)$lastId + 1;
            } else {
                $nextId = 1;
            }
            $nextIdentifier = $identifierPrefix . str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
            $data->setIdentifier($nextIdentifier);
        }

        if ($operation instanceof Post || $operation instanceof Patch || $operation instanceof Put) {
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();
            $data->attribute($user);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
