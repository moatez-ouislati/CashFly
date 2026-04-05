<?php

namespace App\Repository;

use App\Entity\Document;
use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function findByEntreprise(Entreprise $entreprise): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.entreprise = :entreprise')
            ->setParameter('entreprise', $entreprise)
            ->orderBy('d.dateUpload', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByUtilisateur(Utilisateur $utilisateur): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.utilisateur = :utilisateur')
            ->setParameter('utilisateur', $utilisateur)
            ->orderBy('d.dateUpload', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.typeDocument = :type')
            ->setParameter('type', $type)
            ->orderBy('d.dateUpload', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByStatut(string $statut): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statut = :statut')
            ->setParameter('statut', $statut)
            ->orderBy('d.dateUpload', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPendingDocuments(): array
    {
        return $this->findByStatut(Document::STATUT_EN_ATTENTE);
    }

    public function search(string $query): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.nomDocument LIKE :query OR d.description LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('d.dateUpload', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
