<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use App\Repository\OperationRepository;
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
        OperationRepository $operationRepository
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_dashboard');
        }

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
            ->orderBy('o.date_operation', 'DESC')
            ->getQuery()
            ->getResult();

        $totalOperations = count($operations);
        $incomeCount = 0;
        $expenseCount = 0;
        $lowBalanceAccounts = [];

        foreach ($tresoreries as $t) {
            $totalBalance += (float)$t->getSolde();
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
