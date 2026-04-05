<?php

namespace App\Repository;

use App\Entity\Tresorerie;
use App\Entity\Entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TresorerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tresorerie::class);
    }

    public function findByEntreprise(Entreprise $entreprise): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.entreprise = :entreprise')
            ->setParameter('entreprise', $entreprise)
            ->orderBy('t.derniereMaj', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.typeCompte = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }

    public function getTotalSolde(): float
    {
        $result = $this->createQueryBuilder('t')
            ->select('SUM(t.solde) as total')
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }

    public function getSoldeByType(): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.typeCompte, SUM(t.solde) as total')
            ->groupBy('t.typeCompte')
            ->getQuery()
            ->getResult();
    }

    public function findRecentUpdated(int $limit = 10): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.derniereMaj', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
