<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Tresorerie;
use App\Entity\Operation;
use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Contrôleur Entreprise
 * 
 * Gère le cycle de vie des PME (Création, Affichage, Modification, Suppression).
 * 
 * Architecture :
 * - Utilise EntrepriseRepository pour récupérer les données.
 * - Utilise EntrepriseType pour valider et traiter les formulaires.
 * - Les accès sont restreints au propriétaire de l'entreprise ou à l'administrateur.
 */
#[Route('/entreprise')]
class EntrepriseController extends AbstractController
{
    /**
     * Liste les entreprises.
     * Pour un propriétaire : seulement ses entreprises.
     * Pour un investisseur ou admin : toutes les entreprises avec recherche.
     */
    #[Route('/', name: 'app_entreprise_index', methods: ['GET'])]
    public function index(Request $request, EntrepriseRepository $entrepriseRepository): Response
    {
        $user = $this->getUser();
        $search = $request->query->get('q');
        $sector = $request->query->get('sector');

        $qb = $entrepriseRepository->createQueryBuilder('e');

        if ($this->isGranted('ROLE_PROPRIETAIRE')) {
            $qb->andWhere('e.proprietaire = :user')
               ->setParameter('user', $user);
        }

        if ($search) {
            $qb->andWhere('e.nom LIKE :search OR e.secteur LIKE :search OR e.adresse LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($sector) {
            $qb->andWhere('e.secteur = :sector')
               ->setParameter('sector', $sector);
        }

        $entreprises = $qb->getQuery()->getResult();
        
        // Récupérer tous les secteurs uniques pour le filtre
        $sectors = $entrepriseRepository->createQueryBuilder('e')
            ->select('DISTINCT e.secteur')
            ->getQuery()
            ->getResult();

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
            'search' => $search,
            'current_sector' => $sector,
            'sectors' => array_column($sectors, 'secteur'),
        ]);
    }

    /**
     * Crée une nouvelle entreprise et initialise automatiquement son compte de trésorerie principal.
     */
    #[Route('/creation', name: 'app_entreprise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_PROPRIETAIRE') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Seuls les propriétaires peuvent créer une entreprise.');
        }

        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entreprise->setProprietaire($this->getUser());
            $entityManager->persist($entreprise);

            // Automatiquement créer un compte de trésorerie avec le capital
            $tresorerie = new Tresorerie();
            $tresorerie->setEntreprise($entreprise);
            $tresorerie->setNomCompte('Compte Capital Social');
            $tresorerie->setTypeCompte('BANQUE');
            $tresorerie->setSolde($entreprise->getCapital());
            $tresorerie->setDevise('TND');
            $tresorerie->setDerniereMaj(new \DateTime());
            
            $entityManager->persist($tresorerie);

            // Créer une opération initiale pour le capital
            if ((float)$entreprise->getCapital() > 0) {
                $operation = new Operation();
                $operation->setTresorerie($tresorerie);
                $operation->setType('revenu');
                $operation->setMontant($entreprise->getCapital());
                $operation->setReference('CAPITAL-' . $entreprise->getId());
                $operation->setCategorie('Capital Social');
                $operation->setDescription('Apport initial au capital social de ' . $entreprise->getNom());
                $operation->setDateOperation(new \DateTime());
                $entityManager->persist($operation);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Entreprise ajoutée avec succès et compte de trésorerie créé.');
            return $this->redirectToRoute('app_entreprise_index');
        }

        return $this->render('entreprise/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{id}', name: 'app_entreprise_show', methods: ['GET'])]
    public function show(Entreprise $entreprise, \App\Repository\TresorerieRepository $tresorerieRepository): Response
    {
        // Les investisseurs peuvent voir les entreprises pour décider d'investir
        // Mais seuls le propriétaire et l'admin voient les comptes de trésorerie
        $isOwnerOrAdmin = $entreprise->getProprietaire() === $this->getUser() || $this->isGranted('ROLE_ADMIN');
        
        $tresoreries = [];
        if ($isOwnerOrAdmin) {
            $tresoreries = $tresorerieRepository->findBy(['entreprise' => $entreprise]);
        }

        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
            'tresoreries' => $tresoreries,
            'is_owner_or_admin' => $isOwnerOrAdmin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_entreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        if ($entreprise->getProprietaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Entreprise mise à jour avec succès.');
            return $this->redirectToRoute('app_entreprise_index');
        }

        return $this->render('entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{id}', name: 'app_entreprise_delete', methods: ['POST'])]
    public function delete(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        if ($entreprise->getProprietaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->request->get('_token'))) {
            $entityManager->remove($entreprise);
            $entityManager->flush();
            $this->addFlash('success', 'Entreprise supprimée avec succès.');
        }

        return $this->redirectToRoute('app_entreprise_index');
    }

    #[Route('/{id}/documents', name: 'app_entreprise_documents', methods: ['GET'])]
    public function documents(Entreprise $entreprise, DocumentRepository $documentRepository): Response
    {
        if ($entreprise->getProprietaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('entreprise/documents.html.twig', [
            'entreprise' => $entreprise,
            'documents' => $documentRepository->findBy(['entreprise' => $entreprise], ['dateUpload' => 'DESC']),
        ]);
    }
}
