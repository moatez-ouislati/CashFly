<?php

namespace App\Repository;

use App\Entity\Entreprise;
use App\Entity\Utilisateur;
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

    public function findByProprietaire(Utilisateur $proprietaire): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.proprietaire = :proprietaire')
            ->setParameter('proprietaire', $proprietaire)
            ->orderBy('e.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findBySecteur(string $secteur): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.secteur = :secteur')
            ->setParameter('secteur', $secteur)
            ->getQuery()
            ->getResult();
    }

    public function search(string $query): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nom LIKE :query OR e.secteur LIKE :query OR e.adresse LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('e.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findWithInvestors(): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.investissements', 'i')
            ->addSelect('i')
            ->getQuery()
            ->getResult();
    }

    public function countBySecteur(): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.secteur, COUNT(e.idEntreprise) as count')
            ->groupBy('e.secteur')
            ->getQuery()
            ->getResult();
    }
}
