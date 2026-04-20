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

    public function getCapitalStats(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = '
            SELECT 
                COUNT(*) as total_entreprises,
                SUM(CAST(capital AS DECIMAL(15,2))) as total_capital,
                AVG(CAST(capital AS DECIMAL(15,2))) as avg_capital,
                MIN(CAST(capital AS DECIMAL(15,2))) as min_capital,
                MAX(CAST(capital AS DECIMAL(15,2))) as max_capital
            FROM entreprises
        ';
        
        $result = $conn->executeQuery($sql)->fetchAssociative();
        
        return [
            'total' => (int) ($result['total_entreprises'] ?? 0),
            'total_capital' => (float) ($result['total_capital'] ?? 0),
            'avg_capital' => (float) ($result['avg_capital'] ?? 0),
            'min_capital' => (float) ($result['min_capital'] ?? 0),
            'max_capital' => (float) ($result['max_capital'] ?? 0),
        ];
    }

    public function getCapitalStatsBySector(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = '
            SELECT 
                secteur,
                COUNT(*) as nb_entreprises,
                SUM(CAST(capital AS DECIMAL(15,2))) as total_capital,
                AVG(CAST(capital AS DECIMAL(15,2))) as avg_capital
            FROM entreprises
            WHERE secteur IS NOT NULL AND secteur != ""
            GROUP BY secteur
            ORDER BY total_capital DESC
        ';
        
        return $conn->executeQuery($sql)->fetchAllAssociative();
    }

    public function getTopEntreprises(int $limit = 5): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = '
            SELECT * FROM entreprises
            ORDER BY CAST(capital AS DECIMAL(15,2)) DESC
            LIMIT ' . (int)$limit . '
        ';
        
        return $conn->executeQuery($sql)->fetchAllAssociative();
    }

    public function findAllWithCoordinates(): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.latitude IS NOT NULL')
            ->andWhere('e.longitude IS NOT NULL')
            ->getQuery()
            ->getResult();
    }

    public function getEntreprisesByMonth(int $months = 12): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = '
            SELECT 
                DATE_FORMAT(date_creation, "%Y-%m") as month,
                COUNT(*) as nb_entreprises,
                SUM(CAST(capital AS DECIMAL(15,2))) as total_capital
            FROM entreprises
            WHERE date_creation IS NOT NULL
            GROUP BY DATE_FORMAT(date_creation, "%Y-%m")
            ORDER BY month DESC
            LIMIT ' . (int)$months . '
        ';
        
        $result = $conn->executeQuery($sql)->fetchAllAssociative();
        
        return array_reverse($result);
    }

    public function getCapitalByMonth(int $months = 12): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = '
            SELECT 
                DATE_FORMAT(date_creation, "%Y-%m") as month,
                SUM(CAST(capital AS DECIMAL(15,2))) as total_capital,
                AVG(CAST(capital AS DECIMAL(15,2))) as avg_capital
            FROM entreprises
            WHERE date_creation IS NOT NULL
            GROUP BY DATE_FORMAT(date_creation, "%Y-%m")
            ORDER BY month DESC
            LIMIT ' . (int)$months . '
        ';
        
        $result = $conn->executeQuery($sql)->fetchAllAssociative();
        
        return array_reverse($result);
    }
}