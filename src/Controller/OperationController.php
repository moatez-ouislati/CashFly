<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Form\OperationType;
use App\Repository\OperationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/operation')]
class OperationController extends AbstractController
{
    #[Route('/', name: 'app_operation_index', methods: ['GET'])]
    public function index(Request $request, OperationRepository $operationRepository, \App\Repository\EntrepriseRepository $entrepriseRepository, \App\Repository\TresorerieRepository $tresorerieRepository): Response
    {
        $user = $this->getUser();
        $entrepriseId = $request->query->get('entreprise');
        $tresorerieId = $request->query->get('tresorerie');
        $date = $request->query->get('date');
        $type = $request->query->get('type');
        $search = $request->query->get('search');

        $qb = $operationRepository->createQueryBuilder('o')
            ->join('o.tresorerie', 't')
            ->join('t.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->orderBy('o.date_operation', 'DESC');
        
        if ($search) {
            $qb->andWhere('o.reference LIKE :search OR o.description LIKE :search OR o.categorie LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($entrepriseId) {
            $qb->andWhere('t.entreprise = :entId')
               ->setParameter('entId', $entrepriseId);
        }

        if ($tresorerieId) {
            $qb->andWhere('o.tresorerie = :tresId')
               ->setParameter('tresId', $tresorerieId);
        }
        
        if ($type) {
            $qb->andWhere('o.type = :type')
               ->setParameter('type', $type);
        }
        
        if ($date) {
            $qb->andWhere('o.date_operation LIKE :date')
               ->setParameter('date', $date . '%');
        }
        
        $operations = $qb->getQuery()->getResult();
        
        $selectedTresorerie = null;
        if ($tresorerieId) {
            $selectedTresorerie = $tresorerieRepository->find($tresorerieId);
        }

        return $this->render('operation/index.html.twig', [
            'operations' => $operations,
            'entreprises' => $entrepriseRepository->findBy(['proprietaire' => $user]),
            'tresoreries' => $tresorerieRepository->createQueryBuilder('tr')
                ->join('tr.entreprise', 'ent')
                ->where('ent.proprietaire = :u')
                ->setParameter('u', $user)
                ->getQuery()
                ->getResult(),
            'selected_tresorerie' => $selectedTresorerie,
            'search' => $search,
            'type' => $type,
            'date' => $date,
            'entrepriseId' => $entrepriseId,
            'tresorerieId' => $tresorerieId,
        ]);
    }

    #[Route('/new', name: 'app_operation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operation = new Operation();
        
        // Pre-fill tresorerie if provided in URL
        $tresorerieId = $request->query->get('tresorerie');
        if ($tresorerieId) {
            $tresorerie = $entityManager->getRepository(\App\Entity\Tresorerie::class)->find($tresorerieId);
            if ($tresorerie) {
                $operation->setTresorerie($tresorerie);
            }
        }

        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update tresorerie balance
            $tresorerie = $operation->getTresorerie();
            $amount = (float) $operation->getMontant();
            $currentSolde = (float) $tresorerie->getSolde();
            
            if ($operation->getType() === 'revenu') {
                $tresorerie->setSolde((string) ($currentSolde + $amount));
            } else {
                $tresorerie->setSolde((string) ($currentSolde - $amount));
            }
            $tresorerie->setDerniereMaj(new \DateTime());

            $entityManager->persist($operation);
            $entityManager->flush();

            $this->addFlash('success', 'Transaction recorded successfully.');
            return $this->redirectToRoute('app_operation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('operation/new.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_operation_show', methods: ['GET'])]
    public function show(Operation $operation): Response
    {
        return $this->render('operation/show.html.twig', [
            'operation' => $operation,
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_operation_pdf', methods: ['GET'])]
    public function generatePdf(Operation $operation): Response
    {
        return $this->render('operation/pdf.html.twig', [
            'operation' => $operation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_operation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Operation $operation, EntityManagerInterface $entityManager): Response
    {
        $oldType = $operation->getType();
        $oldAmount = $operation->getMontant();
        $oldTresorerie = $operation->getTresorerie();

        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Revert old balance
            $oldCurrentSolde = (float) $oldTresorerie->getSolde();
            if ($oldType === 'revenu') {
                $oldTresorerie->setSolde((string) ($oldCurrentSolde - (float)$oldAmount));
            } else {
                $oldTresorerie->setSolde((string) ($oldCurrentSolde + (float)$oldAmount));
            }

            // Apply new balance
            $newTresorerie = $operation->getTresorerie();
            $newAmount = (float) $operation->getMontant();
            $newCurrentSolde = (float) $newTresorerie->getSolde();
            if ($operation->getType() === 'revenu') {
                $newTresorerie->setSolde((string) ($newCurrentSolde + $newAmount));
            } else {
                $newTresorerie->setSolde((string) ($newCurrentSolde - $newAmount));
            }
            $newTresorerie->setDerniereMaj(new \DateTime());

            $entityManager->flush();

            $this->addFlash('success', 'Transaction updated successfully.');
            return $this->redirectToRoute('app_operation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('operation/edit.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_operation_delete', methods: ['POST'])]
    public function delete(Request $request, Operation $operation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operation->getId(), $request->get('_token'))) {
            // Revert balance before deleting
            $tresorerie = $operation->getTresorerie();
            $currentSolde = (float) $tresorerie->getSolde();
            $amount = (float) $operation->getMontant();
            
            if ($operation->getType() === 'revenu') {
                $tresorerie->setSolde((string) ($currentSolde - $amount));
            } else {
                $tresorerie->setSolde((string) ($currentSolde + $amount));
            }
            $tresorerie->setDerniereMaj(new \DateTime());

            $entityManager->remove($operation);
            $entityManager->flush();
            $this->addFlash('success', 'Transaction deleted successfully.');
        }

        return $this->redirectToRoute('app_operation_index', [], Response::HTTP_SEE_OTHER);
    }
}
