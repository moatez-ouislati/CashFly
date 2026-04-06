<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 *
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
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
            $qb->andWhere('d.nom LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($type) {
            $qb->andWhere('d.type = :type')
               ->setParameter('type', $type);
        }

        return $qb->orderBy('d.id', 'DESC')->getQuery()->getResult();
    }

    public function findAllTypes(): array
    {
        return $this->createQueryBuilder('d')
            ->select('d.type')
            ->distinct()
            ->where('d.type IS NOT NULL')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function add(Document $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Document $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Document[] Returns an array of Document objects
     */
    public function findByUser($user): array
    {
        return $this->createQueryBuilder('d')
            ->join('d.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->orderBy('d.dateUpload', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
