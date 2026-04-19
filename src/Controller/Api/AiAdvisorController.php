<?php

namespace App\Controller\Api;

use App\Service\Api\AiAssistantService;
use App\Service\RiskScoringService;
use App\Repository\InvestissementRepository;
use App\Repository\RendementInvestissementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ai-advisor')]
class AiAdvisorController extends AbstractController
{
    #[Route('/', name: 'app_ai_advisor')]
    public function index(
        Request $request,
        AiAssistantService $aiService,
        InvestissementRepository $invRepo,
        RendementInvestissementRepository $rendRepo
    ): Response {
        $message = $request->query->get('message', '');
        $quickAction = $request->query->get('action', '');
        
        $portfolio = [
            'totalInvested' => 0,
            'totalGain' => 0,
            'investmentsCount' => 0,
            'riskDistribution' => ['LOW' => 0, 'MEDIUM' => 0, 'HIGH' => 0],
        ];
        
        $investments = $invRepo->findAll();
        $portfolio['investmentsCount'] = count($investments);
        
        $totalInvested = 0;
        $totalGain = 0;
        foreach ($investments as $inv) {
            $totalInvested += $inv->getMontantFloat();
        }
        
        $portfolio['totalInvested'] = $totalInvested;
        $portfolio['totalGain'] = $totalGain;
        
        $aiResponse = null;
        
        if ($message) {
            $aiResponse = $aiService->chat($message, $portfolio);
        } elseif ($quickAction === 'portfolio' && count($investments) > 0) {
            $aiResponse = $aiService->analyzePortfolio($portfolio);
        } elseif ($quickAction === 'strategy') {
            $aiResponse = $aiService->explainStrategy('balanced');
        } elseif ($quickAction === 'safe') {
            $aiResponse = $aiService->explainStrategy('safe');
        }
        
        return $this->render('api/ai_advisor.html.twig', [
            'aiResponse' => $aiResponse,
            'quickActions' => [
                'portfolio' => 'Analyser mon portefeuille',
                'strategy' => 'Strategie balancee',
                'safe' => 'Strategie sicher',
            ],
        ]);
    }

    #[Route('/chat', name: 'app_ai_advisor_chat', methods: ['POST'])]
    public function chat(
        Request $request,
        AiAssistantService $aiService,
        InvestissementRepository $invRepo
    ): Response {
        $data = json_decode($request->getContent(), true);
        $message = $data['message'] ?? '';
        
        if (empty($message)) {
            return $this->json(['error' => 'Message is required'], 400);
        }
        
        $investments = $invRepo->findAll();
        $portfolio = [
            'totalInvested' => array_sum(array_map(fn($i) => $i->getMontantFloat(), $investments)),
            'investmentsCount' => count($investments),
            'riskDistribution' => ['LOW' => 0, 'MEDIUM' => 0, 'HIGH' => 0],
        ];
        
        $response = $aiService->chat($message, $portfolio);
        
        return $this->json($response);
    }

    #[Route('/compare', name: 'app_ai_advisor_compare', methods: ['POST'])]
    public function compare(
        Request $request,
        AiAssistantService $aiService
    ): Response {
        $data = json_decode($request->getContent(), true);
        $inv1 = $data['investment1'] ?? [];
        $inv2 = $data['investment2'] ?? [];
        
        if (empty($inv1) || empty($inv2)) {
            return $this->json(['error' => 'Two investments required'], 400);
        }
        
        $response = $aiService->compareInvestments($inv1, $inv2);
        
        return $this->json($response);
    }

    #[Route('/should-invest', name: 'app_ai_advisor_should_invest', methods: ['POST'])]
    public function shouldInvest(
        Request $request,
        AiAssistantService $aiService
    ): Response {
        $data = json_decode($request->getContent(), true);
        $investment = $data['investment'] ?? [];
        
        if (empty($investment)) {
            return $this->json(['error' => 'Investment data required'], 400);
        }
        
        $response = $aiService->shouldInvest($investment);
        
        return $this->json($response);
    }
}