<?php

namespace App\Repository;

use App\Entity\Probe;
use App\Entity\User;
use App\Enum\LaboratoryFunction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Probe>
 */
class ProbeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Probe::class);
    }

    public function getNextIdentifier(LaboratoryFunction $laboratoryFunction): string
    {
        $identifierPrefix = $laboratoryFunction === LaboratoryFunction::PRIMARY ? 'P' : 'N';
        $identifierPrefix .= (new \DateTime())->format("y") . "-"; // small y is two digit representation of the year

        $lastProbe = $this->createQueryBuilder('p')
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

        return $identifierPrefix . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }
}
