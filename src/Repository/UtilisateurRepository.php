<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UtilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Utilisateur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setMotDePasse($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findByEmail(string $email): ?Utilisateur
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByCin(int $cin): ?Utilisateur
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.cin = :cin')
            ->setParameter('cin', $cin)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findActiveUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.active = :active')
            ->setParameter('active', 1)
            ->orderBy('u.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.role = :role')
            ->setParameter('role', $role)
            ->getQuery()
            ->getResult();
    }

    public function countByRole(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.role as role, COUNT(u.id) as count')
            ->groupBy('u.role')
            ->getQuery()
            ->getResult();
    }

    public function search(string $query): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.nom LIKE :query OR u.prenom LIKE :query OR u.email LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('u.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
