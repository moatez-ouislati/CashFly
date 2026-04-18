<?php

namespace App\Controller;

use App\Service\Api\NewsService;
use App\Service\Api\ExchangeRateService;
use App\Service\Api\AiRecommendationService;
use App\Service\Api\AlphaVantageService;
use App\Service\Api\WorldBankService;
use App\Repository\InvestissementRepository;
use App\Repository\UtilisateurRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    public function __construct(
        private NewsService $newsService,
        private ExchangeRateService $exchangeRateService,
        private AiRecommendationService $aiService,
        private AlphaVantageService $alphaVantageService,
        private WorldBankService $worldBankService,
        private InvestissementRepository $investissementRepository,
        private UtilisateurRepository $utilisateurRepository
    ) {}

    #[Route('/actualites', name: 'app_api_news')]
    public function news(): Response
    {
        $user = $this->utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        $news = $this->newsService->getFinancialNews(20);

        return $this->render('api/news.html.twig', [
            'news' => $news,
            'user' => $user,
        ]);
    }

    #[Route('/taux-de-change', name: 'app_api_exchange_rates')]
    public function exchangeRates(): Response
    {
        $user = $this->utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        $rates = $this->exchangeRateService->getExchangeRates();

        return $this->render('api/exchange_rates.html.twig', [
            'rates' => $rates,
            'user' => $user,
        ]);
    }

    #[Route('/recommandations-ia', name: 'app_api_recommendations')]
    public function recommendations(): Response
    {
        $user = $this->utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        $portfolioData = $this->getPortfolioData();
        $recommendations = $this->aiService->getRecommendations($portfolioData);

        return $this->render('api/recommendations.html.twig', [
            'recommendations' => $recommendations,
            'portfolioData' => $portfolioData,
            'user' => $user,
        ]);
    }

    #[Route('/recommandations-ia/download-pdf', name: 'app_api_recommendations_pdf')]
    public function downloadPdf(): Response
    {
        $user = $this->utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        $portfolioData = $this->getPortfolioData();
        $recommendations = $this->aiService->getRecommendations($portfolioData);

        $html = $this->renderView('api/recommendations_pdf.html.twig', [
            'recommendations' => $recommendations,
            'portfolioData' => $portfolioData,
            'user' => $user,
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="rapport-recommandations-ia-cashfly.pdf"');

        return $response;
    }

    #[Route('/marches-boursiers', name: 'app_api_stocks')]
    public function stocks(Request $request): Response
    {
        $user = $this->utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        
        $symbol = $request->query->get('symbol', 'IBM');
        $quotes = $this->alphaVantageService->getGlobalQuote($symbol);
        $popularStocks = $this->alphaVantageService->getPopularStocks();

        return $this->render('api/stocks.html.twig', [
            'symbol' => strtoupper($symbol),
            'quotes' => $quotes,
            'popularStocks' => $popularStocks,
            'user' => $user,
        ]);
    }

    #[Route('/recherche-action', name: 'app_api_stock_search')]
    public function stockSearch(Request $request): Response
    {
        $user = $this->utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        $keyword = $request->query->get('keyword', '');
        $results = [];
        $symbol = 'IBM';
        $quotes = [];

        if ($keyword) {
            $results = $this->alphaVantageService->searchSymbol($keyword);
        } else {
            $quotes = $this->alphaVantageService->getGlobalQuote('IBM');
        }

        return $this->render('api/stock_search.html.twig', [
            'keyword' => $keyword,
            'results' => $results,
            'symbol' => $symbol,
            'quotes' => $quotes,
            'user' => $user,
        ]);
    }

    #[Route('/api/stock-quote', name: 'app_api_stock_quote', methods: ['GET'])]
    public function stockQuote(Request $request): Response
    {
        $symbol = $request->query->get('symbol', 'IBM');
        $quotes = $this->alphaVantageService->getGlobalQuote($symbol);

        return new Response(json_encode($quotes), 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    #[Route('/statut-marches', name: 'app_api_market_status')]
    public function marketStatus(): Response
    {
        $user = $this->utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        $marketData = $this->alphaVantageService->getMarketStatus();

        return $this->render('api/market_status.html.twig', [
            'marketData' => $marketData,
            'user' => $user,
        ]);
    }

    #[Route('/indicateurs-economiques', name: 'app_api_economic')]
    public function economicIndicators(): Response
    {
        $user = $this->utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        $countryInfo = $this->worldBankService->getCountryInfo('TN');
        $latestIndicators = $this->worldBankService->getLatestIndicators('TN');
        $allIndicators = $this->worldBankService->getAllIndicators('TN');

        return $this->render('api/economic_indicators.html.twig', [
            'countryInfo' => $countryInfo,
            'latestIndicators' => $latestIndicators,
            'allIndicators' => $allIndicators,
            'user' => $user,
        ]);
    }

    private function getPortfolioData(): array
    {
        $investissements = $this->investissementRepository->findAll();

        $totalInvested = 0;
        $totalGain = 0;
        $sectors = [];

        foreach ($investissements as $inv) {
            $totalInvested += (float) $inv->getMontant();
            if ($inv->getTauxRendementPrevu()) {
                $totalGain += (float) $inv->getMontant() * ((float) $inv->getTauxRendementPrevu() / 100);
            }
            if ($inv->getEntreprise() && $inv->getEntreprise()->getSecteur()) {
                $sectors[] = $inv->getEntreprise()->getSecteur();
            }
        }

        return [
            'totalInvested' => $totalInvested,
            'totalGain' => $totalGain,
            'investmentsCount' => count($investissements),
            'topSectors' => array_unique($sectors),
        ];
    }
}
