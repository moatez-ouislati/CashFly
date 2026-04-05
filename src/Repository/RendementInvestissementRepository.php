<?php

namespace App\Repository;

use App\Entity\RendementInvestissement;
use App\Entity\Investissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RendementInvestissement>
 */
class RendementInvestissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendementInvestissement::class);
    }

    public function findByInvestissement(Investissement $investissement): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.investissement = :investissement')
            ->setParameter('investissement', $investissement)
            ->orderBy('r.dateCalcul', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getTotalGains(): float
    {
        $result = $this->createQueryBuilder('r')
            ->select('COALESCE(SUM(r.gain), 0)')
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }

    public function getTotalPertes(): float
    {
        $result = $this->createQueryBuilder('r')
            ->select('COALESCE(SUM(r.perte), 0)')
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }

    public function getRendementNet(): float
    {
        return $this->getTotalGains() - $this->getTotalPertes();
    }
}
