<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatbotService
{
    private HttpClientInterface $httpClient;
    private string $apiKey;
    private string $apiUrl = 'https://integrate.api.nvidia.com/v1/chat/completions';

    public function __construct(
        HttpClientInterface $httpClient,
        #[Autowire(env: 'NVIDIA_API_KEY')] string $apiKey
    ) {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    public function sendMessage(string $message, array $context = []): array
    {
        $userPrompt = $this->buildPrompt($message, $context);

        try {
            $response = $this->httpClient->request('POST', $this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'mistralai/mixtral-8x7b-instruct-v0.1',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Tu es l\'assistant financier de CashFly. Règle absolue : Tu DOIS parler EXCLUSIVEMENT en français. Réponds poliment aux salutations. Limite-toi à la finance, la trésorerie et les opérations. Refuse les autres sujets. Sois concis et professionnel.'],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.2,
                    'max_tokens' => 250,
                ],
                'timeout' => 45,
            ]);

            $statusCode = $response->getStatusCode();
            $data = $response->toArray();

            if ($statusCode !== 200) {
                return [
                    'success' => false,
                    'message' => 'API Error: ' . $statusCode,
                ];
            }

            $text = $data['choices'][0]['message']['content'] ?? '';

            // Extract action
            $action = $this->extractAction($text, $message);
            
            // Clean JSON from text if it's there
            if ($action !== null) {
                $text = preg_replace('/\{[^}]+\}/s', '', $text);
                $text = trim($text);
                if (empty($text)) {
                    $text = "Je vais m'en occuper immédiatement.";
                }
            }
            
            return [
                'success' => true,
                'message' => $text,
                'action' => $action,
                'suggestions' => [],
                'is_automated' => false,
            ];

        } catch (\Exception $e) {
            $error = $e->getMessage();
            
            // Timeout - return fallback
            if (strpos($error, 'timeout') !== false || strpos($error, 'Idle') !== false) {
                return $this->getFallbackResponse($message, $context);
            }
            
            return [
                'success' => false,
                'message' => 'Error: ' . substr($error, 0, 100),
            ];
        }
    }

    private function buildPrompt(string $message, array $context): string
    {
        $prompt = "Message de l'utilisateur : \"" . $message . "\"\n\n";
        
        $prompt .= "--- CONTEXTE SYSTÈME ---\n";
        if (!empty($context) && isset($context['tresoreries']) && is_array($context['tresoreries'])) {
            $accountsInfos = [];
            foreach ($context['tresoreries'] as $t) {
                if (isset($t['id'], $t['nom'], $t['solde'])) {
                    $accountsInfos[] = '- Compte "' . $t['nom'] . '" (ID: ' . $t['id'] . ') : ' . number_format($t['solde'], 2, '.', ' ') . ' ' . ($t['devise'] ?? 'TND');
                }
            }
            if (!empty($accountsInfos)) {
                $prompt .= "L'utilisateur possède les comptes de trésorerie suivants :\n";
                $prompt .= implode("\n", $accountsInfos) . "\n\n";
            }
        }
        
        $prompt .= "--- INSTRUCTIONS ---\n";
        $prompt .= "1. Règle absolue : Tu DOIS parler UNIQUEMENT en français.\n";
        $prompt .= "2. Tu es l'assistant financier de CashFly. Tu dois répondre UNIQUEMENT aux questions liées à la finance, la trésorerie, les opérations et les économies. Si on te demande autre chose, refuse poliment.\n";
        $prompt .= "3. Tu peux saluer l'utilisateur s'il te dit bonjour.\n";
        $prompt .= "4. Tu peux fournir des conseils financiers, résumer les comptes ou analyser le solde de l'utilisateur.\n";
        $prompt .= "5. Ton rôle est d'analyser le texte de l'utilisateur. SI tu détectes que l'utilisateur veut CRÉER UNE DÉPENSE ou un REVENU, tu dois extraire les informations et répondre avec un message amical suivi EXACTEMENT de ce bloc JSON à la fin de ta réponse :\n";
        $prompt .= '   Dépense : {"action":"create_operation","type":"depense","montant":XXX, "tresorerie_id":YYY}' . "\n";
        $prompt .= '   Revenu : {"action":"create_operation","type":"revenu","montant":XXX, "tresorerie_id":YYY}' . "\n";
        $prompt .= "   (Remplace XXX par le montant extrait du texte, et YYY par l'ID du compte s'il est spécisé, sinon omet tresorerie_id).\n";
        $prompt .= "6. SI tu détectes que l'utilisateur veut faire un VIREMENT entre comptes, réponds avec un message amical suivi de ce bloc JSON à la fin :\n";
        $prompt .= '   Virement : {"action":"transfer","montant":XXX,"from_tresorerie_id":AAA,"to_tresorerie_id":BBB}' . "\n";
        $prompt .= "   (Remplace XXX par le montant extrait, AAA par l'ID du compte source, BBB par l'ID du compte destination).\n";
        $prompt .= "7. N'ajoute AUCUN JSON si l'utilisateur pose juste une question ou ne demande pas explicitement de faire une opération.\n";
        
        return $prompt;
    }

    private function extractAction(string $text, string $userMessage): ?array
    {
        // Look for JSON in response
        if (preg_match('/\{[^}]+\}/s', $text, $matches)) {
            $data = json_decode($matches[0], true);
            if (isset($data['action'])) {
                return $data;
            }
        }
        
        // Parse user message for amounts
        $msg = strtolower($userMessage);
        
        // Expense
        if (preg_match('/dépense.*?(\d+(?:[,\.]\d+)?)/i', $msg, $m) ||
            (preg_match('/(?:ajoute?|crée?).*?(\d+(?:[,\.]\d+)?)/i', $msg, $m) && strpos($msg, 'dépense') !== false)) {
            return [
                'action' => 'create_operation',
                'type' => 'depense',
                'montant' => (float)str_replace(',', '.', $m[1]),
                'description' => 'Dépense via chat',
            ];
        }
        
        // Income
        if (preg_match('/revenu.*?(\d+(?:[,\.]\d+)?)/i', $msg, $m) ||
            (preg_match('/(?:ajoute?|crée?).*?(\d+(?:[,\.]\d+)?)/i', $msg, $m) && strpos($msg, 'revenu') !== false)) {
            return [
                'action' => 'create_operation',
                'type' => 'revenu',
                'montant' => (float)str_replace(',', '.', $m[1]),
                'description' => 'Revenu via chat',
            ];
        }
        
        return null;
    }

    private function getFallbackResponse(string $message, array $context): array
    {
        $msg = strtolower($message);
        $tresoreries = $context['tresoreries'] ?? [];
        
        // Check for expense/income in message
        $action = $this->extractAction($message, $message);
        
        $r = "🤖 **Assistant CashFly**\n\n";
        
        if (!empty($tresoreries)) {
            $r .= "📊 **Vos Comptes:**\n";
            foreach ($tresoreries as $t) {
                $r .= "• " . $t['nom'] . ": **" . number_format($t['solde'], 2, ',', ' ') . " " . ($t['devise'] ?? 'TND') . "**\n";
            }
        }
        
        return [
            'success' => true,
            'message' => $r,
            'action' => $action,
            'suggestions' => [],
            'is_automated' => true,
        ];
    }

    public function suggestCategory(string $description, float $montant, string $type): array
    {
        return $this->sendMessage("Categorie: $description", []);
    }

    public function generateFinancialSummary(array $data): array
    {
        return $this->sendMessage("Resume", $data);
    }
}
