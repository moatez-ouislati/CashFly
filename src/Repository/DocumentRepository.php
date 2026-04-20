<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 */
// ...existing code...
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function searchAndFilterAdmin(?string $search, ?string $type)
    {
        $qb = $this->createQueryBuilder('d');

        if ($search) {
            $qb->andWhere('d.nomDocument LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($type) {
            $qb->andWhere('d.typeDocument = :type')
               ->setParameter('type', $type);
        }

        return $qb->orderBy('d.id', 'DESC')->getQuery()->getResult();
    }

    public function findAllTypes(): array
    {
        return $this->createQueryBuilder('d')
            ->select('d.typeDocument')
            ->distinct()
            ->where('d.typeDocument IS NOT NULL')
            ->getQuery()
            ->getSingleColumnResult();
    }
}