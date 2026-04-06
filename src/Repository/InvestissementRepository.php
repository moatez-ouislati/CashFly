<?php

namespace App\Repository;

use App\Entity\Investissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Investissement>
 *
 * @method Investissement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Investissement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Investissement[]    findAll()
 * @method Investissement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvestissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Investissement::class);
    }

    public function add(Investissement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Investissement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
