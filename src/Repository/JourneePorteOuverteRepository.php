<?php

namespace App\Repository;

use App\Entity\JourneePorteOuverte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JourneePorteOuverte>
 *
 * @method JourneePorteOuverte|null find($id, $lockMode = null, $lockVersion = null)
 * @method JourneePorteOuverte|null findOneBy(array $criteria, array $orderBy = null)
 * @method JourneePorteOuverte[]    findAll()
 * @method JourneePorteOuverte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JourneePorteOuverteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JourneePorteOuverte::class);
    }

    public function add(JourneePorteOuverte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(JourneePorteOuverte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return JourneePorteOuverte[] Returns an array of JourneePorteOuverte objects
     */
    public function findUpcoming(): array
    {
        return $this->createQueryBuilder('j')
            ->where('j.dateEvenement >= :today')
            ->setParameter('today', new \DateTime())
            ->orderBy('j.dateEvenement', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
