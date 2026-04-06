<?php

namespace App\Controller;

use App\Entity\Investissement;
use App\Entity\User;
use App\Form\InvestissementType;
use App\Repository\InvestissementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/investissement')]
class InvestissementController extends AbstractController
{
    #[Route('/', name: 'app_investissement_index', methods: ['GET'])]
    public function index(Request $request, InvestissementRepository $investissementRepository): Response
    {
        $user = $this->getUser();
        $search = $request->query->get('search');
        $statut = $request->query->get('statut');

        $qb = $investissementRepository->createQueryBuilder('i')
            ->orderBy('i.dateInvestissement', 'DESC');

        // Si l'utilisateur est un investisseur, il ne voit que ses investissements
        if ($this->isGranted('ROLE_INVESTISSEUR')) {
            $qb->andWhere('i.investisseur = :user')
               ->setParameter('user', $user);
        } 
        // Si l'utilisateur est un propriétaire, il voit les investissements reçus par ses entreprises
        elseif ($this->isGranted('ROLE_PROPRIETAIRE')) {
            $qb->join('i.entreprise', 'e')
               ->andWhere('e.proprietaire = :user')
               ->setParameter('user', $user);
        }

        if ($search) {
            $qb->join('i.entreprise', 'e2')
               ->andWhere('e2.nom LIKE :search OR i.description LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($statut) {
            $qb->andWhere('i.statut = :statut')
               ->setParameter('statut', $statut);
        }

        return $this->render('investissement/index.html.twig', [
            'investissements' => $qb->getQuery()->getResult(),
            'search' => $search,
            'statut' => $statut,
        ]);
    }

    #[Route('/new', name: 'app_investissement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_INVESTISSEUR')) {
            throw $this->createAccessDeniedException('Seuls les investisseurs peuvent créer un investissement.');
        }

        $investissement = new Investissement();
        
        // Pré-remplir l'entreprise si l'ID est fourni en paramètre
        $entrepriseId = $request->query->get('entreprise');
        if ($entrepriseId) {
            $entreprise = $entityManager->getRepository(\App\Entity\Entreprise::class)->find($entrepriseId);
            if ($entreprise) {
                $investissement->setEntreprise($entreprise);
            }
        }

        $form = $this->createForm(InvestissementType::class, $investissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $investissement->setInvestisseur($this->getUser());
            $investissement->setStatut('EN_ATTENTE');
            $investissement->setDateInvestissement(new \DateTime());
            
            $entityManager->persist($investissement);
            $entityManager->flush();

            $this->addFlash('success', 'Votre proposition d\'investissement a été envoyée.');
            return $this->redirectToRoute('app_investissement_index');
        }

        return $this->render('investissement/new.html.twig', [
            'investissement' => $investissement,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/investisseurs', name: 'app_investissement_investisseurs', methods: ['GET'])]
    public function listInvestisseurs(Request $request, InvestissementRepository $investissementRepository, \App\Repository\UserRepository $userRepository): Response
    {
        if (!$this->isGranted('ROLE_PROPRIETAIRE')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();
        
        // Récupérer les investisseurs qui ont investi (ou proposé) dans les entreprises de ce propriétaire
        $investisseurs = $userRepository->createQueryBuilder('u')
            ->join('App\Entity\Investissement', 'i', 'WITH', 'i.investisseur = u')
            ->join('i.entreprise', 'e')
            ->where('e.proprietaire = :owner')
            ->setParameter('owner', $user)
            ->distinct()
            ->getQuery()
            ->getResult();

        return $this->render('investissement/investisseurs.html.twig', [
            'investisseurs' => $investisseurs,
        ]);
    }

    #[Route('/investisseur/{id}', name: 'app_investissement_investisseur_show', methods: ['GET'])]
    public function showInvestisseur(User $investisseur, InvestissementRepository $investissementRepository): Response
    {
        if (!$this->isGranted('ROLE_PROPRIETAIRE')) {
            throw $this->createAccessDeniedException();
        }

        $user = $this->getUser();
        
        // Vérifier si cet investisseur a au moins un investissement lié au propriétaire actuel
        $hasLink = $investissementRepository->createQueryBuilder('i')
            ->join('i.entreprise', 'e')
            ->where('i.investisseur = :investisseur')
            ->andWhere('e.proprietaire = :owner')
            ->setParameter('investisseur', $investisseur)
            ->setParameter('owner', $user)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$hasLink && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne pouvez voir que les détails des investisseurs liés à vos entreprises.');
        }

        $investissements = $investissementRepository->createQueryBuilder('i')
            ->join('i.entreprise', 'e')
            ->where('i.investisseur = :investisseur')
            ->andWhere('e.proprietaire = :owner')
            ->setParameter('investisseur', $investisseur)
            ->setParameter('owner', $user)
            ->getQuery()
            ->getResult();

        return $this->render('investissement/investisseur_show.html.twig', [
            'investisseur' => $investisseur,
            'investissements' => $investissements,
        ]);
    }

    #[Route('/{id}', name: 'app_investissement_show', methods: ['GET'])]
    public function show(Investissement $investissement): Response
    {
        // Vérification des droits d'accès
        $user = $this->getUser();
        $isOwner = $investissement->getEntreprise()->getProprietaire() === $user;
        $isInvestor = $investissement->getInvestisseur() === $user;

        if (!$isOwner && !$isInvestor && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('investissement/show.html.twig', [
            'investissement' => $investissement,
        ]);
    }

    #[Route('/{id}/valider', name: 'app_investissement_valider', methods: ['POST'])]
    public function valider(Investissement $investissement, EntityManagerInterface $entityManager): Response
    {
        if ($investissement->getEntreprise()->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $investissement->setStatut('ACTIF');
        $entityManager->flush();

        $this->addFlash('success', 'L\'investissement a été validé et est désormais actif.');
        return $this->redirectToRoute('app_investissement_show', ['id' => $investissement->getId()]);
    }

    #[Route('/{id}/refuser', name: 'app_investissement_refuser', methods: ['POST'])]
    public function refuser(Investissement $investissement, EntityManagerInterface $entityManager): Response
    {
        if ($investissement->getEntreprise()->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $investissement->setStatut('ANNULE');
        $entityManager->flush();

        $this->addFlash('warning', 'L\'investissement a été refusé.');
        return $this->redirectToRoute('app_investissement_show', ['id' => $investissement->getId()]);
    }
}
