<?php

namespace App\Service;

use App\Service\Api\AiKeyProvider;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiInvestmentAdvisor
{
    private HttpClientInterface $httpClient;
    private string $apiKey;
    private string $apiUrl = 'https://integrate.api.nvidia.com/v1/chat/completions';
    private const MODEL = 'deepseek-ai/deepseek-v3.2';

    public function __construct(
        HttpClientInterface $httpClient,
        AiKeyProvider $keyProvider
    ) {
        $this->httpClient = $httpClient;
        $this->apiKey = $keyProvider->getNvidiaKey();
    }

    private ?array $cachedRecommendations = null;

    public function getRecommendations(array $userProfile, array $portfolio, bool $refresh = false): array
    {
        if (!$refresh && $this->cachedRecommendations !== null) {
            return $this->cachedRecommendations;
        }

        if ($refresh) {
            $this->cachedRecommendations = null;
        }

        // Handle missing or placeholder API key
        $isPlaceholder = empty($this->apiKey) || str_contains($this->apiKey, 'YOUR_');

        if ($isPlaceholder) {
            $budget = (float) ($userProfile['budget'] ?? 0);
            $this->cachedRecommendations = [
                'success' => true,
                'message' => "### 🤖 Recommandations Stratégiques (Mode Démo)\n\n" .
                           "Votre clé API NVIDIA n'est pas encore configurée. Voici une analyse basée sur vos données actuelles :\n\n" .
                           "1. **Diversification sectorielle** : Votre portefeuille actuel est concentré. Nous vous conseillons de répartir vos fonds sur au moins 3 secteurs différents pour minimiser les risques.\n\n" .
                           "2. **Optimisation du rendement** : Avec un budget de " . number_format($budget, 0, '.', ' ') . " TND, vous pouvez explorer des PME dans le secteur de l'énergie renouvelable ou de la Tech, actuellement en forte croissance.\n\n" .
                           "3. **Gestion de la trésorerie** : Assurez-vous de garder une réserve de liquidité de 15% pour saisir les opportunités d'investissement flash.",
                'recommendations' => []
            ];
            return $this->cachedRecommendations;
        }

        $prompt = $this->buildPrompt($userProfile, $portfolio);
        
        try {
            $this->cachedRecommendations = $this->callAiApi($this->apiUrl, $this->apiKey, self::MODEL, $prompt);
            return $this->cachedRecommendations;
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();

            // Fallback to demo mode if all APIs fail
            $budget = (float) ($userProfile['budget'] ?? 0);
            return [
                'success' => true,
                'is_fallback' => true,
                'message' => "### 🤖 Recommandations Stratégiques (Mode Sécurisé)\n\n" .
                           "Désolé, notre conseiller IA est temporairement indisponible (Erreur: " . $errorMsg . "). Voici une analyse basée sur vos données actuelles :\n\n" .
                           "1. **Diversification sectorielle** : Votre portefeuille actuel est concentré. Nous vous conseillons de répartir vos fonds sur au moins 3 secteurs différents pour minimiser les risques.\n\n" .
                           "2. **Optimisation du rendement** : Avec un budget de " . number_format($budget, 0, '.', ' ') . " TND, vous pouvez explorer des PME dans le secteur de l'énergie renouvelable ou de la Tech, actuellement en forte croissance.\n\n" .
                           "3. **Gestion de la trésorerie** : Assurez-vous de garder une réserve de liquidité de 15% pour saisir les opportunités d'investissement flash.",
                'recommendations' => []
            ];
        }
    }

    private function callAiApi(string $url, string $key, string $model, string $prompt): array
    {
        $headers = [
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => 'application/json',
        ];

        $response = $this->httpClient->request('POST', $url, [
                'headers' => $headers,
                'json' => [
                    'model' => self::MODEL,
                    'messages' => [
                        ['role' => 'system', 'content' => 'Tu es un conseiller expert en finance et investissement pour la plateforme CashFly. Ta mission est de fournir des recommandations stratégiques basées sur le profil de l\'utilisateur et son portefeuille actuel. Tu dois répondre EXCLUSIVEMENT en français et te concentrer sur les aspects financiers.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 1,
                    'top_p' => 0.95,
                    'max_tokens' => 4096,
                    'stream' => false,
                ],
                'timeout' => 60,
            ]);

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            $errorData = $response->toArray(false);
            $msg = $errorData['error']['message'] ?? $errorData['message'] ?? 'Unknown error';
            throw new \Exception('API Error (' . $statusCode . '): ' . $msg);
        }

        $data = $response->toArray();
        $text = $data['choices'][0]['message']['content'] ?? 'Désolé, je ne peux pas générer de recommandations pour le moment.';
        $reasoning = $data['choices'][0]['message']['reasoning_content'] ?? '';

        // Combine reasoning and content for a better display in the dashboard
        $finalMessage = "";
        if ($reasoning) {
            $finalMessage .= "### 🧠 Analyse de Réflexion\n" . $reasoning . "\n\n---\n\n";
        }
        $finalMessage .= $text;

        return [
            'success' => true,
            'message' => $finalMessage,
            'recommendations' => $this->parseRecommendations($text)
        ];
    }

    private function buildPrompt(array $user, array $portfolio): string
    {
        $date = date('d/m/Y H:i');
        return "Tu es l'expert en investissement stratégique de CashFly. Date actuelle : {$date}.\n\n" .
               "--- PROFIL UTILISATEUR ---\n" .
               "Nom: {$user['nom']} {$user['prenom']}\n" .
               "Expérience: {$user['experience']} ans\n" .
               "Budget disponible: " . number_format($user['budget'], 2) . " TND\n\n" .
               "--- PORTFEUILLE ACTUEL ---\n" .
               "Total investi: " . number_format($portfolio['totalInvested'], 2) . " TND\n" .
               "Gains/Pertes totaux: " . number_format($portfolio['totalGain'], 2) . " TND\n" .
               "Investissements actifs: {$portfolio['count']}\n\n" .
               "--- MISSION ---\n" .
               "1. Analyse personnalisée : Fournis une analyse unique de la performance basée sur les chiffres ci-dessus.\n" .
               "2. Recommandations : Donne 3 conseils stratégiques variés pour optimiser le rendement.\n" .
               "3. COMPARAISON DE SOCIÉTÉS : Analyse et compare 2 entreprises tunisiennes ou secteurs pertinents pour un investissement aujourd'hui. Explique clairement pourquoi l'une pourrait être plus avantageuse que l'autre selon le profil de l'utilisateur.\n" .
               "4. Règle absolue : Réponds UNIQUEMENT en français, sois professionnel, précis et évite les réponses génériques. Varie tes conseils à chaque analyse.";
    }

    private function parseRecommendations(string $text): array
    {
        return [];
    }

    public function analyzeRisk(float $amount, string $sector): string
    {
        if ($amount > 50000) return 'ÉLEVÉ';
        if ($sector === 'Tech' || $sector === 'Crypto') return 'MOYEN-ÉLEVÉ';
        if ($amount < 5000) return 'FAIBLE';
        return 'MOYEN';
    }
}
