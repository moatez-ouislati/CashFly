<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsService
{
    private const BASE_URL = 'https://newsapi.org/v2';
    private const API_KEY = '0f0636ae9ee742b38e12c97e3af4e58d';

    public function __construct(
        private HttpClientInterface $httpClient
    ) {}

    public function getNews(string $query, int $limit = 5): array
    {
        if (empty(self::API_KEY)) {
            return $this->getMockNews($query, $limit);
        }

        try {
            $response = $this->httpClient->request('GET', self::BASE_URL . '/everything', [
                'query' => [
                    'q' => $query,
                    'apiKey' => self::API_KEY,
                    'language' => 'fr',
                    'sortBy' => 'publishedAt',
                    'pageSize' => $limit,
                ],
                'timeout' => 5,
            ]);

            $data = $response->toArray();

            if ($data['status'] !== 'ok') {
                return $this->getMockNews($query, $limit);
            }

            return array_map(function ($article) {
                return [
                    'title' => $article['title'] ?? '',
                    'description' => $article['description'] ?? '',
                    'url' => $article['url'] ?? '',
                    'source' => $article['source']['name'] ?? '',
                    'publishedAt' => $article['publishedAt'] ?? '',
                    'image' => $article['urlToImage'] ?? null,
                ];
            }, $data['articles'] ?? []);
        } catch (\Exception $e) {
            return $this->getMockNews($query, $limit);
        }
    }

    private function getMockNews(string $query, int $limit): array
    {
        $mockNews = [
            [
                'title' => 'Bienvenue dans CashFly - ' . $query,
                'description' => 'Votre plateforme de gestion d\'entreprises. Consultez les dernières actualités et tendances du marché.',
                'url' => '#',
                'source' => 'CashFly',
                'publishedAt' => date('Y-m-d\TH:i:s\Z'),
                'image' => null,
            ],
            [
                'title' => 'Analyse du marché pour ' . $query,
                'description' => 'Découvrez les dernières tendances et opportunités dans votre secteur d\'activité.',
                'url' => '#',
                'source' => 'CashFly Insights',
                'publishedAt' => date('Y-m-d\TH:i:s\Z', strtotime('-1 day')),
                'image' => null,
            ],
            [
                'title' => 'Conseils pour gérer votre entreprise',
                'description' => 'Les meilleures pratiques pour optimiser la gestion de votre entreprise et maximiser votre capital.',
                'url' => '#',
                'source' => 'CashFly Tips',
                'publishedAt' => date('Y-m-d\TH:i:s\Z', strtotime('-2 days')),
                'image' => null,
            ],
            [
                'title' => 'Actualités économiques - ' . date('d/m/Y'),
                'description' => 'Restez informé des dernières nouvelles économiques et financières.',
                'url' => '#',
                'source' => 'CashFly News',
                'publishedAt' => date('Y-m-d\TH:i:s\Z', strtotime('-3 days')),
                'image' => null,
            ],
            [
                'title' => 'Guide des meilleures pratiques',
                'description' => 'Comment gérer efficacement une entreprise en 2026: conseils et stratégies.',
                'url' => '#',
                'source' => 'CashFly Guide',
                'publishedAt' => date('Y-m-d\TH:i:s\Z', strtotime('-4 days')),
                'image' => null,
            ],
        ];

        return array_slice($mockNews, 0, $limit);
    }

    public function getNewsByCompany(string $companyName, int $limit = 5): array
    {
        return $this->getNews($companyName, $limit);
    }
}
