<?php

namespace App\Controller\Front;

use App\Repository\OperationRepository;
use App\Repository\TresorerieRepository;
use App\Repository\InvestissementRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\RendementInvestissementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/register', name: 'app_register')]
    public function register(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(
        OperationRepository $operationRepository,
        TresorerieRepository $tresorerieRepository,
        InvestissementRepository $investissementRepository,
        UtilisateurRepository $utilisateurRepository
    ): Response {
        $user = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        
        $totalRevenus = $operationRepository->getTotalRevenus();
        $totalDepenses = $operationRepository->getTotalDepenses();
        $totalSolde = $tresorerieRepository->getTotalSolde();
        $recentOperations = $operationRepository->getRecentOperations(10);
        
        $recentInvestissements = [];
        if ($user) {
            $recentInvestissements = $investissementRepository->findByInvestisseur($user);
        }

        $soldeByType = $tresorerieRepository->getSoldeByType();
        $operationsParMois = $this->getOperationsParMois($operationRepository);
        
        $chartData = $this->prepareChartData($operationsParMois, $soldeByType);

        return $this->render('front/dashboard.html.twig', [
            'totalRevenus' => $totalRevenus,
            'totalDepenses' => $totalDepenses,
            'totalSolde' => $totalSolde,
            'recentOperations' => $recentOperations,
            'recentInvestissements' => array_slice($recentInvestissements, 0, 5),
            'soldeByType' => $soldeByType,
            'operationsParMois' => $operationsParMois,
            'chartData' => $chartData,
            'user' => $user,
        ]);
    }

    #[Route('/transactions', name: 'app_transactions')]
    public function transactions(OperationRepository $operationRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        $operations = $operationRepository->findBy([], ['dateOperation' => 'DESC'], 100);
        
        return $this->render('front/transactions.html.twig', [
            'operations' => $operations,
            'user' => $user,
        ]);
    }

    #[Route('/wallets', name: 'app_wallets')]
    public function wallets(TresorerieRepository $tresorerieRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        $wallets = $tresorerieRepository->findAll();
        $totalSolde = $tresorerieRepository->getTotalSolde();
        $soldeByType = $tresorerieRepository->getSoldeByType();
        
        return $this->render('front/wallets.html.twig', [
            'wallets' => $wallets,
            'totalSolde' => $totalSolde,
            'soldeByType' => $soldeByType,
            'user' => $user,
        ]);
    }

    #[Route('/companies', name: 'app_companies')]
    public function companies(EntrepriseRepository $entrepriseRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        $entreprises = $entrepriseRepository->findAll();
        
        return $this->render('front/companies.html.twig', [
            'entreprises' => $entreprises,
            'user' => $user,
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        return $this->render('front/profile.html.twig', ['user' => $user]);
    }

    #[Route('/settings', name: 'app_settings')]
    public function settings(UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        return $this->render('front/settings.html.twig', ['user' => $user]);
    }

    #[Route('/rendements', name: 'app_rendements')]
    public function rendements(
        UtilisateurRepository $utilisateurRepository,
        RendementInvestissementRepository $rendementRepository,
        InvestissementRepository $investissementRepository
    ): Response {
        $user = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        
        $rendements = $rendementRepository->findBy([], ['dateCalcul' => 'DESC']);
        $investissements = $investissementRepository->findAll();
        
        $totalGain = $rendementRepository->getTotalGains();
        $totalPerte = $rendementRepository->getTotalPertes();
        $rendementNet = $rendementRepository->getRendementNet();
        
        $chartData = $this->prepareRendementChartData($rendements);
        
        return $this->render('front/rendements.html.twig', [
            'rendements' => $rendements,
            'investissements' => $investissements,
            'totalGain' => $totalGain,
            'totalPerte' => $totalPerte,
            'rendementNet' => $rendementNet,
            'chartData' => $chartData,
            'user' => $user,
        ]);
    }

    private function getOperationsParMois(OperationRepository $operationRepository): array
    {
        $result = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = new \DateTime("-{$i} months");
            $start = (clone $date)->modify('first day of this month');
            $end = (clone $date)->modify('last day of this month');
            
            $result[] = [
                'month' => $start->format('M'),
                'revenus' => $operationRepository->getTotalRevenus($start, $end),
                'depenses' => $operationRepository->getTotalDepenses($start, $end),
            ];
        }
        return $result;
    }
    
    private function prepareChartData(array $operationsParMois, array $soldeByType): array
    {
        $revenus = [];
        $depenses = [];
        $months = [];
        
        foreach ($operationsParMois as $data) {
            $months[] = $data['month'];
            $revenus[] = (float) $data['revenus'];
            $depenses[] = (float) $data['depenses'];
        }
        
        $soldeData = [];
        $soldeLabels = [];
        foreach ($soldeByType as $type) {
            $soldeLabels[] = $type['typeCompte'] ?? 'Autre';
            $soldeData[] = (float) ($type['total'] ?? 0);
        }
        
        return [
            'months' => $months,
            'revenus' => $revenus,
            'depenses' => $depenses,
            'soldeData' => $soldeData,
            'soldeLabels' => $soldeLabels,
        ];
    }
    
    private function prepareRendementChartData(array $rendements): array
    {
        $labels = [];
        $data = [];
        
        foreach ($rendements as $rendement) {
            $dateCalcul = $rendement->getDateCalcul();
            $labels[] = $dateCalcul ? $dateCalcul->format('d/m') : '';
            $data[] = $rendement->getRendementNet();
        }
        
        return [
            'rendementLabels' => $labels,
            'rendementData' => $data,
        ];
    }
}
