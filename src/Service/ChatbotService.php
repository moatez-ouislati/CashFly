<?php

namespace App\Service;

use App\Service\Api\AiKeyProvider;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatbotService
{
    private HttpClientInterface $httpClient;
    private AiKeyProvider $keyProvider;
    private string $apiUrl = 'https://integrate.api.nvidia.com/v1/chat/completions';
    private const MODEL = 'meta/llama-3.1-8b-instruct';

    public function __construct(
        HttpClientInterface $httpClient,
        AiKeyProvider $keyProvider
    ) {
        $this->httpClient = $httpClient;
        $this->keyProvider = $keyProvider;
    }

    public function sendMessage(string $message, array $context = [], string $role = 'proprietaire'): array
    {
        if (!$this->isFinanceInvestmentRelated($message)) {
            return [
                'success' => true,
                'message' => "Désolé, je suis spécialisé uniquement dans la gestion financière et les investissements pour CashFly. Je ne peux pas répondre à des questions sur d'autres sujets (cuisine, sport, divertissement, etc.). N'hésitez pas à me poser des questions sur votre trésorerie, vos dépenses ou vos placements.",
                'suggestions' => [],
                'is_automated' => true,
            ];
        }

        $userPrompt = $this->buildPrompt($message, $context, $role);

        try {
            $apiKey = $this->keyProvider->getChatbotNvidiaKey();
            if (empty($apiKey)) {
                return [
                    'success' => false,
                    'message' => 'Configuration error: CHATBOT_NVIDIA_KEY not set. Check .env.local and clear cache.',
                ];
            }

            if ($role === 'investisseur') {
                $systemPersona = "Tu es l'expert en investissement stratégique de CashFly. Ta mission est d'aider les investisseurs à analyser leurs placements, évaluer les risques et optimiser leur portefeuille. Tu dois fournir des conseils basés sur les rendements, la diversification et les opportunités de marché. Règle absolue : Parle UNIQUEMENT en français. Sois professionnel et précis sur les chiffres.";
            } else {
                $systemPersona = "Tu es l'assistant financier de gestion pour CashFly. Ta mission est d'aider les propriétaires d'entreprise à gérer leur trésorerie, leurs revenus et leurs dépenses. Tu peux aussi exécuter des opérations comme créer une dépense ou un virement si l'utilisateur le demande. Règle absolue : Parle UNIQUEMENT en français. Sois pratique et efficace.";
            }

            $requestBody = [
                'model' => self::MODEL,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPersona],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => 1,
                'top_p' => 1,
                'max_tokens' => 4096,
                'stream' => false,
            ];
            error_log('NVIDIA API Request: ' . json_encode($requestBody));

            $response = $this->httpClient->request('POST', $this->apiUrl, [
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
                error_log('NVIDIA API Error Response: ' . $rawResponse);
                return [
                    'success' => false,
                    'message' => 'NVIDIA API Error: ' . $statusCode . ' - ' . substr($rawResponse, 0, 200),
                ];
            }

            $data = $response->toArray();
            
            // DEBUG
            error_log('NVIDIA API Response OK: ' . ($data['choices'][0]['message']['content'] ?? '[empty]'));

            $text = $data['choices'][0]['message']['content'] ?? '';
            $reasoning = $data['choices'][0]['message']['reasoning_content'] ?? '';

            // If text is empty but reasoning is there (happens with some thinking models)
            if (empty($text) && !empty($reasoning)) {
                $text = $reasoning;
                $reasoning = '';
            }

            $finalMessage = "";
            if (!empty($reasoning)) {
                $finalMessage .= "### 🧠 Analyse de Réflexion\n" . $reasoning . "\n\n---\n\n";
            }
            $finalMessage .= $text;

            // Extract action
            $action = $this->extractAction($text, $message);
            
            // Clean JSON from text if it's there
            if ($action !== null) {
                $finalMessage = preg_replace('/\{[^}]+\}/s', '', $finalMessage);
                $finalMessage = trim($finalMessage);
                if (empty($finalMessage)) {
                    $finalMessage = "Je vais m'en occuper immédiatement.";
                }
            }
            
            return [
                'success' => true,
                'message' => $finalMessage,
                'action' => $action,
                'suggestions' => [],
                'is_automated' => false,
            ];

        } catch (\Exception $e) {
            $error = $e->getMessage();
            error_log('ChatbotService API Error: ' . $error);

            // Timeout - return fallback
            if (strpos($error, 'timeout') !== false || strpos($error, 'Idle') !== false) {
                return $this->getFallbackResponse($message, $context);
            }

            return [
                'success' => false,
                'message' => "Erreur API NVIDIA: " . substr($error, 0, 150),
            ];
        }
    }

    private function formatApiError(int $statusCode, array $data): string
    {
        $message = $data['error']['message'] ?? $data['message'] ?? $data['detail'] ?? '';
        if (is_array($message)) {
            $message = json_encode($message);
        }

        $message = trim((string) $message);
        if ($message === '') {
            $message = 'requete refusee par NVIDIA. Verifiez le modele et le format du payload.';
        }

        return 'Erreur API NVIDIA (' . $statusCode . '): ' . substr($message, 0, 250);
    }

    private function isFinanceInvestmentRelated(string $message): bool
    {
        $lowerMsg = strtolower($message);

        // Common greetings are allowed to start a conversation
        $greetings = ['hello', 'hi', 'bonjour', 'salut', 'hey', 'bonsoir', 'ca va', 'comment vas-tu'];
        foreach ($greetings as $greet) {
            if (str_contains($lowerMsg, $greet)) {
                return true;
            }
        }

        $financeKeywords = [
            'invest', 'finance', 'fiancaca', 'argent', 'budget', 'rendement', 'roi', 'benefice',
            'perte', 'gain', 'portefeuille', 'action', 'obligation', 'bourse',
            'marche financier', 'diversifier', 'diversification', 'epargne',
            'economiser', 'credit', 'pret', 'interet', 'taux', 'inflation',
            'placement', 'actif', 'passif', 'liquidite', 'capital', 'dividende',
            'risque', 'assurance', 'banque', 'tnd', 'dinar', 'crypto', 'bitcoin',
            'forex', 'trading', 'analyse financiere', 'etude de marche',
            'entreprise', 'startup', 'fonds', 'etf', 'immobilier', 'taxe',
            'impot', 'fiscalite', 'comptabilite', 'revenu', 'salaire',
            'comment investir', 'quel placement', 'meilleur investissement',
            'strategie d\'investissement', 'conseil financier', 'valeur mobiliere',
            'obligation d\'etat', 'bons du tresor', 'marche tunisien',
            'performance financiere', 'croissance', 'recession', 'crise economique',
            'argent', 'cash', 'monnaie', 'devises', 'portefeuille', 'investisseur',
            'projets', 'opportunites', 'rentable', 'rentabilite', 'economie',
            'wealth', 'money', 'profit', 'loss', 'return', 'interest rate',
            'stock market', 'bond', 'mutual fund', 'pension', 'retirement',
            'inflation', 'deflation', 'gdp', 'economic', 'financial advisor',
            'asset management', 'portfolio', 'yield', 'capital gain',
            'cash flow', 'net worth', 'savings', 'loan', 'mortgage',
            'debt', 'equity', 'market cap', 'valuation', 'ipo',
            'dépense', 'revenu', 'virement', 'compte', 'solde', 'trésorerie', 'facture'
        ];

        foreach ($financeKeywords as $keyword) {
            if (str_contains($lowerMsg, $keyword)) {
                return true;
            }
        }

        $nonFinancePatterns = [
            '/^comment faire.*gateau/', '/^recette de/', '/^comment cuisiner/',
            '/^meilleur film/', '/^meilleure serie/', '/^qui est.*acteur/',
            '/^comment jouer.*football/', '/^regle du.*jeu/',
            '/^histoire de.*pays/', '/^qui a invente/',
            '/^meteo/', '/^quelle temperature/',
            '/^blague/', '/^raconte.*histoire/',
            '/^comment reparer.*voiture/', '/^recette .* cuisine/',
        ];

        foreach ($nonFinancePatterns as $pattern) {
            if (preg_match($pattern, $lowerMsg)) {
                return false;
            }
        }

        $questionWords = ['comment', 'quoi', 'pourquoi', 'ou', 'qui', 'quel', 'quand', 'est-ce que', 'peux-tu', 'peut-on'];
        $hasQuestion = false;
        foreach ($questionWords as $qw) {
            if (str_starts_with($lowerMsg, $qw) || str_contains($lowerMsg, '?' . $qw)) {
                $hasQuestion = true;
                break;
            }
        }

        $genericTopics = ['cuisine', 'sport', 'film', 'musique', 'voyage', 'meteo', 'jeu', 'animaux', 'sante', 'medecine', 'ecole', 'amour', 'politique', 'religion', 'guerre'];
        foreach ($genericTopics as $topic) {
            if (str_contains($lowerMsg, $topic)) {
                if (!$hasQuestion || !str_contains($lowerMsg, 'invest')) {
                    return false;
                }
            }
        }

        return true;
    }

    private function buildPrompt(string $message, array $context, string $role): string
    {
        $prompt = "Rôle de l'utilisateur : " . ($role === 'investisseur' ? 'Investisseur' : 'Propriétaire d\'entreprise') . "\n";
        $prompt .= "Message de l'utilisateur : \"" . $message . "\"\n\n";
        
        $prompt .= "--- CONTEXTE SYSTÈME ---\n";

        if ($role === 'investisseur' && isset($context['stats'])) {
            $stats = $context['stats'];
            $prompt .= "Statistiques du portefeuille :\n";
            $prompt .= "- Total investi : " . number_format($stats['totalInvested'] ?? 0, 2) . " TND\n";
            $prompt .= "- Gains totaux : " . number_format($stats['totalGain'] ?? 0, 2) . " TND\n";
            $prompt .= "- Nombre d'investissements : " . ($stats['count'] ?? 0) . "\n";
            $prompt .= "- Budget disponible : " . number_format($context['user_budget'] ?? 0, 2) . " TND\n";
            $prompt .= "- Expérience : " . ($context['user_experience'] ?? 'N/A') . " ans\n\n";
        }
        
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
        
        $prompt .= "\n--- INSTRUCTIONS ---\n";
        $prompt .= "1. Règle absolue : Tu DOIS parler UNIQUEMENT en français.\n";
        
        if ($role === 'investisseur') {
            $prompt .= "2. Tu es l'expert en investissement. Réponds UNIQUEMENT aux questions liées aux placements, rendements, risques et stratégie d'investissement.\n";
            $prompt .= "3. Utilise les statistiques du portefeuille fournies pour donner des conseils personnalisés.\n";
            $prompt .= "4. Ne propose JAMAIS d'opérations de trésorerie (dépenses/virements) à un investisseur.\n";
        } else {
            $prompt .= "2. Tu es l'assistant de gestion. Réponds UNIQUEMENT aux questions liées à la finance d'entreprise, la trésorerie et les opérations.\n";
            $prompt .= "3. Tu peux fournir des conseils financiers, résumer les comptes ou analyser le solde.\n";
            $prompt .= "4. SI tu détectes que l'utilisateur veut CRÉER UNE DÉPENSE ou un REVENU, tu dois extraire les informations et répondre avec un message amical suivi EXACTEMENT de ce bloc JSON à la fin de ta réponse :\n";
            $prompt .= '   Dépense : {"action":"create_operation","type":"depense","montant":XXX, "tresorerie_id":YYY}' . "\n";
            $prompt .= '   Revenu : {"action":"create_operation","type":"revenu","montant":XXX, "tresorerie_id":YYY}' . "\n";
            $prompt .= "   (Remplace XXX par le montant extrait du texte, et YYY par l'ID du compte s'il est spécisé, sinon omet tresorerie_id).\n";
            $prompt .= "5. SI tu détectes que l'utilisateur veut faire un VIREMENT entre comptes, réponds avec un message amical suivi de ce bloc JSON à la fin :\n";
            $prompt .= '   Virement : {"action":"transfer","montant":XXX,"from_tresorerie_id":AAA,"to_tresorerie_id":BBB}' . "\n";
            $prompt .= "   (Remplace XXX par le montant extrait, AAA par l'ID du compte source, BBB par l'ID du compte destination).\n";
            $prompt .= "6. N'ajoute AUCUN JSON si l'utilisateur pose juste une question ou ne demande pas explicitement de faire une opération.\n";
        }
        
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
