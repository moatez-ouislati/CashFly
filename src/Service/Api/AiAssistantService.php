<?php

namespace App\Service\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiAssistantService
{
    private HttpClientInterface $httpClient;
    private AiKeyProvider $keyProvider;
    private const API_URL = 'https://integrate.api.nvidia.com/v1/chat/completions';
    private const MODEL = 'meta/llama-3.1-8b-instruct';

    public function __construct(
        HttpClientInterface $httpClient,
        AiKeyProvider $keyProvider
    ) {
        $this->httpClient = $httpClient;
        $this->keyProvider = $keyProvider;
    }

    public function chat(string $message, array $context = []): array
    {
        if (!$this->isFinanceInvestmentRelated($message)) {
            return [
                'response' => "Désolé, je suis spécialisé uniquement dans la gestion financière et les investissements. Je ne peux pas répondre à des questions sur d'autres sujets. N'hésitez pas à me poser des questions sur les investissements, le marché financier, la gestion de portefeuille ou les stratégies d'investissement en Tunisie.",
                'model' => self::MODEL,
                'is_automated' => true,
                'isRestricted' => true,
            ];
        }

        $systemPrompt = "Tu es l'expert en investissement stratégique de CashFly. Ta mission est d'aider les investisseurs à analyser leurs placements, évaluer les risques et optimiser leur portefeuille. Tu dois fournir des conseils basés sur les rendements, la diversification et les opportunités de marché. Règle absolue : Parle UNIQUEMENT en français. Sois professionnel et précis sur les chiffres.";

        $userPrompt = $this->buildUserPrompt($message, $context);

        return $this->callNvidiaApi($systemPrompt, $userPrompt);
    }

    public function compareInvestments(array $inv1, array $inv2): array
    {
        $systemPrompt = "Tu es l'expert en investissement stratégique de CashFly. Compare les investissements et recommande le meilleur choix. Parle UNIQUEMENT en français.";
        
        $userPrompt = "Compare ces deux investissements et recommande lequel est plus sûr et plus rentable:

Investissement 1: {$inv1['name']} - {$inv1['amount']} TND, Rendement attendu: {$inv1['return']}%, Durée: {$inv1['duration']} mois

Investissement 2: {$inv2['name']} - {$inv2['amount']} TND, Rendement attendu: {$inv2['return']}%, Durée: {$inv2['duration']} mois

Fournis une comparaison claire avec avantages et inconvénients de chaque option.";

        return $this->callNvidiaApi($systemPrompt, $userPrompt);
    }

    public function analyzePortfolio(array $portfolio): array
    {
        $systemPrompt = "Tu es l'expert en investissement stratégique de CashFly. Analyse les portefeuilles d'investissement. Parle UNIQUEMENT en français.";
        
        $userPrompt = "Analyse ce portefeuille d'investissement:

Total Investi: {$portfolio['totalInvested']} TND
Gains Totaux: {$portfolio['totalGain']} TND
Nombre d'Investissements: {$portfolio['investmentsCount']}

Fournis: 1. Évaluation globale de la santé du portefeuille 2. Résumé des risques 3. Recommandations d'amélioration";

        return $this->callNvidiaApi($systemPrompt, $userPrompt);
    }

    public function shouldInvest(array $investment): array
    {
        $systemPrompt = "Tu es l'expert en investissement stratégique de CashFly. Analyse les opportunités d'investissement. Parle UNIQUEMENT en français.";
        
        $userPrompt = "Devrais-je investir dans ceci? Analyse et donne une recommandation OUI/NON:

Investissement: {$investment['name']}
Montant: {$investment['amount']} TND
Rendement Attendu: {$investment['return']}%
Durée: {$investment['duration']} mois";

        return $this->callNvidiaApi($systemPrompt, $userPrompt);
    }

    public function explainStrategy(string $strategyType): array
    {
        $strategies = [
            'safe' => 'stratégie d\'investissement sécurisée pour investisseurs conservateurs',
            'aggressive' => 'stratégie d\'investissement agressive à haut rendement',
            'balanced' => 'stratégie de portefeuille équilibrée',
        ];

        $strategyDesc = $strategies[$strategyType] ?? 'stratégie équilibrée';
        
        $systemPrompt = "Tu es l'expert en investissement stratégique de CashFly. Explique les stratégies d'investissement. Parle UNIQUEMENT en français.";
        
        $userPrompt = "Explique la {$strategyDesc}.

Inclus: 1. Ce que cela signifie 2. Meilleurs types d'investissement 3. Rendements typiques 4. Risques 5. Qui devrait l'utiliser";

        return $this->callNvidiaApi($systemPrompt, $userPrompt);
    }

    private function callNvidiaApi(string $systemPrompt, string $userPrompt): array
    {
        try {
            $apiKey = $this->keyProvider->getInvestmentNvidiaKey();
            
            error_log('AI Advisor: API Key length = ' . strlen($apiKey));
            
            if (empty($apiKey)) {
                error_log('AI Advisor: ERROR - API Key is empty!');
                return [
                    'response' => "⚠️ Configuration error: INVESTMENT_NVIDIA_KEY not set. Vérifiez votre fichier .env.local",
                    'model' => self::MODEL,
                    'is_automated' => true,
                ];
            }

            $requestBody = [
                'model' => self::MODEL,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => 1,
                'top_p' => 1,
                'max_tokens' => 4096,
                'stream' => false,
            ];
            
            error_log('AI Advisor: Sending request to NVIDIA API');

            $response = $this->httpClient->request('POST', self::API_URL, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
                'timeout' => 60,
            ]);

            $statusCode = $response->getStatusCode();
            
            if ($statusCode !== 200) {
                $rawResponse = $response->getContent(false);
                error_log('AI Advisor: NVIDIA API Error ' . $statusCode . ': ' . substr($rawResponse, 0, 200));
                return [
                    'response' => "❌ Erreur API NVIDIA (code {$statusCode}). Vérifiez votre clé API.",
                    'model' => self::MODEL,
                    'is_automated' => true,
                ];
            }

            $data = $response->toArray();
            
            $text = $data['choices'][0]['message']['content'] ?? '';
            $reasoning = $data['choices'][0]['message']['reasoning_content'] ?? '';

            if (empty($text) && !empty($reasoning)) {
                $text = $reasoning;
                $reasoning = '';
            }

            error_log('AI Advisor: Got response, length = ' . strlen($text));

            return [
                'response' => $text,
                'reasoning' => $reasoning,
                'model' => self::MODEL,
                'is_automated' => false,
            ];
            
        } catch (\Exception $e) {
            error_log('AI Advisor: Exception - ' . $e->getMessage());
            return [
                'response' => "❌ Erreur lors de l'appel à l'API: " . substr($e->getMessage(), 0, 100),
                'model' => self::MODEL,
                'is_automated' => true,
            ];
        }
    }

    private function buildUserPrompt(string $message, array $context): string
    {
        $prompt = $message;
        
        if (!empty($context)) {
            $prompt .= "\n\nContexte du portefeuille:\n";
            if (isset($context['totalInvested'])) {
                $prompt .= "- Total investi: {$context['totalInvested']} TND\n";
            }
            if (isset($context['investmentsCount'])) {
                $prompt .= "- Nombre d'investissements: {$context['investmentsCount']}\n";
            }
            if (isset($context['totalGain'])) {
                $prompt .= "- Gains totaux: {$context['totalGain']} TND\n";
            }
        }
        
        return $prompt;
    }

    private function isFinanceInvestmentRelated(string $message): bool
    {
        $lowerMsg = strtolower($message);

        $greetings = ['bonjour', 'salut', 'hello', 'hi', 'hey'];
        foreach ($greetings as $greet) {
            if (str_contains($lowerMsg, $greet)) {
                return true;
            }
        }

        $financeKeywords = [
            'invest', 'finance', 'argent', 'budget', 'rendement', 'roi', 'benefice',
            'perte', 'gain', 'portefeuille', 'action', 'obligation', 'bourse',
            'risque', 'diversification', 'strategie', 'taux', 'interet',
            'placement', 'investir', 'comparer', 'analyse', 'conseil',
        ];

        foreach ($financeKeywords as $keyword) {
            if (str_contains($lowerMsg, $keyword)) {
                return true;
            }
        }

        return true;
    }
}
