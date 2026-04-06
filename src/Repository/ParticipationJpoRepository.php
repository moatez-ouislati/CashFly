<?php

namespace App\Repository;

use App\Entity\ParticipationJpo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParticipationJpo>
 *
 * @method ParticipationJpo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipationJpo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipationJpo[]    findAll()
 * @method ParticipationJpo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationJpoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParticipationJpo::class);
    }

    public function add(ParticipationJpo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ParticipationJpo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
