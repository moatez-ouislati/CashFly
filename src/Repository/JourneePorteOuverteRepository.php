<?php

namespace App\Repository;

use App\Entity\JourneePorteOuverte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JourneePorteOuverte>
 */
class JourneePorteOuverteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JourneePorteOuverte::class);
    }

    /**
     * Find events with optional filters and sorting.
     *
     * @param string|null $search   Search term (titre, lieu, description)
     * @param int|null    $month    Month number (1-12)
     * @param string|null $status   'open' | 'full' | null
     * @param string      $sortBy   'date' | 'titre'
     * @param string      $sortDir  'ASC' | 'DESC'
     *
     * @return JourneePorteOuverte[]
     */
    public function findFiltered(
        ?string $search = null,
        ?string $status = null,
        string $sortBy = 'date_evenement',
        string $sortDir = 'ASC',
        bool $history = false,
        ?int $userIdForRegistrations = null,
        ?int $createurId = null
    ): array {
        $qb = $this->createQueryBuilder('j');

        if ($userIdForRegistrations) {
            $qb->innerJoin('App\Entity\ParticipationJpo', 'p', 'WITH', 'p.idEvenement = j.idEvenement')
               ->andWhere('p.idUtilisateur = :regUserId')
               ->andWhere('p.statut != :annule')
               ->setParameter('regUserId', $userIdForRegistrations)
               ->setParameter('annule', 'annulé');
        }

        if ($createurId) {
            $qb->andWhere('j.idCreateur = :createurId')
               ->setParameter('createurId', $createurId);
        }

        if ($search) {
            $qb->andWhere('j.titre LIKE :search OR j.lieu LIKE :search OR j.description LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        $today = new \DateTime('today');
        if ($history) {
            $qb->andWhere('j.dateEvenement < :today')->setParameter('today', $today);
        } else {
            $qb->andWhere('j.dateEvenement >= :today')->setParameter('today', $today);
        }

        // Whitelist sort fields
        $allowedSort = ['dateEvenement' => 'j.dateEvenement', 'titre' => 'j.titre', 'participants' => 'j.currentParticipants'];
        $sortBy = match ($sortBy) {
            'titre' => 'j.titre',
            'participants' => 'j.currentParticipants',
            default => 'j.dateEvenement',
        };
        $sortDir = strtoupper($sortDir) === 'DESC' ? 'DESC' : 'ASC';

        $qb->orderBy($sortBy, $sortDir);

        $results = $qb->getQuery()->getResult();

        // Filter status in PHP (DQL doesn't handle computed columns easily)
        if ($status === 'open') {
            $results = array_filter($results, fn($e) => !$e->isFull());
        } elseif ($status === 'full') {
            $results = array_filter($results, fn($e) => $e->isFull());
        }

        return array_values($results);
    }

    /**
     * Return events grouped by date string (Y-m-d) for calendar dot rendering.
     *
     * @return array<string, JourneePorteOuverte[]>
     */
    public function findGroupedByDate(): array
    {
        $all = $this->findAll();
        $map = [];
        foreach ($all as $event) {
            $key = $event->getDateEvenement()->format('Y-m-d');
            $map[$key][] = $event;
        }
        return $map;
    }

    /**
     * Return all events for a specific date.
     *
     * @return JourneePorteOuverte[]
     */
    public function findByDate(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('j')
            ->where('j.dateEvenement = :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->orderBy('j.titre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find IDs of all events the user is registered for.
     *
     * @return int[]
     */
    public function findUserRegistrationIds(int $userId): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT id_evenement FROM participation_jpo WHERE id_utilisateur = ? AND statut != "annulé"';
        $rows = $conn->fetchAllAssociative($sql, [$userId]);
        return array_map(fn($r) => (int)$r['id_evenement'], $rows);
    }

    /**
     * Persist a new event.
     */
    public function save(JourneePorteOuverte $event): void
    {
        $em = $this->getEntityManager();
        $em->persist($event);
        $em->flush();
    }
    /**
     * Remove an existing event.
     */
    public function remove(JourneePorteOuverte $event): void
    {
        $em = $this->getEntityManager();
        $em->remove($event);
        $em->flush();
    }
}
