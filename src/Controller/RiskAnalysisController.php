<?php

namespace App\Controller;

use App\Service\InvestmentService;
use App\Service\AiInvestmentAdvisor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/investisseur/risque')]
#[IsGranted('ROLE_INVESTISSEUR')]
class RiskAnalysisController extends AbstractController
{
    public function __construct(
        private InvestmentService $investmentService,
        private AiInvestmentAdvisor $aiAdvisor
    ) {}

    #[Route('/', name: 'investisseur_risk_analysis')]
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $stats = $this->investmentService->getPortfolioStats($user);
        
        // Detailed risk metrics
        $riskMetrics = [
            'exposure_level' => $this->calculateExposure($stats['totalInvested'], $user->getBudget()),
            'diversification_score' => $this->calculateDiversification($stats['sectorDistribution']),
            'portfolio_health' => $this->calculateHealth($stats['averageYield'], $stats['totalCount']),
        ];

        return $this->render('investisseur/risk_analysis.html.twig', [
            'stats' => $stats,
            'riskMetrics' => $riskMetrics,
            'user' => $user
        ]);
    }

    private function calculateExposure(float $invested, ?float $budget): array
    {
        if (!$budget || $budget <= 0) return ['level' => 'Inconnu', 'percent' => 0, 'color' => 'slate'];
        
        $percent = ($invested / $budget) * 100;
        if ($percent > 80) return ['level' => 'Critique', 'percent' => $percent, 'color' => 'red'];
        if ($percent > 50) return ['level' => 'Élevé', 'percent' => $percent, 'color' => 'orange'];
        if ($percent > 20) return ['level' => 'Modéré', 'percent' => $percent, 'color' => 'amber'];
        
        return ['level' => 'Faible', 'percent' => $percent, 'color' => 'emerald'];
    }

    private function calculateDiversification(array $sectors): array
    {
        $count = count($sectors);
        if ($count >= 4) return ['level' => 'Excellente', 'score' => 95, 'color' => 'emerald'];
        if ($count >= 2) return ['level' => 'Bonne', 'score' => 70, 'color' => 'blue'];
        if ($count == 1) return ['level' => 'Faible', 'score' => 30, 'color' => 'orange'];
        
        return ['level' => 'Nulle', 'score' => 0, 'color' => 'red'];
    }

    private function calculateHealth(float $yield, int $count): array
    {
        if ($count === 0) return ['level' => 'Neutre', 'color' => 'slate'];
        if ($yield > 15) return ['level' => 'Performant', 'color' => 'emerald'];
        if ($yield > 5) return ['level' => 'Stable', 'color' => 'blue'];
        
        return ['level' => 'Sous-performant', 'color' => 'orange'];
    }
}
