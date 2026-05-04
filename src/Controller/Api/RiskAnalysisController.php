<?php

namespace App\Controller\Api;

use App\Service\RiskScoringService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/analyse-risque')]
class RiskAnalysisController extends AbstractController
{
    #[Route('/', name: 'app_risk_analysis')]
    public function index(RiskScoringService $riskService): Response
    {
        $risks = $riskService->calculateAllRisks();
        $distribution = $riskService->getDistribution($risks);

        return $this->render('api/risk_analysis.html.twig', [
            'risks' => $risks,
            'distribution' => $distribution,
            'totalInvestments' => count($risks),
        ]);
    }
}