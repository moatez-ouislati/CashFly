<?php

namespace App\Repository;

use App\Entity\Operation;
use App\Entity\Tresorerie;
use App\Entity\Entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
    }

    public function findByTresorerie(Tresorerie $tresorerie, int $limit = 50): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.tresorerie = :tresorerie')
            ->setParameter('tresorerie', $tresorerie)
            ->orderBy('o.dateOperation', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByEntreprise(Entreprise $entreprise, int $limit = 50): array
    {
        return $this->createQueryBuilder('o')
            ->join('o.tresorerie', 't')
            ->andWhere('t.entreprise = :entreprise')
            ->setParameter('entreprise', $entreprise)
            ->orderBy('o.dateOperation', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.type = :type')
            ->setParameter('type', $type)
            ->orderBy('o.dateOperation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByDateRange(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.dateOperation BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('o.dateOperation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByCategory(string $categorie): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.categorie LIKE :categorie')
            ->setParameter('categorie', '%' . $categorie . '%')
            ->orderBy('o.dateOperation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getTotalRevenus(\DateTimeInterface $start = null, \DateTimeInterface $end = null): float
    {
        $qb = $this->createQueryBuilder('o')
            ->select('SUM(o.montant)')
            ->andWhere('o.type = :type')
            ->setParameter('type', 'revenu');

        if ($start) {
            $qb->andWhere('o.dateOperation >= :start')
                ->setParameter('start', $start);
        }
        if ($end) {
            $qb->andWhere('o.dateOperation <= :end')
                ->setParameter('end', $end);
        }

        return (float) ($qb->getQuery()->getSingleScalarResult() ?? 0);
    }

    public function getTotalDepenses(\DateTimeInterface $start = null, \DateTimeInterface $end = null): float
    {
        $qb = $this->createQueryBuilder('o')
            ->select('SUM(o.montant)')
            ->andWhere('o.type = :type')
            ->setParameter('type', 'depense');

        if ($start) {
            $qb->andWhere('o.dateOperation >= :start')
                ->setParameter('start', $start);
        }
        if ($end) {
            $qb->andWhere('o.dateOperation <= :end')
                ->setParameter('end', $end);
        }

        return (float) ($qb->getQuery()->getSingleScalarResult() ?? 0);
    }

    public function getMontantByCategory(): array
    {
        return $this->createQueryBuilder('o')
            ->select('o.categorie as categorie, SUM(o.montant) as total')
            ->groupBy('o.categorie')
            ->getQuery()
            ->getResult();
    }

    public function getRecentOperations(int $limit = 10): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.dateOperation', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function search(string $query): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.description LIKE :query OR o.reference LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('o.dateOperation', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
