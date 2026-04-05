<?php

namespace App\Controller;

use App\Entity\Investissement;
use App\Repository\InvestissementRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/investments')]
class InvestmentController extends AbstractController
{
    #[Route('/', name: 'app_investments')]
    public function index(
        InvestissementRepository $investissementRepository,
        EntrepriseRepository $entrepriseRepository,
        UtilisateurRepository $utilisateurRepository
    ): Response {
        $user = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        $investissements = $investissementRepository->findAll();
        $entreprises = $entrepriseRepository->findAll();
        $totalInvesti = $investissementRepository->getTotalMontantInvesti();
        
        return $this->render('front/investments.html.twig', [
            'investissements' => $investissements,
            'entreprises' => $entreprises,
            'totalInvesti' => $totalInvesti,
            'user' => $user,
        ]);
    }

    #[Route('/create', name: 'app_investment_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        UtilisateurRepository $utilisateurRepository,
        EntrepriseRepository $entrepriseRepository
    ): Response {
        $user = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        
        $investissement = new Investissement();
        $investissement->setInvestisseur($user);
        
        $entrepriseId = $request->request->get('entreprise_id');
        if ($entrepriseId) {
            $entreprise = $entrepriseRepository->find($entrepriseId);
            if ($entreprise) {
                $investissement->setEntreprise($entreprise);
            }
        }
        
        $investissement->setMontant($request->request->get('montant', '0.00'));
        $investissement->setTauxRendementPrevu($request->request->get('taux_rendement', '0.00'));
        $investissement->setDureeMois((int) $request->request->get('duree_mois', 0));
        $investissement->setDescription($request->request->get('description', ''));
        $investissement->setStatut('EN_ATTENTE');
        $investissement->setDateInvestissement(new \DateTime());
        
        $entityManager->persist($investissement);
        $entityManager->flush();
        
        $this->addFlash('success', 'Investissement ajoute avec succes');
        
        return $this->redirectToRoute('app_investments');
    }

    #[Route('/edit/{id}', name: 'app_investment_edit', methods: ['GET', 'POST'])]
    public function edit(
        Investissement $investissement,
        Request $request,
        EntityManagerInterface $entityManager,
        UtilisateurRepository $utilisateurRepository,
        EntrepriseRepository $entrepriseRepository
    ): Response {
        $user = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        
        if ($request->isMethod('POST')) {
            $entrepriseId = $request->request->get('entreprise_id');
            if ($entrepriseId) {
                $entreprise = $entrepriseRepository->find($entrepriseId);
                if ($entreprise) {
                    $investissement->setEntreprise($entreprise);
                }
            }
            
            $investissement->setMontant($request->request->get('montant', '0.00'));
            $investissement->setTauxRendementPrevu($request->request->get('taux_rendement', '0.00'));
            $investissement->setDureeMois((int) $request->request->get('duree_mois', 0));
            $investissement->setDescription($request->request->get('description', ''));
            $investissement->setStatut($request->request->get('statut', 'EN_ATTENTE'));
            
            $entityManager->flush();
            
            $this->addFlash('success', 'Investissement modifie avec succes');
            return $this->redirectToRoute('app_investments');
        }
        
        $entreprises = $entrepriseRepository->findAll();
        
        return $this->render('front/investment_edit.html.twig', [
            'investissement' => $investissement,
            'entreprises' => $entreprises,
            'user' => $user,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_investment_delete', methods: ['POST'])]
    public function delete(
        Investissement $investissement,
        EntityManagerInterface $entityManager
    ): Response {
        $entityManager->remove($investissement);
        $entityManager->flush();
        
        $this->addFlash('success', 'Investissement supprime');
        
        return $this->redirectToRoute('app_investments');
    }

    #[Route('/update-status/{id}/{statut}', name: 'app_investment_update_status', methods: ['POST'])]
    public function updateStatus(
        Investissement $investissement,
        string $statut,
        EntityManagerInterface $entityManager
    ): Response {
        $investissement->setStatut($statut);
        $entityManager->flush();
        
        $this->addFlash('success', 'Statut mis a jour');
        
        return $this->redirectToRoute('app_investments');
    }
}
