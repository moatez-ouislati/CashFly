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
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\ParticipationJpoRepository;
use App\Repository\UtilisateurRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Dompdf\Dompdf;
use Dompdf\Options;
 
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
    public function myEvents(Request $request, PaginatorInterface $paginator): Response
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
        $limit   = max(1, min(100, $request->query->getInt('limit', 10)));
        $history = $request->query->getBoolean('history', false);

        // AJAX branch: still uses the array-returning method for the Stimulus search controller
        if ($request->isXmlHttpRequest()) {
            $allEvents = $this->jpoRepository->findFiltered(
                $search ?: null,
                null,
                $sortBy,
                $sortDir,
                $history,
                null,
                $user->getId()
            );
            $totalItems = count($allEvents);
            $totalPages = (int) ceil($totalItems / $limit);
            $page = max(1, min($page, $totalPages ?: 1));
            $eventsSlice = array_slice($allEvents, ($page - 1) * $limit, $limit);

            $data = array_map(fn($e) => [
                'id'                   => $e->getIdEvenement(),
                'titre'                => $e->getTitre(),
                'date_evenement'       => $e->getDateEvenement()->format('Y-m-d'),
                'lieu'                 => $e->getLieu(),
                'description'          => $e->getDescription(),
                'image_path'           => $e->getImagePath(),
                'max_participants'     => $e->getMaxParticipants(),
                'current_participants' => $e->getCurrentParticipants(),
                'is_locked'            => (($e->getDateEvenement()->getTimestamp() - time()) / 3600 < 24 && ($e->getDateEvenement()->getTimestamp() - time()) >= 0),
                'details_url'          => $this->generateUrl('jpo_event_details', ['id' => $e->getIdEvenement()]),
            ], $eventsSlice);

            return new JsonResponse([
                'events'      => $data,
                'currentPage' => $page,
                'totalPages'  => $totalPages,
            ]);
        }

        // Full-page render: use KnpPaginator with a Doctrine Query
        $query = $this->jpoRepository->findFilteredQuery(
            $search ?: null,
            $sortBy,
            $sortDir,
            $history,
            $user->getId()
        );

        $events = $paginator->paginate($query, $page, $limit, [
            // We handle sorting ourselves in findFilteredQuery() via the ?sort=&dir= params.
            // Disable KnpPaginator's auto-sort so it doesn't try to reinterpret ?sort=dateEvenement
            // as a bare DQL field (it needs the alias prefix j.dateEvenement to be valid).
            'sortFieldWhitelist' => [],
        ]);

        return $this->render('jpo/my_events.html.twig', [
            'events'  => $events,
            'search'  => $search,
            'sortBy'  => $sortBy,
            'sortDir' => $sortDir,
            'limit'   => $limit,
            'history' => $history,
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
            'lieu'             => $e->getLieu(),
            'id_createur'      => $e->getIdCreateur(),
            'max_participants' => $e->getMaxParticipants(),
            'image_path'       => $e->getImagePath(),
            'is_locked'        => (($e->getDateEvenement()->getTimestamp() - time()) / 3600 < 24 && ($e->getDateEvenement()->getTimestamp() - time()) >= 0),
            'details_url'      => $this->generateUrl('jpo_event_details', ['id' => $e->getIdEvenement()]),
        ], $events);

        return new JsonResponse($data);
    }

    /**
     * API: Sign up to an event (investisseur)
     */
    #[Route('/register/{id}', name: 'register', methods: ['POST'])]
    #[IsGranted('ROLE_INVESTISSEUR')]
    public function register(int $id, EntityManagerInterface $em, \App\Service\DiscoveryService $discoveryService): JsonResponse
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

        // Get AI Suggestion
        $suggestion = $discoveryService->getNearbySuggestion($event);
        $suggestionData = null;
        if ($suggestion) {
            $suggestedEvent = $suggestion['event'];
            $suggestionData = [
                'id' => $suggestedEvent->getIdEvenement(),
                'titre' => $suggestedEvent->getTitre(),
                'lieu' => $suggestedEvent->getLieu(),
                'date' => $suggestedEvent->getDateEvenement()->format('d/m/Y'),
                'image' => $suggestedEvent->getImagePath(),
                'hook' => $suggestion['hook']
            ];
        }

        return new JsonResponse([
            'success' => true,
            'suggestion' => $suggestionData
        ]);
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

        // Simple Geocoding for AI Discovery
        if ($lieu) {
            $lowerLieu = strtolower($lieu);
            if (str_contains($lowerLieu, 'paris')) {
                $event->setLatitude(48.8566); $event->setLongitude(2.3522);
            } elseif (str_contains($lowerLieu, 'tunis')) {
                $event->setLatitude(36.8065); $event->setLongitude(10.1815);
            } else {
                // Mock coordinate near Tunis for the demo if unknown
                $event->setLatitude(36.8000 + (rand(-100, 100) / 2000));
                $event->setLongitude(10.1800 + (rand(-100, 100) / 2000));
            }
        }

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

        $now = new \DateTime();
        $intervalHours = ($event->getDateEvenement()->getTimestamp() - $now->getTimestamp()) / 3600;
        if ($intervalHours < 24 && $intervalHours >= 0) {
            return new JsonResponse(['error' => 'lockdown'], 400);
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

        // Update geocoding if location name changed
        if ($lieu) {
            $lowerLieu = strtolower($lieu);
            if (str_contains($lowerLieu, 'paris')) {
                $event->setLatitude(48.8566); $event->setLongitude(2.3522);
            } elseif (str_contains($lowerLieu, 'tunis')) {
                $event->setLatitude(36.8065); $event->setLongitude(10.1815);
            } else {
                // Keep jitter for variety
                $event->setLatitude(36.8000 + (rand(-100, 100) / 2000));
                $event->setLongitude(10.1800 + (rand(-100, 100) / 2000));
            }
        }

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

        $now = new \DateTime();
        $intervalHours = ($event->getDateEvenement()->getTimestamp() - $now->getTimestamp()) / 3600;
        if ($intervalHours < 24 && $intervalHours >= 0) {
            return new JsonResponse(['error' => 'lockdown'], 400);
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
        $hasBadge = false;
        if ($user) {
            // Check participation directly
            $em = $this->jpoRepository->getEntityManager();
            $participation = $em->getRepository(ParticipationJpo::class)->findOneBy([
                'idEvenement' => $event->getIdEvenement(),
                'idUtilisateur' => $user->getId()
            ]);
            if ($participation) {
                $isRegistered = true;
                $hasBadge = $participation->isBadgeGenere();
            }
        }
        
        $now = new \DateTime();
        $eventStart = $event->getDateEvenement();
        $intervalHours = ($eventStart->getTimestamp() - $now->getTimestamp()) / 3600;
        $isWithin24h = ($intervalHours > 0 && $intervalHours < 24);
        
        $chatReadOnly = $now > (clone $eventStart)->modify('+12 hours');

        return $this->render('jpo/event_details.html.twig', [
            'event' => $event,
            'isRegistered' => $isRegistered,
            'hasBadge' => $hasBadge,
            'isWithin24h' => $isWithin24h,
            'chatReadOnly' => $chatReadOnly,
            'isOwner' => $user && $event->getIdCreateur() === $user->getId(),
            'deadlineTimestamp' => $eventStart->getTimestamp() - 86400 // -24 hours
        ]);
    }

    /**
     * Export event participants to Excel (Propriétaire only)
     */
    #[Route('/export/{id}', name: 'export_excel', methods: ['GET'])]
    #[IsGranted('ROLE_PROPRIETAIRE')]
    public function exportExcel(int $id, ParticipationJpoRepository $partRepo, UtilisateurRepository $userRepo): Response
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        $event = $this->jpoRepository->find($id);

        if (!$event) {
            throw $this->createNotFoundException('Événement non trouvé');
        }

        if (!$user || $event->getIdCreateur() !== $user->getId()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas le créateur de cet événement.');
        }

        $participants = $partRepo->findBy(['idEvenement' => $id]);

        if (empty($participants)) {
            $this->addFlash('error', 'Aucun participant inscrit. Impossible de générer l\'export.');
            return $this->redirectToRoute('jpo_event_details', ['id' => $id]);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Liste des Inscrits');

        $sheet->setCellValue('A1', 'Rapport d\'inscriptions : ' . $event->getTitre());
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->setColor(new Color(Color::COLOR_WHITE));
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF0F172A');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(35);

        $sheet->setCellValue('A2', 'Généré le : ' . date('d/m/Y H:i'));
        $sheet->mergeCells('A2:E2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setColor(new Color('FF64748B'));
        
        $headerRow = 4;
        $headers = ['A' => 'Nom et Prénom', 'B' => 'Email', 'C' => 'Date d\'inscription', 'D' => 'Statut Inscription', 'E' => 'Statut Badge'];
        foreach ($headers as $col => $title) {
            $sheet->setCellValue($col . $headerRow, $title);
        }
        
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0EA5E9']], 
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle('A4:E4')->applyFromArray($headerStyle);

        $row = 5;
        foreach ($participants as $p) {
            $participantUser = $userRepo->find($p->getIdUtilisateur());
            if ($participantUser) {
                $sheet->setCellValue('A' . $row, $participantUser->getNomComplet() ?: 'Non renseigné');
                $sheet->setCellValue('B' . $row, $participantUser->getEmail());
                $sheet->setCellValue('C' . $row, $p->getDateInscription() ? $p->getDateInscription()->format('d/m/Y H:i') : 'N/A');
                $sheet->setCellValue('D' . $row, ucfirst($p->getStatut() ?? 'Confirmé'));
                $sheet->setCellValue('E' . $row, $p->isBadgeGenere() ? 'Généré' : 'Non généré');
                
                $sheet->getStyle('C'.$row.':E'.$row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $row++;
            }
        }

        if ($row > 5) {
            $bodyStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFCBD5E1'],
                    ],
                ],
            ];
            $sheet->getStyle('A5:E' . ($row - 1))->applyFromArray($bodyStyle);
            
            for ($i = 5; $i < $row; $i++) {
                if ($i % 2 == 0) {
                    $sheet->getStyle('A'.$i.':E'.$i)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
                }
            }
        }

        foreach (range('A','E') as $colId) {
            $sheet->getColumnDimension($colId)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Inscrits_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $event->getTitre()) . '_' . date('Ymd') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        $writer->save($temp_file);
        
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_ATTACHMENT);
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

        $intervalHours = ($event->getDateEvenement()->getTimestamp() - $now->getTimestamp()) / 3600;
        if ($intervalHours < 24 && $intervalHours >= 0) {
            return new JsonResponse(['error' => 'Désinscription impossible : l\'événement commence en moins de 24 heures.'], 400);
        }

        $participationRepo = $em->getRepository(ParticipationJpo::class);
        $participation = $participationRepo->findOneBy([
            'idEvenement' => $id,
            'idUtilisateur' => $user->getId()
        ]);

        if (!$participation) {
            return new JsonResponse(['error' => 'Inscription non trouvée'], 404);
        }

        if ($participation->isBadgeGenere()) {
            return new JsonResponse(['error' => 'Désinscription impossible : le badge a déjà été généré.'], 400);
        }

        try {
            $em->remove($participation);
            $event->setCurrentParticipants(max(0, $event->getCurrentParticipants() - 1));
            $em->flush();
            return new JsonResponse(['success' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la désinscription : ' . $e->getMessage()], 500);
        }
    }

    /**
     * API: Generate Badge (Investisseur)
     */
    #[Route('/generate-badge/{id}', name: 'generate_badge', methods: ['GET'])]
    #[IsGranted('ROLE_INVESTISSEUR')]
    public function generateBadge(int $id, EntityManagerInterface $em): Response
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        $event = $this->jpoRepository->find($id);

        if (!$event || !$user) {
            throw $this->createNotFoundException('Événement ou utilisateur non trouvé');
        }

        $now = new \DateTime();
        $intervalHours = ($event->getDateEvenement()->getTimestamp() - $now->getTimestamp()) / 3600;

        if ($intervalHours < 24 && $intervalHours >= 0) {
            $this->addFlash('error', 'Génération impossible : Evénement dans moins de 24h.');
            return $this->redirectToRoute('jpo_event_details', ['id' => $id]);
        }

        $participationRepo = $em->getRepository(ParticipationJpo::class);
        $participation = $participationRepo->findOneBy([
            'idEvenement' => $id,
            'idUtilisateur' => $user->getId()
        ]);

        if (!$participation) {
            $this->addFlash('error', 'Vous n\'êtes pas inscrit à cet événement.');
            return $this->redirectToRoute('jpo_event_details', ['id' => $id]);
        }

        // Payload
        $timestamp = date('c');
        $payloadArray = [
            'investorId' => (string)$user->getId(),
            'eventId' => (string)$event->getIdEvenement(),
            'timestamp' => $timestamp
        ];
        
        $secret = $_ENV['APP_SECRET'] ?? 'cashfly_secret';
        $signature = hash_hmac('sha256', json_encode($payloadArray), $secret);
        $payloadArray['signature'] = $signature;
        
        $qrData = json_encode($payloadArray);

        $builder = new Builder(
            writer: new PngWriter(),
            data: $qrData,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );
        $result = $builder->build();

        $qrImageData = $result->getString();

        $width = 400;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);

        // Enable alpha blending for the logo just in case
        imagealphablending($image, true);

        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 15, 23, 42);
        $gray = imagecolorallocate($image, 100, 116, 139);
        $green = imagecolorallocate($image, 0, 193, 106);

        imagefill($image, 0, 0, $white);

        $fontPath = $this->getParameter('kernel.project_dir') . '/vendor/endroid/qr-code/assets/open_sans.ttf';

        // Header Rect
        imagefilledrectangle($image, 0, 0, $width, 100, $black);
        imagerectangle($image, 0, 0, $width - 1, $height - 1, $black);

        // Logo
        $logoPath = 'C:\\xampp\\htdocs\\img\\Logo4.png';
        if (file_exists($logoPath)) {
            $logoImage = imagecreatefrompng($logoPath);
            $logoW = imagesx($logoImage);
            $logoH = imagesy($logoImage);
            $targetH = 40;
            $targetW = (int)($logoW * ($targetH / $logoH));
            $logoX = ($width - $targetW) / 2;
            imagecopyresampled($image, $logoImage, (int)$logoX, 30, 0, 0, $targetW, $targetH, $logoW, $logoH);
            imagedestroy($logoImage);
        } else {
            imagettftext($image, 20, 0, 135, 60, $white, $fontPath, "CASHFLY");
        }

        // Event Name
        $rawEventName = mb_convert_encoding($event->getTitre() ?? '', 'ISO-8859-1', 'UTF-8');
        $wrappedEventName = wordwrap($rawEventName, 35, "\n", true);
        $lines = explode("\n", $wrappedEventName);
        
        $y = 135;
        foreach (array_slice($lines, 0, 3) as $line) { // limit to 3 lines just in case
            imagettftext($image, 14, 0, 30, $y, $black, $fontPath, $line);
            $y += 22;
        }
        
        // Event Date and Location
        $y += 5;
        imagettftext($image, 11, 0, 30, $y, $gray, $fontPath, $event->getDateEvenement()->format('d/m/Y'));
        
        $y += 20;
        $lieu = mb_strimwidth($event->getLieu() ?: 'Lieu à confirmer', 0, 45, '...');
        $lieuISO = mb_convert_encoding($lieu, 'ISO-8859-1', 'UTF-8');
        imagettftext($image, 11, 0, 30, $y, $gray, $fontPath, $lieuISO);

        // QR Code
        $qrCodeGd = imagecreatefromstring($qrImageData);
        imagecopy($image, $qrCodeGd, 50, 220, 0, 0, 300, 300);

        // User Info
        $userName = mb_substr($user->getNomComplet() ?: $user->getEmail(), 0, 30);
        $userNameISO = mb_convert_encoding($userName, 'ISO-8859-1', 'UTF-8');
        imagettftext($image, 16, 0, 30, 545, $black, $fontPath, "Investisseur: " . $userNameISO);
        
        $rawStatus = strtolower($participation->getStatut() ?? 'confirmé');
        $displayStatus = 'Confirmé';
        $statusColor = $green;

        if (str_contains($rawStatus, 'attente')) {
            $displayStatus = 'En attente';
            $orange = imagecolorallocate($image, 245, 158, 11);
            $statusColor = $orange;
        } elseif (str_contains($rawStatus, 'annul')) {
            $displayStatus = 'Annulé';
            $red = imagecolorallocate($image, 239, 68, 68);
            $statusColor = $red;
        } elseif (str_contains($rawStatus, 'confirm')) {
            $displayStatus = 'Confirmé';
            $statusColor = $green;
        } else {
            $displayStatus = ucfirst($rawStatus);
        }

        $displayStatusISO = mb_convert_encoding("Status: " . $displayStatus, 'ISO-8859-1', 'UTF-8');
        imagettftext($image, 12, 0, 30, 570, $statusColor, $fontPath, $displayStatusISO);

        ob_start();
        imagejpeg($image, null, 100);
        $jpgData = ob_get_clean();
        
        imagedestroy($image);
        imagedestroy($qrCodeGd);

        if (!$participation->isBadgeGenere()) {
            $participation->setBadgeGenere(true);
            $em->flush();
        }

        $fileName = 'Badge_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $event->getTitre()) . '_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $user->getNomComplet() ?: 'User') . '.jpg';

        return new Response($jpgData, 200, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }

    /**
     * API: Verify Badge Page (Security Staff)
     */
    #[Route('/verify-badge/{eventId}/{investorId}', name: 'verify_badge', methods: ['GET'])]
    public function verifyBadge(int $eventId, int $investorId, EntityManagerInterface $em, UtilisateurRepository $userRepo): Response
    {
        $event = $this->jpoRepository->find($eventId);
        $user = $userRepo->find($investorId);

        $status = 'desinscrit';
        
        if ($event && $user) {
            $participationRepo = $em->getRepository(ParticipationJpo::class);
            $participation = $participationRepo->findOneBy([
                'idEvenement' => $eventId,
                'idUtilisateur' => $investorId
            ]);
            
            if ($participation) {
                if ($participation->isBadgeGenere()) {
                    $status = 'valide';
                } else {
                    $status = 'non_genere';
                }
            }
        }

        return $this->render('jpo/badge_scan_result.html.twig', [
            'status' => $status,
            'nomComplet' => $user ? ($user->getNomComplet() ?: $user->getEmail()) : 'Inconnu',
            'eventTitre' => $event ? $event->getTitre() : 'Événement Inconnu',
            'eventDate' => $event ? $event->getDateEvenement()->format('d/m/Y H:i') : 'N/A'
        ]);
    }
}