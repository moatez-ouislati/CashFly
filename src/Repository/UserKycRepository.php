<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use App\Entity\UserKyc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserKyc>
 */
class UserKycRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserKyc::class);
    }

    public function findByUser(Utilisateur $user): ?UserKyc
    {
        return $this->findOneBy(['utilisateur' => $user]);
    }

    public function findVerifiedUsers(): array
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.isVerified = :verified')
            ->setParameter('verified', 1)
            ->getQuery()
            ->getResult();
    }

    public function findPendingVerification(): array
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.isVerified = :verified')
            ->setParameter('verified', 0)
            ->getQuery()
            ->getResult();
    }
}
