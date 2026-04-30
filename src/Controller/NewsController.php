<?php

namespace App\Controller;

use App\Service\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/news')]
class NewsController extends AbstractController
{
    public function __construct(
        private NewsService $newsService
    ) {}

    #[Route('/company/{name}', name: 'api_news_company', methods: ['GET'])]
    public function getCompanyNews(string $name): JsonResponse
    {
        $news = $this->newsService->getNewsByCompany(urldecode($name), 5);

        return $this->json([
            'success' => true,
            'company' => $name,
            'news' => $news,
        ]);
    }

    #[Route('/search', name: 'api_news_search', methods: ['GET'])]
    public function searchNews(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '');
        
        if (strlen($query) < 2) {
            return $this->json([
                'success' => false,
                'error' => 'La requête doit contenir au moins 2 caractères',
            ], 400);
        }

        $news = $this->newsService->getNews($query, 10);

        return $this->json([
            'success' => true,
            'news' => $news,
        ]);
    }
}
