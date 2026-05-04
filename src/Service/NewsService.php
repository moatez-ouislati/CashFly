<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class NewsService
{
    private const BASE_URL = 'https://newsapi.org/v2';
    private string $apiKey;

    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire(env: 'NEWS_API_KEY')] string $apiKey = '',
        private ?LoggerInterface $logger = null
    ) {
        $this->apiKey = $apiKey;
    }

    public function getNews(string $query, int $limit = 5): array
    {
        $this->logger?->info('NewsService: checking API', ['key_status' => !empty($this->apiKey) && $this->apiKey !== 'your_news_api_key' ? 'configured' : 'missing']);
        
        if (empty($this->apiKey) || $this->apiKey === 'your_news_api_key') {
            $this->logger?->info('NewsService: using mock data (no API key)');
            return $this->getEnhancedMockNews($query, $limit);
        }

        try {
            $response = $this->httpClient->request('GET', self::BASE_URL . '/everything', [
                'query' => [
                    'q' => $query,
                    'apiKey' => $this->apiKey,
                    'language' => 'fr',
                    'sortBy' => 'publishedAt',
                    'pageSize' => $limit,
                ],
                'timeout' => 5,
            ]);

            $statusCode = $response->getStatusCode();
            $this->logger?->info('NewsService: API response status', ['status' => $statusCode]);
            
            if ($statusCode !== 200) {
                $this->logger?->warning('NewsService: API returned non-200 status, using mock data');
                return $this->getEnhancedMockNews($query, $limit);
            }

            $data = $response->toArray();

            if (($data['status'] ?? 'error') !== 'ok' || empty($data['articles'])) {
                $this->logger?->warning('NewsService: API error or no articles, using mock data', ['data' => $data]);
                return $this->getEnhancedMockNews($query, $limit);
            }

            $this->logger?->info('NewsService: successfully fetched news', ['count' => count($data['articles'])]);
            
            return array_map(function ($article) {
                return [
                    'title' => $article['title'] ?? '',
                    'description' => $article['description'] ?? '',
                    'url' => $article['url'] ?? '',
                    'source' => $article['source']['name'] ?? '',
                    'publishedAt' => $article['publishedAt'] ?? '',
                    'image' => $article['urlToImage'] ?? null,
                    '_isReal' => true,
                ];
            }, $data['articles'] ?? []);
        } catch (\Exception $e) {
            $this->logger?->error('NewsService: exception', ['error' => $e->getMessage()]);
            return $this->getEnhancedMockNews($query, $limit);
        }
    }

    private function getEnhancedMockNews(string $query, int $limit): array
    {
        $this->logger?->info('NewsService: generating mock news data', ['query' => $query]);
        
        $mockNews = [
            [
                'title' => 'Tendances du marché pour ' . $query . ' en 2026',
                'description' => 'Analyse des dernières évolutions du secteur et perspectives d\'croissance pour les entreprises tunisiennes.',
                'url' => '#',
                'source' => 'CashFly Insights',
                'publishedAt' => date('Y-m-d\TH:i:s\Z'),
                'image' => null,
                '_isReal' => false,
            ],
            [
                'title' => 'Guide de gestion d\'entreprise: ' . $query,
                'description' => 'Les meilleures pratiques pour optimiser la gestion financière et développer votre activité.',
                'url' => '#',
                'source' => 'CashFly Tips',
                'publishedAt' => date('Y-m-d\TH:i:s\Z', strtotime('-1 day')),
                'image' => null,
                '_isReal' => false,
            ],
            [
                'title' => 'Actualités économiques - Maghreb',
                'description' => 'Suivez l\'actualité économique de la région et restez informé des opportunités d\'investissement.',
                'url' => '#',
                'source' => 'CashFly News',
                'publishedAt' => date('Y-m-d\TH:i:s\Z', strtotime('-2 days')),
                'image' => null,
                '_isReal' => false,
            ],
            [
                'title' => 'Stratégies de croissance pour PME en Tunisie',
                'description' => 'Découvrez comment développer votre entreprise avec les conseils d\'experts.',
                'url' => '#',
                'source' => 'CashFly Guide',
                'publishedAt' => date('Y-m-d\TH:i:s\Z', strtotime('-3 days')),
                'image' => null,
                '_isReal' => false,
            ],
            [
                'title' => 'Innovation et entrepreneurship en Afrique du Nord',
                'description' => 'Le paysage entrepreneurial tunisien en pleine évolution: tendances 2026.',
                'url' => '#',
                'source' => 'CashFly Maghreb',
                'publishedAt' => date('Y-m-d\TH:i:s\Z', strtotime('-4 days')),
                'image' => null,
                '_isReal' => false,
            ],
        ];

        return array_slice($mockNews, 0, $limit);
    }

    public function getNewsByCompany(string $companyName, int $limit = 5): array
    {
        $searchQuery = !empty($companyName) ? $companyName : 'entreprise tunisie';
        return $this->getNews($searchQuery, $limit);
    }
}
