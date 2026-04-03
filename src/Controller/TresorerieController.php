<?php

namespace App\Controller;

use App\Entity\Tresorerie;
use App\Entity\Operation;
use App\Form\TresorerieType;
use App\Form\TransfertType;
use App\Repository\TresorerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tresorerie')]
class TresorerieController extends AbstractController
{
    #[Route('/virement', name: 'app_tresorerie_virement', methods: ['GET', 'POST'])]
    public function virement(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(TransfertType::class, null, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $source = $data['source'];
            $destination = $data['destination'];
            $montant = $data['montant'];
            $description = $data['description'] ?? 'Virement interne';

            if ($source->getId() === $destination->getId()) {
                $this->addFlash('error', 'Le compte source et destination doivent être différents.');
                return $this->redirectToRoute('app_tresorerie_virement');
            }

            if ((float)$source->getSolde() < $montant) {
                $this->addFlash('error', 'Solde insuffisant sur le compte source.');
                return $this->redirectToRoute('app_tresorerie_virement');
            }

            // 1. Débiter la source
            $opSource = new Operation();
            $opSource->setTresorerie($source);
            $opSource->setType('depense');
            $opSource->setMontant($montant);
            $opSource->setReference('VIR-OUT-' . date('Ymd-His'));
            $opSource->setCategorie('Virement Interne');
            $opSource->setDescription($description . ' vers ' . $destination->getNomCompte());
            $opSource->setDateOperation(new \DateTime());
            
            $source->setSolde((string)((float)$source->getSolde() - $montant));
            $source->setDerniereMaj(new \DateTime());

            // 2. Créditer la destination
            $opDest = new Operation();
            $opDest->setTresorerie($destination);
            $opDest->setType('revenu');
            $opDest->setMontant($montant);
            $opDest->setReference('VIR-IN-' . date('Ymd-His'));
            $opDest->setCategorie('Virement Interne');
            $opDest->setDescription($description . ' depuis ' . $source->getNomCompte());
            $opDest->setDateOperation(new \DateTime());

            $destination->setSolde((string)((float)$destination->getSolde() + $montant));
            $destination->setDerniereMaj(new \DateTime());

            $entityManager->persist($opSource);
            $entityManager->persist($opDest);
            $entityManager->flush();

            $this->addFlash('success', 'Virement interne effectué avec succès.');
            return $this->redirectToRoute('app_tresorerie_index');
        }

        return $this->render('tresorerie/virement.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'app_tresorerie_index', methods: ['GET'])]
    public function index(Request $request, TresorerieRepository $tresorerieRepository, \App\Repository\EntrepriseRepository $entrepriseRepository): Response
    {
        $user = $this->getUser();
        $entrepriseId = $request->query->get('entreprise');
        $search = $request->query->get('search');
        
        $qb = $tresorerieRepository->createQueryBuilder('t')
            ->join('t.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->orderBy('t.nom_compte', 'ASC');
            
        if ($entrepriseId) {
            $qb->andWhere('t.entreprise = :entId')
               ->setParameter('entId', $entrepriseId);
        }
        
        if ($search) {
            $qb->andWhere('t.nom_compte LIKE :search OR t.numero_compte LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }
        
        $tresoreries = $qb->getQuery()->getResult();
        
        $selectedEntreprise = null;
        if ($entrepriseId) {
            $selectedEntreprise = $entrepriseRepository->find($entrepriseId);
        }

        return $this->render('tresorerie/index.html.twig', [
            'tresoreries' => $tresoreries,
            'entreprises' => $entrepriseRepository->findBy(['proprietaire' => $user]),
            'selected_entreprise' => $selectedEntreprise,
            'search' => $search,
            'entrepriseId' => $entrepriseId,
        ]);
    }

    #[Route('/new', name: 'app_tresorerie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tresorerie = new Tresorerie();
        
        // Pre-fill entreprise if provided in URL
        $entrepriseId = $request->query->get('entreprise');
        if ($entrepriseId) {
            $entreprise = $entityManager->getRepository(\App\Entity\Entreprise::class)->find($entrepriseId);
            if ($entreprise) {
                $tresorerie->setEntreprise($entreprise);
            }
        }

        $form = $this->createForm(TresorerieType::class, $tresorerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tresorerie->setDerniereMaj(new \DateTime());
            $entityManager->persist($tresorerie);
            $entityManager->flush();

            $this->addFlash('success', 'Trésorerie created successfully.');
            
            $params = [];
            if ($tresorerie->getEntreprise()) {
                $params['entreprise'] = $tresorerie->getEntreprise()->getId();
            }
            
            return $this->redirectToRoute('app_tresorerie_index', $params, Response::HTTP_SEE_OTHER);
        }

        return $this->render('tresorerie/new.html.twig', [
            'tresorerie' => $tresorerie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_tresorerie_show', methods: ['GET'])]
    public function show(Tresorerie $tresorerie, \App\Repository\OperationRepository $operationRepository): Response
    {
        return $this->render('tresorerie/show.html.twig', [
            'tresorerie' => $tresorerie,
            'operations' => $operationRepository->findBy(['tresorerie' => $tresorerie], ['date_operation' => 'DESC']),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tresorerie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tresorerie $tresorerie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TresorerieType::class, $tresorerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tresorerie->setDerniereMaj(new \DateTime());
            $entityManager->flush();

            $this->addFlash('success', 'Trésorerie updated successfully.');
            return $this->redirectToRoute('app_tresorerie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tresorerie/edit.html.twig', [
            'tresorerie' => $tresorerie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_tresorerie_delete', methods: ['POST'])]
    public function delete(Request $request, Tresorerie $tresorerie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tresorerie->getId(), $request->get('_token'))) {
            $entityManager->remove($tresorerie);
            $entityManager->flush();
            $this->addFlash('success', 'Trésorerie deleted successfully.');
        }

        return $this->redirectToRoute('app_tresorerie_index', [], Response::HTTP_SEE_OTHER);
    }
}
