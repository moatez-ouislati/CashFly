<?php

namespace App\Repository;

use App\Entity\Tresorerie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tresorerie>
 *
 * @method Tresorerie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tresorerie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tresorerie[]    findAll()
 * @method Tresorerie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TresorerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tresorerie::class);
    }
}
