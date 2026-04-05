<?php

namespace App\Repository;

use App\Entity\OperationNote;
use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OperationNote>
 */
class OperationNoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperationNote::class);
    }

    public function findByOperation(Operation $operation): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.operation = :operation')
            ->setParameter('operation', $operation)
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findRecentNotes(int $limit = 20): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
