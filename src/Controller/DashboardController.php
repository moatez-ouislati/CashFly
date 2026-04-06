<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use App\Repository\InvestissementRepository;
use App\Repository\OperationRepository;
use App\Repository\ParticipationJpoRepository;
use App\Repository\TresorerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(
        EntrepriseRepository $entrepriseRepository,
        TresorerieRepository $tresorerieRepository,
        OperationRepository $operationRepository,
        InvestissementRepository $investissementRepository,
        ParticipationJpoRepository $participationJpoRepository
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

        // Si l'utilisateur est un investisseur
        if ($user->getDbRole() === 'investisseur') {
            // Vérifier si le profil est complet
            if (empty($user->getYearsExperience()) || empty($user->getHighestProfit())) {
                return $this->redirectToRoute('app_profile_complete');
            }

            $myInvestments = $investissementRepository->findBy(['investisseur' => $user]);
            $totalInvested = 0;
            $expectedReturns = 0;
            
            foreach ($myInvestments as $inv) {
                if ($inv->getStatut() === 'ACTIF') {
                    $totalInvested += (float)$inv->getMontant();
                    $expectedReturns += (float)$inv->getMontant() * ((float)$inv->getTauxRendementPrevu() / 100);
                }
            }

            $myParticipations = $participationJpoRepository->findBy(['utilisateur' => $user]);
            $availableEntreprises = $entrepriseRepository->findAll(); // Pour suggérer des investissements

            return $this->render('dashboard/investisseur.html.twig', [
                'my_investments' => $myInvestments,
                'total_invested' => $totalInvested,
                'expected_returns' => $expectedReturns,
                'my_participations' => $myParticipations,
                'available_entreprises' => array_slice($availableEntreprises, 0, 4),
            ]);
        }

        // Sinon, c'est un propriétaire (logique existante)
        $entreprises = $entrepriseRepository->findBy(['proprietaire' => $user]);
        $entreprisesCount = count($entreprises);
        
        $tresoreries = $tresorerieRepository->createQueryBuilder('t')
            ->join('t.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
            
        $tresoreriesCount = count($tresoreries);
        
        $totalBalance = 0;
        foreach ($tresoreries as $t) {
            $totalBalance += (float)$t->getSolde();
        }

        $operations = $operationRepository->createQueryBuilder('o')
            ->join('o.tresorerie', 't')
            ->join('t.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->orderBy('o.dateOperation', 'DESC')
            ->getQuery()
            ->getResult();

        $totalOperations = count($operations);
        $incomeCount = 0;
        $expenseCount = 0;
        $lowBalanceAccounts = [];

        foreach ($tresoreries as $t) {
            if ((float)$t->getSolde() < 100) {
                $lowBalanceAccounts[] = $t;
            }
        }

        foreach ($operations as $o) {
            if ($o->getType() === 'revenu') {
                $incomeCount++;
            } else {
                $expenseCount++;
            }
        }

        // Simuler les favoris (les 3 comptes avec le solde le plus élevé)
        $favTresoreries = $tresorerieRepository->createQueryBuilder('t')
            ->join('t.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->orderBy('t.solde', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();

        return $this->render('dashboard/index.html.twig', [
            'total_balance' => $totalBalance,
            'total_operations' => $totalOperations,
            'entreprises_count' => $entreprisesCount,
            'tresoreries_count' => $tresoreriesCount,
            'income_count' => $incomeCount,
            'expense_count' => $expenseCount,
            'recent_operations' => array_slice($operations, 0, 5),
            'fav_tresoreries' => $favTresoreries,
            'low_balance_accounts' => $lowBalanceAccounts,
        ]);
    }
}
