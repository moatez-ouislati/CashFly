<?php

namespace App\Controller;

use App\Entity\JourneePorteOuverte;
use App\Entity\ParticipationJpo;
use App\Entity\Utilisateur;
use App\Repository\JourneePorteOuverteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Security\Http\Attribute\IsGranted;
 
#[Route('/jpo', name: 'jpo_')]
class JpoController extends AbstractController
{
    public function __construct(
        private readonly JourneePorteOuverteRepository $jpoRepository,
    ) {}

    /**
     * Proprietaire dashboard — includes the calendar overlay + event creation popup.
     */
    #[Route('/dashboard/proprietaire', name: 'dashboard_proprietaire')]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function dashboardProprietaire(): Response
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        $byDate = $this->jpoRepository->findGroupedByDate();
        $calendarData = [];
        foreach ($byDate as $dateKey => $events) {
            $calendarData[$dateKey] = array_map(fn($e) => [
                'id'          => $e->getIdEvenement(),
                'titre'       => $e->getTitre(),
                'lieu'        => $e->getLieu(),
                'id_createur' => $e->getIdCreateur(),
            ], $events);
        }

        return $this->render('jpo/proprietaire_dashboard.html.twig', [
            'calendarData'  => $calendarData,
            'currentUserId' => $user ? $user->getId() : null,
        ]);
    }

    /**
     * List of events created by the current user.
     */
    #[Route('/dashboard/proprietaire/my-events', name: 'my_events')]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function myEvents(Request $request): Response
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $search  = $request->query->get('q', '');
        $sortBy  = $request->query->get('sort', 'dateEvenement');
        $sortDir = $request->query->get('dir', 'DESC');
        $page    = $request->query->getInt('page', 1);
        $limit   = $request->query->getInt('limit', 10);
        $history = $request->query->getBoolean('history', false);
 
        $allEvents = $this->jpoRepository->findFiltered(
            $search ?: null,
            null,
            $sortBy,
            $sortDir,
            $history,
            null,
            $user->getId()
        );

        // Simple manual pagination
        $totalItems = count($allEvents);
        $totalPages = ceil($totalItems / $limit);
        $page = max(1, min($page, $totalPages ?: 1));
        $offset = ($page - 1) * $limit;
        $eventsSlice = array_slice($allEvents, $offset, $limit);

        if ($request->isXmlHttpRequest()) {
            $data = array_map(fn($e) => [
                'id'                   => $e->getIdEvenement(),
                'titre'                => $e->getTitre(),
                'date_evenement'       => $e->getDateEvenement()->format('Y-m-d'),
                'lieu'                 => $e->getLieu(),
                'description'          => $e->getDescription(),
                'image_path'           => $e->getImagePath(),
                'max_participants'     => $e->getMaxParticipants(),
                'current_participants' => $e->getCurrentParticipants(),
                'details_url'          => $this->generateUrl('jpo_event_details', ['id' => $e->getIdEvenement()]),
            ], $eventsSlice);

            return new JsonResponse([
                'events'      => $data,
                'currentPage' => $page,
                'totalPages'  => $totalPages,
            ]);
        }

        return $this->render('jpo/my_events.html.twig', [
            'events'      => $eventsSlice,
            'search'      => $search,
            'sortBy'      => $sortBy,
            'sortDir'     => $sortDir,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
            'limit'       => $limit,
            'history'     => $history,
        ]);
    }

    /**
     * Investisseur dashboard — includes the events list inline.
     */
    #[Route('/dashboard/investisseur', name: 'dashboard_investisseur')]
    #[IsGranted('ROLE_INVESTISSEUR')]
    public function dashboardInvestisseur(Request $request): Response
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        $search  = $request->query->get('q', '');
        $status  = $request->query->get('status', '');
        $sortBy  = $request->query->get('sort', 'dateEvenement');
        $sortDir = $request->query->get('dir', 'ASC');
        $history = $request->query->getBoolean('history', false);
        $mine    = $request->query->get('mine'); // Get raw to check if provided
        
        // If history is requested and mine was NOT explicitly provided, default to true
        if ($history && $mine === null) {
            $mineValue = true;
        } else {
            $mineValue = $request->query->getBoolean('mine', false);
        }

        if ($request->isXmlHttpRequest()) {
            $events = $this->jpoRepository->findFiltered(
                $search ?: null,
                $status ?: null,
                $sortBy,
                $sortDir,
                $history,
                ($mineValue && $user) ? $user->getId() : null
            );

            $registrationIds = $user ? $this->jpoRepository->findUserRegistrationIds($user->getId()) : [];

            $data = array_map(fn($e) => [
                'id'                   => $e->getIdEvenement(),
                'titre'                => $e->getTitre(),
                'date_evenement'       => $e->getDateEvenement()->format('Y-m-d'),
                'lieu'                 => $e->getLieu(),
                'description'          => $e->getDescription(),
                'image_path'           => $e->getImagePath(),
                'max_participants'     => $e->getMaxParticipants(),
                'current_participants' => $e->getCurrentParticipants(),
                'is_full'              => $e->isFull(),
                'is_past'              => $e->getDateEvenement() < new \DateTime('today'),
                'is_registered'        => in_array($e->getIdEvenement(), $registrationIds, true),
                'details_url'          => $this->generateUrl('jpo_event_details', ['id' => $e->getIdEvenement()]),
            ], $events);
            return new JsonResponse($data);
        }

        $events = $this->jpoRepository->findFiltered(
            $search ?: null,
            $status ?: null,
            $sortBy,
            $sortDir,
            $history,
            ($mineValue && $user) ? $user->getId() : null
        );

        $registrationIds = [];
        if ($user) {
            $registrationIds = $this->jpoRepository->findUserRegistrationIds($user->getId());
        }

        return $this->render('jpo/investisseur_dashboard.html.twig', [
            'events'  => $events,
            'search'  => $search,
            'status'  => $status,
            'sortBy'  => $sortBy,
            'sortDir' => $sortDir,
            'history' => $history,
            'mine'    => $mine,
            'registrationIds' => $registrationIds,
        ]);
    }

    /**
     * API: return events for a given date (YYYY-MM-DD).
     */
    #[Route('/events-on-day', name: 'events_on_day', methods: ['GET'])]
    public function eventsOnDay(Request $request): JsonResponse
    {
        $dateStr = $request->query->get('date', '');
        try {
            $date = new \DateTime($dateStr);
        } catch (\Exception) {
            return new JsonResponse(['error' => 'Invalid date'], 400);
        }

        $events = $this->jpoRepository->findByDate($date);
        $data = array_map(fn($e) => [
            'id'               => $e->getIdEvenement(),
            'titre'            => $e->getTitre(),
            'date'             => $e->getDateEvenement()->format('d/m/Y'),
            'id_createur'      => $e->getIdCreateur(),
            'description'      => $e->getDescription(),
            'max_participants' => $e->getMaxParticipants(),
            'image_path'       => $e->getImagePath(),
            'details_url'      => $this->generateUrl('jpo_event_details', ['id' => $e->getIdEvenement()]),
        ], $events);

        return new JsonResponse($data);
    }

    /**
     * API: Sign up to an event (investisseur)
     */
    #[Route('/register/{id}', name: 'register', methods: ['POST'])]
    #[IsGranted('ROLE_INVESTISSEUR')]
    public function register(int $id, EntityManagerInterface $em): JsonResponse
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        if (!$user || !in_array('ROLE_INVESTISSEUR', $user->getRoles(), true)) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }

        $event = $this->jpoRepository->find($id);

        if (!$event) {
            return new JsonResponse(['error' => 'Événement non trouvé'], 404);
        }

        $now = new \DateTime();
        if ($event->getDateEvenement() <= $now) {
            return new JsonResponse(['error' => 'L\'événement est déjà passé'], 400);
        }

        if ($event->isFull()) {
            return new JsonResponse(['error' => 'L\'événement est complet'], 400);
        }

        $registrationIds = $this->jpoRepository->findUserRegistrationIds($user->getId());
        if (in_array($event->getIdEvenement(), $registrationIds, true)) {
            return new JsonResponse(['error' => 'Vous êtes déjà inscrit à cet événement'], 400);
        }

        // Add participation
        $participation = new ParticipationJpo();
        $participation->setIdEvenement($event->getIdEvenement());
        $participation->setIdUtilisateur($user->getId());
        $participation->setDateInscription(new \DateTime());
        $participation->setStatut('confirmé');
        $participation->setBadgeGenere(false);

        // Update event participant count
        $event->setCurrentParticipants($event->getCurrentParticipants() + 1);

        $em->persist($participation);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    /**
     * API: create a new event (proprietaire only).
     */
    #[Route('/create-event', name: 'create_event', methods: ['POST'])]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function createEvent(Request $request): JsonResponse
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        if (!$user || !in_array('ROLE_PROPRIETAIRE', $user->getRoles(), true)) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }

        $titre           = trim($request->request->get('titre', ''));
        $dateStr         = trim($request->request->get('date_evenement', ''));
        $lieu            = trim($request->request->get('lieu', ''));
        $description     = trim($request->request->get('description', ''));
        $maxParticipants = (int) $request->request->get('max_participants', 50);

        if (!$titre || !$dateStr) {
            return new JsonResponse(['error' => 'Titre et date requis'], 400);
        }

        try {
            $date = new \DateTime($dateStr);
        } catch (\Exception) {
            return new JsonResponse(['error' => 'Date invalide'], 400);
        }

        $imagePath = null;
        $imageFile = $request->files->get('image');
        if ($imageFile) {
            $uploadDir = $this->getParameter('cashfly_events_dir');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $imageFile->getClientOriginalName());
            $imageFile->move($uploadDir, $filename);
            $imagePath = '/cashfly/images/events/' . $filename;
        }

        $event = new JourneePorteOuverte();
        $event->setTitre($titre);
        $event->setDateEvenement($date);
        $event->setLieu($lieu ?: null);
        $event->setDescription($description ?: null);
        $event->setMaxParticipants($maxParticipants > 0 ? $maxParticipants : 50);
        $event->setImagePath($imagePath);
        $event->setIdCreateur($user->getId());

        $this->jpoRepository->save($event);

        return new JsonResponse([
            'success' => true,
            'id'      => $event->getIdEvenement(),
            'titre'   => $event->getTitre(),
            'date'    => $event->getDateEvenement()->format('d/m/Y'),
        ]);
    }

    /**
     * API: edit an existing event (creator only).
     */
    #[Route('/edit-event/{id}', name: 'edit_event', methods: ['POST'])]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function editEvent(int $id, Request $request): JsonResponse
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        $event = $this->jpoRepository->find($id);

        if (!$event) {
            return new JsonResponse(['error' => 'Événement non trouvé'], 404);
        }

        if (!$user || $event->getIdCreateur() !== $user->getId()) {
            return new JsonResponse(['error' => 'Forbidden: Vous n\'êtes pas le créateur de cet événement'], 403);
        }

        $titre           = trim($request->request->get('titre', ''));
        $lieu            = trim($request->request->get('lieu', ''));
        $description     = trim($request->request->get('description', ''));
        $maxParticipants = (int) $request->request->get('max_participants', $event->getMaxParticipants());

        if (!$titre) {
            return new JsonResponse(['error' => 'Titre requis'], 400);
        }

        $imageFile = $request->files->get('image');
        if ($imageFile) {
            $uploadDir = $this->getParameter('cashfly_events_dir');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $imageFile->getClientOriginalName());
            $imageFile->move($uploadDir, $filename);
            $event->setImagePath('/cashfly/images/events/' . $filename);
        }

        $event->setTitre($titre);
        $event->setLieu($lieu ?: null);
        $event->setDescription($description ?: null);
        $event->setMaxParticipants($maxParticipants > 0 ? $maxParticipants : 50);

        $this->jpoRepository->save($event);

        return new JsonResponse([
            'success' => true,
            'id'      => $event->getIdEvenement(),
            'titre'   => $event->getTitre(),
            'date'    => $event->getDateEvenement()->format('d/m/Y'),
        ]);
    }

    /**
     * API: delete an event (creator only).
     */
    #[Route('/delete-event/{id}', name: 'delete_event', methods: ['POST'])]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function deleteEvent(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        $event = $this->jpoRepository->find($id);

        if (!$event) {
            return new JsonResponse(['error' => 'Événement non trouvé'], 404);
        }

        if (!$user || $event->getIdCreateur() !== $user->getId()) {
            return new JsonResponse(['error' => 'Forbidden: Vous n\'êtes pas le créateur de cet événement'], 403);
        }

        $force = $request->request->getBoolean('force', false);
        $participantCount = $event->getCurrentParticipants();

        if ($participantCount > 0 && !$force) {
            return new JsonResponse([
                'error' => 'has_participants',
                'count' => $participantCount
            ]);
        }

        // Manual Cascade Delete for participation_jpo
        $em = $this->jpoRepository->getEntityManager();
        $conn = $em->getConnection();
        
        try {
            // 1. Delete associated participations
            $conn->executeStatement('DELETE FROM participation_jpo WHERE id_evenement = ?', [$id]);
            
            // 2. Remove the event entity
            $this->jpoRepository->remove($event);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la suppression en cascade : ' . $e->getMessage()], 500);
        }

        return new JsonResponse(['success' => true]);
    }

    /**
     * Dedicated event details page.
     */
    #[Route('/details/{id}', name: 'event_details')]
    public function eventDetails(int $id): Response
    {
        $event = $this->jpoRepository->find($id);
        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé');
        }

        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        $isRegistered = false;
        if ($user) {
            $regIds = $this->jpoRepository->findUserRegistrationIds($user->getId());
            $isRegistered = in_array($event->getIdEvenement(), $regIds, true);
        }

        return $this->render('jpo/event_details.html.twig', [
            'event' => $event,
            'isRegistered' => $isRegistered,
            'isOwner' => $user && $event->getIdCreateur() === $user->getId(),
        ]);
    }

    /**
     * API: Unregister from an event (investisseur)
     */
    #[Route('/unregister/{id}', name: 'unregister', methods: ['POST'])]
    #[IsGranted('ROLE_INVESTISSEUR')]
    public function unregister(int $id, EntityManagerInterface $em): JsonResponse
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Forbidden'], 403);
        }

        $event = $this->jpoRepository->find($id);
        if (!$event) {
            return new JsonResponse(['error' => 'Événement non trouvé'], 404);
        }

        $now = new \DateTime();
        if ($event->getDateEvenement() <= $now) {
            return new JsonResponse(['error' => 'L\'événement est déjà passé'], 400);
        }

        $conn = $em->getConnection();
        
        try {
            $deleted = $conn->executeStatement(
                'DELETE FROM participation_jpo WHERE id_evenement = ? AND id_utilisateur = ?',
                [$id, $user->getId()]
            );

            if ($deleted > 0) {
                $event->setCurrentParticipants(max(0, $event->getCurrentParticipants() - 1));
                $em->flush();
                return new JsonResponse(['success' => true]);
            }
            
            return new JsonResponse(['error' => 'Inscription non trouvée'], 404);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la désinscription : ' . $e->getMessage()], 500);
        }
    }
}