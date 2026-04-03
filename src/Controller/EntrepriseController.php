<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Tresorerie;
use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/entreprise')]
class EntrepriseController extends AbstractController
{
    #[Route('/', name: 'app_entreprise_index', methods: ['GET'])]
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $user = $this->getUser();
        $entreprises = $entrepriseRepository->findBy(['proprietaire' => $user]);

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    #[Route('/creation', name: 'app_entreprise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
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
            $entityManager->flush();

            $this->addFlash('success', 'Entreprise ajoutée avec succès et compte de trésorerie créé.');
            return $this->redirectToRoute('app_entreprise_index');
        }

        return $this->render('entreprise/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_entreprise_show', methods: ['GET'])]
    public function show(Entreprise $entreprise, \App\Repository\TresorerieRepository $tresorerieRepository): Response
    {
        if ($entreprise->getProprietaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
            'tresoreries' => $tresorerieRepository->findBy(['entreprise' => $entreprise]),
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
        ]);
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
    public function documents(Entreprise $entreprise): Response
    {
        return $this->render('entreprise/documents.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }
}
