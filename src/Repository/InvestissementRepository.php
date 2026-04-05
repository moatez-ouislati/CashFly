<?php

namespace App\Repository;

use App\Entity\Investissement;
use App\Entity\Utilisateur;
use App\Entity\Entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InvestissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Investissement::class);
    }

    public function findByInvestisseur(Utilisateur $investisseur): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.investisseur = :investisseur')
            ->setParameter('investisseur', $investisseur)
            ->orderBy('i.dateInvestissement', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByEntreprise(Entreprise $entreprise): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.entreprise = :entreprise')
            ->setParameter('entreprise', $entreprise)
            ->orderBy('i.dateInvestissement', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByStatut(string $statut): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.statut = :statut')
            ->setParameter('statut', $statut)
            ->orderBy('i.dateInvestissement', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPendingInvestissements(): array
    {
        return $this->findByStatut(Investissement::STATUT_EN_ATTENTE);
    }

    public function findActiveInvestissements(): array
    {
        return $this->findByStatut(Investissement::STATUT_VALIDE);
    }

    public function getTotalMontantInvesti(): float
    {
        $result = $this->createQueryBuilder('i')
            ->select('SUM(i.montant)')
            ->andWhere('i.statut = :statut')
            ->setParameter('statut', Investissement::STATUT_VALIDE)
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }

    public function getMontantByStatut(): array
    {
        return $this->createQueryBuilder('i')
            ->select('i.statut, SUM(i.montant) as total, COUNT(i.idInvestissement) as count')
            ->groupBy('i.statut')
            ->getQuery()
            ->getResult();
    }

    public function getTopInvestors(int $limit = 10): array
    {
        return $this->createQueryBuilder('i')
            ->select('u.id, u.nom, u.prenom, SUM(i.montant) as total')
            ->join('i.investisseur', 'u')
            ->andWhere('i.statut = :statut')
            ->setParameter('statut', Investissement::STATUT_VALIDE)
            ->groupBy('u.id')
            ->orderBy('total', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
