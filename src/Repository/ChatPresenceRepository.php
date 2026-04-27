<?php

namespace App\Repository;

use App\Entity\ChatPresence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChatPresence>
 */
class ChatPresenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatPresence::class);
    }

    public function findByRoomAndUser(int $idRoom, int $idUtilisateur): ?ChatPresence
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idRoom = :room')
            ->andWhere('p.idUtilisateur = :user')
            ->setParameter('room', $idRoom)
            ->setParameter('user', $idUtilisateur)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
