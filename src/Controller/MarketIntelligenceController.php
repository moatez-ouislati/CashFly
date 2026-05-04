<?php

namespace App\Controller;

use App\Service\NewsService;
use App\Service\CurrencyConverterService;
use App\Service\AiInvestmentAdvisor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/investisseur/market')]
#[IsGranted('ROLE_INVESTISSEUR')]
class MarketIntelligenceController extends AbstractController
{
    public function __construct(
        private NewsService $newsService,
        private CurrencyConverterService $currencyService,
        private AiInvestmentAdvisor $aiAdvisor
    ) {}

    #[Route('/', name: 'investisseur_market_intel')]
    public function index(): Response
    {
        // Get general financial news
        $news = $this->newsService->getNews('finance mondiale', 6);
        
        // Get some exchange rates
        $rates = [
            'EUR' => $this->currencyService->getTaux('EUR'),
            'USD' => $this->currencyService->getTaux('USD'),
            'GBP' => $this->currencyService->getTaux('GBP'),
        ];

        // Mock market trends (as Alpha Vantage OHLCV would be complex to display without a chart lib here)
        $trends = [
            ['name' => 'Tunindex', 'value' => '8 945.20', 'change' => '+0.45%', 'status' => 'up'],
            ['name' => 'S&P 500', 'value' => '5 204.34', 'change' => '-0.12%', 'status' => 'down'],
            ['name' => 'Nasdaq', 'value' => '16 384.47', 'change' => '+0.32%', 'status' => 'up'],
        ];

        return $this->render('investisseur/market_intel.html.twig', [
            'news' => $news,
            'rates' => $rates,
            'trends' => $trends,
        ]);
    }
}
