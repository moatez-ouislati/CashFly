<?php

namespace App\Controller\Api;

use App\Service\JwtService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/external')]
class ExternalApiController extends BaseApiController
{
    public function __construct(
        private JwtService $jwtService
    ) {}

    #[Route('/exchange-rates', name: 'api_exchange_rates', methods: ['GET'])]
    public function exchangeRates(Request $request): JsonResponse
    {
        $currency = $request->query->get('currency', 'TND');
        
        try {
            $url = "https://open.er-api.com/v6/latest/" . urlencode($currency);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode !== 200 || !$response) {
                return $this->error('Service indisponible', 503);
            }
            
            $data = json_decode($response, true);
            
            if (!isset($data['rates'])) {
                return $this->error('Donnees invalides', 500);
            }
            
            return $this->success([
                'base' => $data['base_code'] ?? $currency,
                'date' => $data['time_last_update_utc'] ?? date('Y-m-d'),
                'rates' => $data['rates'],
            ]);
            
        } catch (\Exception $e) {
            return $this->error('Erreur: ' . $e->getMessage(), 500);
        }
    }

    #[Route('/weather', name: 'api_weather', methods: ['GET'])]
    public function weather(Request $request): JsonResponse
    {
        $lat = $request->query->get('lat', '36.8065');
        $lon = $request->query->get('lon', '10.1815');
        $apiKey = '17b5dff300384b65bfc2acc837425db4';
        
        try {
            $url = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric&lang=fr";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode !== 200 || !$response) {
                return $this->error('Service meteo indisponible', 503);
            }
            
            $data = json_decode($response, true);
            
            return $this->success([
                'city' => $data['name'] ?? 'Unknown',
                'country' => $data['sys']['country'] ?? '',
                'temperature' => $data['main']['temp'] ?? 0,
                'feels_like' => $data['main']['feels_like'] ?? 0,
                'humidity' => $data['main']['humidity'] ?? 0,
                'description' => $data['weather'][0]['description'] ?? '',
                'icon' => $data['weather'][0]['icon'] ?? '',
                'wind_speed' => $data['wind']['speed'] ?? 0,
            ]);
            
        } catch (\Exception $e) {
            return $this->error('Erreur: ' . $e->getMessage(), 500);
        }
    }

    #[Route('/news', name: 'api_news', methods: ['GET'])]
    public function news(Request $request): JsonResponse
    {
        $apiKey = $_ENV['NEWS_API_KEY'] ?? '2a6e167a37374964b203cb3f15093dcc';
        
        try {
            $url = "https://newsapi.org/v2/everything?q=(finance+OR+economie+OR+investissement)+AND+(tunisie+OR+maghreb)&language=fr&sortBy=publishedAt&pageSize=15&apiKey={$apiKey}";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode !== 200 || !$response) {
                return $this->error('Service actualites indisponible', 503);
            }
            
            $data = json_decode($response, true);
            
            if (!isset($data['articles'])) {
                return $this->error('Donnees invalides', 500);
            }
            
            $articles = array_map(function($article) {
                return [
                    'title' => $article['title'] ?? '',
                    'description' => $article['description'] ?? '',
                    'url' => $article['url'] ?? '',
                    'image' => $article['urlToImage'] ?? null,
                    'source' => $article['source']['name'] ?? '',
                    'published_at' => $article['publishedAt'] ?? '',
                ];
            }, $data['articles']);
            
            return $this->success([
                'total' => $data['totalResults'] ?? 0,
                'articles' => $articles,
            ]);
            
        } catch (\Exception $e) {
            return $this->error('Erreur: ' . $e->getMessage(), 500);
        }
    }

    #[Route('/ai/recommendations', name: 'api_ai_recommendations', methods: ['POST'])]
    public function aiRecommendations(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userBudget = $data['budget'] ?? 0;
        $userExperience = $data['experience'] ?? '';
        $riskLevel = $data['risk_level'] ?? 'medium';
        
        $apiKey = $_ENV['OPENAI_API_KEY'] ?? '';
        $openrouterKey = $_ENV['OPENROUTER_API_KEY'] ?? '';
        
        try {
            $messages = [
                [
                    'role' => 'system',
                    'content' => 'Tu es un conseiller financier expert en investissements en Tunisie. Donne des recommandations personalisees basées sur le profil de l\'investisseur.'
                ],
                [
                    'role' => 'user',
                    'content' => "Donne-moi des recommandations d'investissement pour un profil avec:\n" .
                                 "- Budget: {$userBudget} TND\n" .
                                 "- Experience: {$userExperience}\n" .
                                 "- Niveau de risque: {$riskLevel}\n" .
                                 "Reponds en JSON avec un tableau de recommandations."
                ]
            ];
            
            $body = [
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages,
                'temperature' => 0.7,
            ];
            
            $headers = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ];
            
            if (empty($apiKey) && !empty($openrouterKey)) {
                $body['model'] = 'openai/gpt-3.5-turbo';
                $headers = [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $openrouterKey,
                ];
            }
            
            if (empty($apiKey) && empty($openrouterKey)) {
                return $this->success([
                    'recommendations' => $this->getDefaultRecommendations($userBudget, $riskLevel),
                    'source' => 'default',
                ]);
            }
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, empty($apiKey) ? 'https://openrouter.ai/api/v1/chat/completions' : 'https://api.openai.com/v1/chat/completions');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            
            $result = json_decode($response, true);
            
            if (isset($result['choices'][0]['message']['content'])) {
                $content = $result['choices'][0]['message']['content'];
                
                preg_match('/\[.*\]/s', $content, $matches);
                
                if (!empty($matches)) {
                    $recommendations = json_decode($matches[0], true);
                    if (is_array($recommendations)) {
                        return $this->success([
                            'recommendations' => $recommendations,
                            'source' => 'ai',
                        ]);
                    }
                }
                
                return $this->success([
                    'recommendations' => [['text' => $content]],
                    'source' => 'ai',
                ]);
            }
            
            return $this->success([
                'recommendations' => $this->getDefaultRecommendations($userBudget, $riskLevel),
                'source' => 'default',
            ]);
            
        } catch (\Exception $e) {
            return $this->success([
                'recommendations' => $this->getDefaultRecommendations($userBudget, $riskLevel),
                'source' => 'default',
            ]);
        }
    }

    #[Route('/translate', name: 'api_translate', methods: ['GET'])]
    public function translate(Request $request): JsonResponse
    {
        $text = $request->query->get('text', '');
        $targetLang = $request->query->get('lang', 'en');
        
        if (empty($text)) {
            return $this->validationError(['text' => 'Texte requis']);
        }
        
        try {
            $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=" . urlencode($targetLang) . "&dt=t&q=" . urlencode($text);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            
            $data = json_decode($response, true);
            
            if (isset($data[0][0][0])) {
                return $this->success([
                    'original' => $text,
                    'translated' => $data[0][0][0],
                    'source_lang' => $data[2] ?? 'auto',
                    'target_lang' => $targetLang,
                ]);
            }
            
            return $this->error('Translation failed');
            
        } catch (\Exception $e) {
            return $this->error('Erreur: ' . $e->getMessage(), 500);
        }
    }

    private function getDefaultRecommendations(float $budget, string $riskLevel): array
    {
        $recommendations = [];
        
        if ($budget >= 50000) {
            $recommendations[] = [
                'type' => 'immobilier',
                'title' => 'Investissement Immobilier',
                'description' => 'Considerez l\'investissement dans l\'immobilier commercial ou residentiel en Tunisie.',
                'montant_suggere' => ($budget * 0.4),
                'rendement_estime' => '8-12%',
                'risque' => 'moyen',
            ];
        }
        
        $recommendations[] = [
            'type' => 'boursier',
            'title' => 'Actions et Obligations',
            'description' => 'Diversifiez avec des actions de societes tunisiennes cotées.',
            'montant_suggere' => ($budget * 0.3),
            'rendement_estime' => '5-15%',
            'risque' => $riskLevel === 'high' ? 'eleve' : 'moyen',
        ];
        
        $recommendations[] = [
            'type' => 'startup',
            'title' => 'Startup et Innovation',
            'description' => 'Participez au financement de startups prometteuses via des plateformes dediees.',
            'montant_suggere' => ($budget * 0.1),
            'rendement_estime' => '10-30%',
            'risque' => 'eleve',
        ];
        
        $recommendations[] = [
            'type' => 'epargne',
            'title' => 'Epargne et Depots',
            'description' => 'Maintenez une reserve de securite dans des comptes d\'epargne.',
            'montant_suggere' => ($budget * 0.2),
            'rendement_estime' => '3-5%',
            'risque' => 'faible',
        ];
        
        return $recommendations;
    }
}
