<?php

namespace App\Service\Api;

class AiAssistantService
{
    private const HF_API_URL = 'https://api-inference.huggingface.co';
    private const HF_MODEL = 'mistralai/Mistral-7B-Instruct-v0.3';
    private string $hfToken;

    public function __construct()
    {
        $this->hfToken = $_ENV['HF_TOKEN'] ?? ($_SERVER['HF_TOKEN'] ?? '');
    }

    public function setToken(string $token): self
    {
        $this->hfToken = $token;
        return $this;
    }

    public function chat(string $message, array $context = []): array
    {
        $systemPrompt = $this->buildSystemPrompt($context);

        try {
            $response = $this->callHuggingFace($systemPrompt, $message);
            return [
                'response' => $response,
                'model' => 'meta-llama/Meta-Llama-3-8B-Instruct',
            ];
        } catch (\Exception $e) {
            return [
                'response' => 'Erreur: ' . $e->getMessage(),
                'model' => 'meta-llama/Meta-Llama-3-8B-Instruct',
                'error' => true,
            ];
        }
    }

    public function compareInvestments(array $inv1, array $inv2): array
    {
        $prompt = "Compare these two investments and recommend which is safer and more profitable:

Investment 1: {$inv1['name']} - {$inv1['amount']} TND, Expected return: {$inv1['return']}%, Duration: {$inv1['duration']} months

Investment 2: {$inv2['name']} - {$inv2['amount']} TND, Expected return: {$inv2['return']}%, Duration: {$inv2['duration']} months

Provide a clear comparison with pros and cons.";

        return $this->chat($prompt);
    }

    public function analyzePortfolio(array $portfolio): array
    {
        $prompt = "Analyze this investment portfolio:

Total Invested: {$portfolio['totalInvested']} TND
Total Gains: {$portfolio['totalGain']} TND
Number of Investments: {$portfolio['investmentsCount']}

Provide: 1. Overall health assessment 2. Risk summary 3. Recommendations";

        return $this->chat($prompt);
    }

    public function shouldInvest(array $investment): array
    {
        $prompt = "Should I invest in this? Analyze and give YES/NO recommendation:

Investment: {$investment['name']}
Amount: {$investment['amount']} TND
Expected Return: {$investment['return']}%
Duration: {$investment['duration']} months";

        return $this->chat($prompt);
    }

    public function explainStrategy(string $strategyType): array
    {
        $strategies = [
            'safe' => 'Safe investment strategy for conservative investors',
            'aggressive' => 'Aggressive high-return investment strategy',
            'balanced' => 'Balanced portfolio strategy',
        ];

        $strategyDesc = $strategies[$strategyType] ?? 'balanced';
        $prompt = "Explain " . $strategyDesc . ". 

Include: 1. What it means 2. Best investment types 3. Typical returns 4. Risks 5. Who should use";

        return $this->chat($prompt);
    }

    private function buildSystemPrompt(array $context): string
    {
        return <<<EOT
You are "CashFly AI Advisor", a professional investment assistant specialized in the Tunisian market.

Your role:
- Help Tunisian investors make informed decisions
- Provide analysis of investment opportunities
- Explain financial concepts in simple terms
- Compare investment options
- Assess risk and return

Guidelines:
- Always consider Tunisian dinar (TND) for local investments
- Factor in local market conditions
- Recommend diversification
- Warn about risks
- Be professional but accessible

If you don't have enough information, ask clarifying questions.
EOT;
    }

    private function callHuggingFace(string $systemPrompt, string $userMessage): string
    {
        // Use text generation format
        $fullPrompt = $systemPrompt . "\n\nUser: " . $userMessage . "\nAssistant:";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => self::HF_API_URL . '/models/' . self::HF_MODEL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(['inputs' => $fullPrompt]),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . self::HF_TOKEN,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 180,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            // Return basic investment advice when API is unavailable
            return $this->getInvestmentAdvice($userMessage);
        }

        $data = json_decode($response, true);
        
        if (isset($data[0]['generated_text'])) {
            return trim(str_replace($fullPrompt, '', $data[0]['generated_text']));
        }

        return $this->getInvestmentAdvice($userMessage);
    }

    private function getInvestmentAdvice(string $userMessage): string
    {
        $lowerMsg = strtolower($userMessage);
        
        if (str_contains($lowerMsg, 'should i invest') || str_contains($lowerMsg, 'devrais-je investir')) {
            return "Voici les facteurs a considerer:\n\n1. **Diversification** - Ne mettez pas tous vos oeufs dans le meme panier\n2. **Horizon temporal** - Investissez pour le long terme (minimum 3-5 ans)\n3. **Tolerance au risque** - Evaluez votre capacite a absorber des pertes\n4. **Rendement vs securite** - Les hauts rendements impliquent toujours plus de risques\n\nPour le marche tunisien, focusez sur les secteurs en croissance comme la technologie et les energies renouvelables.";
        }
        
        if (str_contains($lowerMsg, 'compare') || str_contains($lowerMsg, 'comparer')) {
            return "Pour comparer des investissements:\n\n1. **Rendement attendu** - Le plus eleve n'est pas toujours le meilleur\n2. **Risque** - Verifiez la notation et l'historique\n3. **Liquidite** - pouvez-vous Recuperer votre argent facilement?\n4. **Frais** - Les couts peuvent reduire vos gains\n\nComparez toujours ces facteurs avant de decide.";
        }
        
        if (str_contains($lowerMsg, 'portfolio') || str_contains($lowerMsg, 'portefeuille')) {
            return "Analyse de portefeuille:\n\n**Principes cles:**\n1. Diversification entre secteurs\n2. Balance entre actions et obligations\n3.定期iteRebalancement\n4. Frais minimaux\n\nUn portefeuille sain pour un investisseur tunisien devrait avoir un melange de:\n- Actions locales (40%)\n- Actions etrangeres (30%)\n- Obligations/Treasury (20%)\n- Liquidites (10%)";
        }
        
        return "Conseils generaux d'investissement:\n\n1. **Diversifiez** - Ne concentratez pas tout dans un seul investissement\n2. **Horizon long** - Considerer 5+ ans\n3. **Frais bas** - Minimisez les couts de transaction\n4. **Urgence fund** - Gardez 3-6 mois de depenses en liquidite\n5. **Formation** - Comprenez avant d'investir\n\nPour des conseils personnalises, consultez un conseiller financier certifie.";
    }
}