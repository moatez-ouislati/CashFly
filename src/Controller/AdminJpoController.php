<?php

namespace App\Controller;

use App\Entity\JourneePorteOuverte;
use App\Entity\ParticipationJpo;
use App\Entity\User;
use App\Entity\ListeAttente;
use App\Repository\JourneePorteOuverteRepository;
use App\Repository\UserRepository;
use App\Repository\ParticipationJpoRepository;
use App\Repository\ListeAttenteRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/jpo/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminJpoController extends AbstractController
{
    public function __construct(
        private readonly JourneePorteOuverteRepository $jpoRepository,
        private readonly UserRepository $UserRepository,
        private readonly ParticipationJpoRepository $participationRepository,
        private readonly ListeAttenteRepository $waitlistRepository,
        private readonly EntrepriseRepository $entrepriseRepository,
        private readonly EntityManagerInterface $em
    ) {}

    #[Route('/dashboard', name: 'jpo_dashboard_admin')]
    public function dashboard(): Response
    {
        return $this->render('jpo/admin_dashboard.html.twig');
    }

    #[Route('/events', name: 'jpo_admin_events_list')]
    public function eventsList(Request $request): Response
    {
        $search = $request->query->get('q', '');
        $dateStr = $request->query->get('date');
        $creatorId = $request->query->get('creator');
        $sortBy = $request->query->get('sort', 'dateEvenement');
        $sortDir = $request->query->get('dir', 'DESC');

        // Validate allowed sort fields to prevent injection or errors
        $allowedSorts = ['dateEvenement', 'currentParticipants', 'titre', 'lieu'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'dateEvenement';
        }

        $qb = $this->jpoRepository->createQueryBuilder('j');

        if ($search) {
            $qb->andWhere('j.titre LIKE :search OR j.lieu LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($dateStr) {
            $qb->andWhere('j.dateEvenement >= :dateStart AND j.dateEvenement < :dateEnd')
               ->setParameter('dateStart', $dateStr . ' 00:00:00')
               ->setParameter('dateEnd', $dateStr . ' 23:59:59');
        }

        if ($creatorId) {
            $qb->andWhere('j.idCreateur = :creatorId')
               ->setParameter('creatorId', $creatorId);
        }

        $qb->orderBy('j.' . $sortBy, $sortDir);

        $events = $qb->getQuery()->getResult();

        $proprietaires = $this->UserRepository->findBy(['roles' => 'proprietaire']);

        // Enrich events with counts
        $enrichedEvents = [];
        foreach ($events as $event) {
            $creator = $this->UserRepository->find($event->getIdCreateur());
            $participants = $this->participationRepository->findBy(['idEvenement' => $event->getIdEvenement()]);
            
            $statusCounts = [
                'confirmé' => 0,
                'en_attente' => 0,
                'annulé' => 0,
                'présent' => 0
            ];
            foreach ($participants as $p) {
                $statusCounts[$p->getStatut()]++;
            }

            $waitlistCount = count($this->waitlistRepository->findBy(['idEvenement' => $event->getIdEvenement()]));

            $enrichedEvents[] = [
                'entity' => $event,
                'creator' => $creator,
                'statusCounts' => $statusCounts,
                'waitlistCount' => $waitlistCount
            ];
        }

        return $this->render('jpo/admin_events_list.html.twig', [
            'events' => $enrichedEvents,
            'proprietaires' => $proprietaires,
            'search' => $search,
            'date' => $dateStr,
            'creatorId' => $creatorId,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
        ]);
    }

    #[Route('/event/create', name: 'jpo_admin_event_create', methods: ['GET', 'POST'])]
    public function createEvent(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $titre = $request->request->get('titre');
            $dateStr = $request->request->get('date_evenement');
            $lieu = $request->request->get('lieu');
            $description = $request->request->get('description');
            $maxParticipants = (int)$request->request->get('max_participants', 50);
            $idCreateur = (int)$request->request->get('id_createur');

            $event = new JourneePorteOuverte();
            $event->setTitre($titre);
            $event->setDateEvenement(new \DateTime($dateStr));
            $event->setLieu($lieu);
            $event->setDescription($description);
            $event->setMaxParticipants($maxParticipants);
            $event->setIdCreateur($idCreateur);

            $imageFile = $request->files->get('image');
            if ($imageFile) {
                $uploadDir = $this->getParameter('cashfly_events_dir');
                $filename = uniqid() . '_' . $imageFile->getClientOriginalName();
                $imageFile->move($uploadDir, $filename);
                $event->setImagePath('/cashfly/images/events/' . $filename);
            }

            $this->em->persist($event);
            $this->em->flush();

            return $this->redirectToRoute('jpo_admin_events_list');
        }

        $proprietaires = $this->UserRepository->findBy(['roles' => 'proprietaire']);
        return $this->render('jpo/admin_event_form.html.twig', [
            'proprietaires' => $proprietaires,
            'event' => null
        ]);
    }

    #[Route('/event/{id}/edit', name: 'jpo_admin_event_edit', methods: ['GET', 'POST'])]
    public function editEvent(int $id, Request $request): Response
    {
        $event = $this->jpoRepository->find($id);
        if (!$event) throw $this->createNotFoundException();

        if ($request->isMethod('POST')) {
            $event->setTitre($request->request->get('titre'));
            $event->setDateEvenement(new \DateTime($request->request->get('date_evenement')));
            $event->setLieu($request->request->get('lieu'));
            $event->setDescription($request->request->get('description'));
            $event->setMaxParticipants((int)$request->request->get('max_participants'));
            $event->setIdCreateur((int)$request->request->get('id_createur'));

            $imageFile = $request->files->get('image');
            if ($imageFile) {
                // Delete old image if exists
                if ($event->getImagePath()) {
                    $oldPath = $this->getParameter('kernel.project_dir') . '/public' . $event->getImagePath();
                    if (file_exists($oldPath)) unlink($oldPath);
                }

                $uploadDir = $this->getParameter('cashfly_events_dir');
                $filename = uniqid() . '_' . $imageFile->getClientOriginalName();
                $imageFile->move($uploadDir, $filename);
                $event->setImagePath('/cashfly/images/events/' . $filename);
            }

            $this->em->flush();
            return $this->redirectToRoute('jpo_admin_events_list');
        }

        $proprietaires = $this->UserRepository->findBy(['roles' => 'proprietaire']);
        return $this->render('jpo/admin_event_form.html.twig', [
            'proprietaires' => $proprietaires,
            'event' => $event
        ]);
    }

    #[Route('/event/{id}/delete', name: 'jpo_admin_event_delete', methods: ['POST'])]
    public function deleteEvent(int $id): JsonResponse
    {
        $event = $this->jpoRepository->find($id);
        if (!$event) return new JsonResponse(['error' => 'Not found'], 404);

        // Delete Image
        if ($event->getImagePath()) {
            $path = $this->getParameter('kernel.project_dir') . '/public' . $event->getImagePath();
            if (file_exists($path)) unlink($path);
        }

        // Cascade deletes
        $this->em->getConnection()->executeStatement('DELETE FROM participation_jpo WHERE id_evenement = ?', [$id]);
        $this->em->getConnection()->executeStatement('DELETE FROM liste_attente WHERE id_evenement = ?', [$id]);

        $this->em->remove($event);
        $this->em->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/event/{id}/participants', name: 'jpo_admin_event_participants', methods: ['GET', 'POST'])]
    public function eventParticipants(int $id, Request $request): Response
    {
        $event = $this->jpoRepository->find($id);
        if (!$event) throw $this->createNotFoundException();

        if ($request->isMethod('POST')) {
            $action = $request->request->get('action');
            $partId = $request->request->get('participation_id');
            $participation = $this->participationRepository->find($partId);

            if ($participation) {
                if ($action === 'update_status') {
                    $participation->setStatut($request->request->get('statut'));
                } elseif ($action === 'toggle_badge') {
                    $participation->setBadgeGenere(!$participation->isBadgeGenere());
                } elseif ($action === 'delete') {
                    $this->em->remove($participation);
                    $event->setCurrentParticipants($event->getCurrentParticipants() - 1);
                }
                $this->em->flush();
            }
            return $this->redirectToRoute('jpo_admin_event_participants', ['id' => $id]);
        }

        $participants = $this->participationRepository->findBy(['idEvenement' => $id]);
        $enrichedParticipants = [];
        foreach ($participants as $p) {
            $user = $this->UserRepository->find($p->getIdUtilisateur());
            $entreprise = $p->getIdEntreprise() ? $this->entrepriseRepository->find($p->getIdEntreprise()) : null;
            $enrichedParticipants[] = [
                'entity' => $p,
                'user' => $user,
                'entreprise' => $entreprise
            ];
        }

        return $this->render('jpo/admin_event_participants.html.twig', [
            'event' => $event,
            'participants' => $enrichedParticipants
        ]);
    }

    #[Route('/event/{id}/waitlist', name: 'jpo_admin_event_waitlist', methods: ['GET', 'POST'])]
    public function eventWaitlist(int $id, Request $request): Response
    {
        $event = $this->jpoRepository->find($id);
        if (!$event) throw $this->createNotFoundException();

        if ($request->isMethod('POST')) {
            $action = $request->request->get('action');
            $waitId = $request->request->get('waitlist_id');
            $waitEntry = $this->waitlistRepository->find($waitId);

            if ($waitEntry) {
                if ($action === 'promote') {
                    if ($event->getCurrentParticipants() < $event->getMaxParticipants()) {
                        $participation = new ParticipationJpo();
                        $participation->setIdEvenement($event->getIdEvenement());
                        $participation->setIdUtilisateur($waitEntry->getIdUtilisateur());
                        $participation->setStatut('confirmé');
                        $participation->setDateInscription(new \DateTime());
                        $participation->setBadgeGenere(false);

                        $this->em->persist($participation);
                        $this->em->remove($waitEntry);
                        $event->setCurrentParticipants($event->getCurrentParticipants() + 1);
                        $this->em->flush();
                    }
                } elseif ($action === 'remove') {
                    $this->em->remove($waitEntry);
                    $this->em->flush();
                }
            }
            return $this->redirectToRoute('jpo_admin_event_waitlist', ['id' => $id]);
        }

        $waitlist = $this->waitlistRepository->findBy(['idEvenement' => $id], ['position' => 'ASC']);
        $pendingParticipants = $this->participationRepository->findBy([
            'idEvenement' => $id,
            'statut' => 'en_attente'
        ]);

        $enrichedWaitlist = [];
        
        // Add pending participants first (usually they have priority or need approval)
        foreach ($pendingParticipants as $p) {
            $user = $this->UserRepository->find($p->getIdUtilisateur());
            $enrichedWaitlist[] = [
                'type' => 'pending',
                'entity' => $p,
                'user' => $user,
                'date' => $p->getDateInscription()
            ];
        }

        // Add waitlist entries
        foreach ($waitlist as $w) {
            $user = $this->UserRepository->find($w->getIdUtilisateur());
            $enrichedWaitlist[] = [
                'type' => 'waitlist',
                'entity' => $w,
                'user' => $user,
                'date' => $w->getDateDemande()
            ];
        }

        return $this->render('jpo/admin_event_waitlist.html.twig', [
            'event' => $event,
            'waitlist' => $enrichedWaitlist
        ]);
    }
}
