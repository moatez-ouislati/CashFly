<?php

namespace App\Service\Api;

class AiRecommendationService
{
    private const OPENAI_URL = 'https://api.openai.com/v1/chat/completions';
    private const OPENAI_KEY = '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';
    private const MODEL = 'gpt-4o-mini';

    private const OPENROUTER_URL = 'https://openrouter.ai/api/v1/chat/completions';
    private const OPENROUTER_KEY = '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';

    public function getRecommendations(array $portfolioData): array
    {
        $prompt = $this->buildPrompt($portfolioData);

        $result = $this->callOpenAI($prompt);

        if (!$result) {
            $result = $this->callOpenRouter($prompt);
        }

        if (!$result) {
            return $this->getFallbackRecommendations();
        }

        return $this->parseRecommendations($result);
    }

    private function buildPrompt(array $portfolioData): string
    {
        $totalInvested = $portfolioData['totalInvested'] ?? 0;
        $totalGain = $portfolioData['totalGain'] ?? 0;
        $investmentsCount = $portfolioData['investmentsCount'] ?? 0;
        $topSectors = $portfolioData['topSectors'] ?? [];

        return "En tant qu'expert financier tunisien, analysez ce portfolio d'investissement et fournissez 4 recommandations stratégiques:

Portfolio actuel:
- Montant total investi: {$totalInvested} TND
- Gains totaux: {$totalGain} TND  
- Nombre d'investissements: {$investmentsCount}
- Secteurs principaux: " . implode(', ', $topSectors) . "

Fournissez EXACTEMENT 4 recommandations au format JSON:
{
    \"recommendations\": [
        {
            \"title\": \"Titre court\",
            \"description\": \"Description détaillée de 2-3 phrases\",
            \"priority\": \"high|medium|low\",
            \"icon\": \"ti-icon-name\"
        }
    ]
}

Les recommandations doivent être en français, pertinentes pour le marché tunisien et Maghreb.";
    }

    private function callOpenAI(string $prompt): ?string
    {
        $data = [
            'model' => self::MODEL,
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un expert financier tunisien. Réponds uniquement en JSON.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 1000,
        ];

        return $this->makeApiCall(self::OPENAI_URL, $data, self::OPENAI_KEY);
    }

    private function callOpenRouter(string $prompt): ?string
    {
        $data = [
            'model' => 'mistralai/mistral-7b-instruct:free',
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un expert financier tunisien. Réponds uniquement en JSON.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'max_tokens' => 1000,
        ];

        return $this->makeApiCall(self::OPENROUTER_URL, $data, self::OPENROUTER_KEY);
    }

    private function makeApiCall(string $url, array $data, string $apiKey): ?string
    {
        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $apiKey,
                ],
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 || !$response) {
                return null;
            }

            $result = json_decode($response, true);
            return $result['choices'][0]['message']['content'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseRecommendations(string $content): array
    {
        $content = trim($content);

        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $content = $matches[0];
        }

        $data = json_decode($content, true);

        if (isset($data['recommendations']) && is_array($data['recommendations'])) {
            return $data['recommendations'];
        }

        return $this->getFallbackRecommendations();
    }

    private function getFallbackRecommendations(): array
    {
        return [
            [
                'title' => 'Diversifiez vos investissements',
                'description' => 'Envisagez de répartir vos investissements sur plusieurs secteurs pour minimiser les risques et maximiser les opportunités de croissance.',
                'priority' => 'high',
                'icon' => 'ti-arrows-shuffle',
            ],
            [
                'title' => 'Surveillez le marché tunisien',
                'description' => 'Le marché tunisien offre des opportunités uniques. Restez informé des évolutions économiques et des nouvelles réglementations.',
                'priority' => 'medium',
                'icon' => 'ti-eye',
            ],
            [
                'title' => 'Réinvestissez vos gains',
                'description' => 'Considérez réinvestir une partie de vos bénéfices pour profiter de l\'effet de composition et accélérer la croissance de votre portfolio.',
                'priority' => 'high',
                'icon' => 'ti-refresh',
            ],
            [
                'title' => 'Consultez un expert',
                'description' => 'Pour des décisions importantes, n\'hésitez pas à consulter un conseiller financier spécialisé dans les investissements au Maghreb.',
                'priority' => 'low',
                'icon' => 'ti-user',
            ],
        ];
    }
}
