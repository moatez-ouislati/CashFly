<?php

namespace App\Repository;

use App\Entity\Entreprise;
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

    public function searchAndFilterAdmin(?string $search, ?string $secteur)
    {
        $qb = $this->createQueryBuilder('e');

        if ($search) {
            $qb->andWhere('e.nom LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($secteur) {
            $qb->andWhere('e.secteur = :secteur')
               ->setParameter('secteur', $secteur);
        }

        return $qb->orderBy('e.id', 'DESC')->getQuery()->getResult();
    }

    public function findAllSectors(): array
    {
        return $this->createQueryBuilder('e')
            ->select('e.secteur')
            ->distinct()
            ->where('e.secteur IS NOT NULL')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function findMapEntreprises(?\App\Entity\User $user = null, bool $showAll = false): array
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.latitude IS NOT NULL')
            ->andWhere('e.longitude IS NOT NULL');

        if (!$showAll && $user) {
            $qb->andWhere('e.proprietaire = :user')
               ->setParameter('user', $user);
        }

        return $qb->getQuery()->getResult();
    }

    public function getEntreprisesByMonth(int $limit = 12, ?\App\Entity\User $proprietaire = null): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $where = "WHERE date_creation IS NOT NULL";
        $params = ['limit' => $limit];
        $types = ['limit' => \Doctrine\DBAL\ParameterType::INTEGER];

        if ($proprietaire) {
            $where .= " AND id_proprietaire = :prop_id";
            $params['prop_id'] = $proprietaire->getId();
            $types['prop_id'] = \Doctrine\DBAL\ParameterType::INTEGER;
        }

        $sql = "
            SELECT 
                DATE_FORMAT(date_creation, '%Y-%m') as month,
                COUNT(id_entreprise) as nb_entreprises
            FROM entreprises
            $where
            GROUP BY month
            ORDER BY month ASC
            LIMIT :limit
        ";

        return $conn->executeQuery($sql, $params, $types)->fetchAllAssociative();
    }

    public function getCapitalByMonth(int $limit = 12, ?\App\Entity\User $proprietaire = null): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $where = "WHERE date_creation IS NOT NULL";
        $params = ['limit' => $limit];
        $types = ['limit' => \Doctrine\DBAL\ParameterType::INTEGER];

        if ($proprietaire) {
            $where .= " AND id_proprietaire = :prop_id";
            $params['prop_id'] = $proprietaire->getId();
            $types['prop_id'] = \Doctrine\DBAL\ParameterType::INTEGER;
        }

        $sql = "
            SELECT 
                DATE_FORMAT(date_creation, '%Y-%m') as month,
                SUM(capital) as total_capital
            FROM entreprises
            $where
            GROUP BY month
            ORDER BY month ASC
            LIMIT :limit
        ";

        return $conn->executeQuery($sql, $params, $types)->fetchAllAssociative();
    }

    public function getCapitalStatsBySector(?\App\Entity\User $proprietaire = null): array
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e.secteur, COUNT(e.id) as nb_entreprises, COUNT(e.id) as count, SUM(e.capital) as total_capital, AVG(e.capital) as avg_capital')
            ->where('e.secteur IS NOT NULL');

        if ($proprietaire) {
            $qb->andWhere('e.proprietaire = :prop')
               ->setParameter('prop', $proprietaire);
        }

        return $qb->groupBy('e.secteur')
            ->orderBy('total_capital', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getCapitalStats(?\App\Entity\User $proprietaire = null): array
    {
        $qb = $this->createQueryBuilder('e')
            ->select('COUNT(e.id) as total_entreprises, SUM(e.capital) as total_capital, AVG(e.capital) as avg_capital, MIN(e.capital) as min_capital, MAX(e.capital) as max_capital');

        if ($proprietaire) {
            $qb->andWhere('e.proprietaire = :prop')
               ->setParameter('prop', $proprietaire);
        }

        return $qb->getQuery()->getSingleResult();
    }

    public function getTopEntreprises(int $limit = 5): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.capital', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}