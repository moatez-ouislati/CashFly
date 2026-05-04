<?php

namespace App\Controller\Api;

use App\Service\Api\AiAssistantService;
use App\Service\RiskScoringService;
use App\Entity\Investissement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/ai-advisor')]
class AiAdvisorController extends AbstractController
{
    #[Route('/', name: 'app_ai_advisor')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response {
        if (!$this->isGranted('ROLE_INVESTISSEUR')) {
            throw $this->createAccessDeniedException('Accès réservé aux investisseurs.');
        }
        return $this->render('api/ai_advisor.html.twig', [
            'quickActions' => [
                'portfolio' => 'Analyser mon portefeuille',
                'strategy' => 'Strategie balancee',
                'safe' => 'Strategie sicher',
            ],
        ]);
    }

    #[Route('/analyze-initial', name: 'app_ai_advisor_analyze_initial', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function analyzeInitial(
        Request $request,
        AiAssistantService $aiService,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        if (!$this->isGranted('ROLE_INVESTISSEUR')) {
            return $this->json(['error' => 'Accès refusé'], 403);
        }

        $message = $request->query->get('message', '');
        $quickAction = $request->query->get('action', '');

        $user = $this->getUser();
        $portfolio = [
            'totalInvested' => 0,
            'totalGain' => 0,
            'investmentsCount' => 0,
            'riskDistribution' => ['LOW' => 0, 'MEDIUM' => 0, 'HIGH' => 0],
        ];

        $investments = $entityManager->getRepository(Investissement::class)->findBy(['investisseur' => $user]);
        $portfolio['investmentsCount'] = count($investments);

        $totalInvested = 0;
        foreach ($investments as $inv) {
            $totalInvested += (float) $inv->getMontant();
        }

        $portfolio['totalInvested'] = $totalInvested;
        $portfolio['totalGain'] = 0;

        try {
            $aiResponse = null;

            if ($message) {
                $aiResponse = $aiService->chat($message, $portfolio);
            } elseif ($quickAction === 'portfolio' && count($investments) > 0) {
                $aiResponse = $aiService->analyzePortfolio($portfolio);
            } elseif ($quickAction === 'strategy') {
                $aiResponse = $aiService->explainStrategy('balanced');
            } elseif ($quickAction === 'safe') {
                $aiResponse = $aiService->explainStrategy('safe');
            } else {
                $aiResponse = [
                    'response' => "Bonjour ! Je suis votre conseiller en investissement. Comment puis-je vous aider aujourd'hui ?",
                    'model' => 'z-ai/glm4.7'
                ];
            }

            // Convert to chatbot format
            $response = [
                'success' => true,
                'message' => $aiResponse['response'] ?? "Bonjour ! Je suis votre conseiller en investissement.",
                'suggestions' => $aiResponse['suggestions'] ?? [],
                'is_automated' => $aiResponse['is_automated'] ?? false,
                'reasoning' => $aiResponse['reasoning'] ?? '',
                'model' => $aiResponse['model'] ?? 'z-ai/glm4.7',
            ];

            return $this->json($response);
        } catch (\Exception $e) {
            error_log('AI Advisor analyze-initial Error: ' . $e->getMessage());
            return $this->json([
                'success' => true,
                'message' => "Bonjour ! Je suis votre conseiller en investissement. Comment puis-je vous aider aujourd'hui ?",
                'suggestions' => [],
                'is_automated' => true,
                'model' => 'z-ai/glm4.7'
            ]);
        }
    }

    #[Route('/chat', name: 'app_ai_advisor_chat', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function chat(
        Request $request,
        AiAssistantService $aiService,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        if (!$this->isGranted('ROLE_INVESTISSEUR')) {
            return $this->json(['error' => 'Accès refusé'], 403);
        }

        $data = json_decode($request->getContent(), true);
        $message = $data['message'] ?? '';

        if (empty($message)) {
            return $this->json(['error' => 'Message is required'], 400);
        }

        $user = $this->getUser();
        $investments = $entityManager->getRepository(Investissement::class)->findBy(['investisseur' => $user]);
        $portfolio = [
            'totalInvested' => array_sum(array_map(fn($i) => (float) $i->getMontant(), $investments)),
            'investmentsCount' => count($investments),
            'riskDistribution' => ['LOW' => 0, 'MEDIUM' => 0, 'HIGH' => 0],
        ];

        try {
            error_log('AI Advisor: Calling chat with message: ' . substr($message, 0, 50));
            $aiResponse = $aiService->chat($message, $portfolio);
            error_log('AI Advisor: Got response: ' . json_encode($aiResponse));

            // Convert AI Assistant format to Chatbot format
            $response = [
                'success' => true,
                'message' => $aiResponse['response'] ?? "Désolé, je ne peux pas répondre pour le moment.",
                'suggestions' => $aiResponse['suggestions'] ?? [],
                'is_automated' => $aiResponse['is_automated'] ?? false,
                'reasoning' => $aiResponse['reasoning'] ?? '',
                'model' => $aiResponse['model'] ?? 'z-ai/glm4.7',
            ];

            // Handle timeout/errors gracefully like chatbot
            if (empty($response['message']) || (isset($aiResponse['error']) && !empty($aiResponse['error']))) {
                $response = [
                    'success' => true,
                    'message' => "🤖 L'assistant met trop de temps à répondre. Veuillez réessayer ou reformuler votre demande.",
                    'suggestions' => [],
                    'is_automated' => true,
                    'model' => 'z-ai/glm4.7',
                ];
            }

            return $this->json($response);
        } catch (\Exception $e) {
            error_log('AI Advisor Exception: ' . $e->getMessage());
            return $this->json([
                'success' => true,
                'message' => "Désolé, je ne peux pas répondre pour le moment. " . substr($e->getMessage(), 0, 100),
                'suggestions' => [],
                'is_automated' => true,
                'model' => 'z-ai/glm4.7',
            ]);
        }
    }

    #[Route('/compare', name: 'app_ai_advisor_compare', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function compare(
        Request $request,
        AiAssistantService $aiService
    ): JsonResponse {
        if (!$this->isGranted('ROLE_INVESTISSEUR')) {
            return $this->json(['error' => 'Accès refusé'], 403);
        }

        $data = json_decode($request->getContent(), true);
        $inv1 = $data['inv1'] ?? ($data['investment1'] ?? []);
        $inv2 = $data['inv2'] ?? ($data['investment2'] ?? []);

        if (empty($inv1) || empty($inv2)) {
            return $this->json(['error' => 'Two investments required'], 400);
        }

        try {
            $aiResponse = $aiService->compareInvestments($inv1, $inv2);
            
            // Convert to chatbot format
            $response = [
                'success' => true,
                'message' => $aiResponse['response'] ?? "Désolé, je ne peux pas comparer ces investissements pour le moment.",
                'reasoning' => $aiResponse['reasoning'] ?? '',
                'model' => $aiResponse['model'] ?? 'z-ai/glm4.7',
            ];
            
            return $this->json($response);
        } catch (\Exception $e) {
            error_log('AI Advisor compare Error: ' . $e->getMessage());
            return $this->json([
                'success' => true,
                'message' => "Désolé, je ne peux pas comparer ces investissements pour le moment.",
                'is_automated' => true,
                'model' => 'z-ai/glm4.7',
            ]);
        }
    }

    #[Route('/should-invest', name: 'app_ai_advisor_should_invest', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function shouldInvest(
        Request $request,
        AiAssistantService $aiService
    ): JsonResponse {
        if (!$this->isGranted('ROLE_INVESTISSEUR')) {
            return $this->json(['error' => 'Accès refusé'], 403);
        }

        $data = json_decode($request->getContent(), true);
        $investment = $data['investment'] ?? [];

        if (empty($investment)) {
            return $this->json(['error' => 'Investment data required'], 400);
        }

        try {
            $aiResponse = $aiService->shouldInvest($investment);
            
            // Convert to chatbot format
            $response = [
                'success' => true,
                'message' => $aiResponse['response'] ?? "Désolé, je ne peux pas analyser cet investissement pour le moment.",
                'reasoning' => $aiResponse['reasoning'] ?? '',
                'model' => $aiResponse['model'] ?? 'z-ai/glm4.7',
            ];
            
            return $this->json($response);
        } catch (\Exception $e) {
            error_log('AI Advisor should-invest Error: ' . $e->getMessage());
            return $this->json([
                'success' => true,
                'message' => "Désolé, je ne peux pas analyser cet investissement pour le moment.",
                'is_automated' => true,
                'model' => 'z-ai/glm4.7',
            ]);
        }
    }
}