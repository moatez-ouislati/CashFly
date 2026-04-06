<?php

namespace App\Controller;

use App\Entity\JourneePorteOuverte;
use App\Entity\ParticipationJpo;
use App\Form\JourneePorteOuverteType;
use App\Repository\JourneePorteOuverteRepository;
use App\Repository\ParticipationJpoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/jpo')]
class JourneePorteOuverteController extends AbstractController
{
    #[Route('/{id}/participer', name: 'app_jpo_participer', methods: ['POST'])]
    public function participer(JourneePorteOuverte $jpo, EntityManagerInterface $entityManager, ParticipationJpoRepository $participationRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifier si déjà inscrit
        $existing = $participationRepository->findOneBy([
            'evenement' => $jpo,
            'utilisateur' => $user
        ]);

        if ($existing) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à cet événement.');
            return $this->redirectToRoute('app_jpo_show', ['id' => $jpo->getId()]);
        }

        // Vérifier la capacité
        if ($jpo->getCurrentParticipants() >= $jpo->getMaxParticipants()) {
            $this->addFlash('error', 'Désolé, cet événement est complet.');
            return $this->redirectToRoute('app_jpo_show', ['id' => $jpo->getId()]);
        }

        $participation = new ParticipationJpo();
        $participation->setEvenement($jpo);
        $participation->setUtilisateur($user);
        $participation->setRole('VISITEUR');
        $participation->setStatut('CONFIRME');

        $jpo->setCurrentParticipants($jpo->getCurrentParticipants() + 1);

        $entityManager->persist($participation);
        $entityManager->flush();

        $this->addFlash('success', 'Votre réservation a été confirmée !');
        return $this->redirectToRoute('app_jpo_show', ['id' => $jpo->getId()]);
    }

    #[Route('/mes-reservations', name: 'app_jpo_reservations', methods: ['GET'])]
    public function mesReservations(ParticipationJpoRepository $participationRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('jpo/reservations.html.twig', [
            'participations' => $participationRepository->findBy(['utilisateur' => $user], ['dateInscription' => 'DESC']),
        ]);
    }

    #[Route('/', name: 'app_jpo_index', methods: ['GET'])]
    public function index(Request $request, JourneePorteOuverteRepository $jpoRepository): Response
    {
        $search = $request->query->get('search');
        $date = $request->query->get('date');

        $qb = $jpoRepository->createQueryBuilder('j')
            ->orderBy('j.dateEvenement', 'ASC');

        if ($search) {
            $qb->andWhere('j.titre LIKE :search OR j.lieu LIKE :search OR j.description LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($date) {
            $qb->andWhere('j.dateEvenement = :date')
               ->setParameter('date', $date);
        }

        return $this->render('jpo/index.html.twig', [
            'events' => $qb->getQuery()->getResult(),
            'search' => $search,
            'date' => $date,
        ]);
    }

    #[Route('/new', name: 'app_jpo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_PROPRIETAIRE') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Seuls les propriétaires peuvent créer un événement.');
        }

        $jpo = new JourneePorteOuverte();
        $form = $this->createForm(JourneePorteOuverteType::class, $jpo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jpo->setCreateur($this->getUser());
            $entityManager->persist($jpo);
            $entityManager->flush();

            $this->addFlash('success', 'Événement JPO créé avec succès.');
            return $this->redirectToRoute('app_jpo_index');
        }

        return $this->render('jpo/new.html.twig', [
            'jpo' => $jpo,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{id}', name: 'app_jpo_show', methods: ['GET'])]
    public function show(JourneePorteOuverte $jpo, \App\Repository\ParticipationJpoRepository $participationRepository): Response
    {
        $userParticipation = null;
        if ($this->getUser()) {
            $userParticipation = $participationRepository->findOneBy([
                'evenement' => $jpo,
                'utilisateur' => $this->getUser()
            ]);
        }

        return $this->render('jpo/show.html.twig', [
            'jpo' => $jpo,
            'user_participation' => $userParticipation,
            'participations' => $participationRepository->findBy(['evenement' => $jpo]),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_jpo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JourneePorteOuverte $jpo, EntityManagerInterface $entityManager): Response
    {
        if ($jpo->getCreateur() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(JourneePorteOuverteType::class, $jpo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Événement JPO mis à jour.');
            return $this->redirectToRoute('app_jpo_index');
        }

        return $this->render('jpo/edit.html.twig', [
            'jpo' => $jpo,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{id}', name: 'app_jpo_delete', methods: ['POST'])]
    public function delete(Request $request, JourneePorteOuverte $jpo, EntityManagerInterface $entityManager): Response
    {
        if ($jpo->getCreateur() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$jpo->getId(), $request->request->get('_token'))) {
            $entityManager->remove($jpo);
            $entityManager->flush();
            $this->addFlash('success', 'Événement JPO supprimé.');
        }

        return $this->redirectToRoute('app_jpo_index');
    }
}
