<?php

namespace App\Repository;

use App\Entity\Entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entreprise>
 */
class EntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entreprise::class);
    }

    public function searchAndFilterAdmin(?string $search, ?string $secteur)
    {
        $qb = $this->createQueryBuilder('e');

        if ($search) {
            $qb->andWhere('e.nom LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($secteur) {
            $qb->andWhere('e.secteur = :secteur')
               ->setParameter('secteur', $secteur);
        }

        return $qb->orderBy('e.id', 'DESC')->getQuery()->getResult();
    }

    public function findAllSectors(): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.secteur')
            ->distinct()
            ->where('e.secteur IS NOT NULL')
            ->getQuery()
            ->getSingleColumnResult();
    }
}