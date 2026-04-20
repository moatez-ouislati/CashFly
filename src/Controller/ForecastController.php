<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use App\Service\ForecastService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class ForecastController extends AbstractController
{
    public function __construct(
        private ForecastService $forecastService,
        private EntrepriseRepository $entrepriseRepository
    ) {}

    #[Route('/previsions', name: 'app_admin_forecast', methods: ['GET'])]
    public function index(): Response
    {
        $entrepriseStats = $this->entrepriseRepository->getEntreprisesByMonth(12);
        $capitalStats = $this->entrepriseRepository->getCapitalByMonth(12);
        $sectorStats = $this->entrepriseRepository->getCapitalStatsBySector();
        
        $entrepriseForecast = $this->forecastService->calculateGrowth($entrepriseStats);
        $capitalForecast = $this->forecastService->calculateCapitalGrowth($capitalStats);
        $sectorForecast = $this->forecastService->getSectorForecast($sectorStats);
        
        return $this->render('entreprise/forecast.html.twig', [
            'entreprise_forecast' => $entrepriseForecast,
            'capital_forecast' => $capitalForecast,
            'sector_forecast' => $sectorForecast,
            'capitalStats' => $this->entrepriseRepository->getCapitalStats(),
            'totalEntreprises' => count($this->entrepriseRepository->findAll()),
        ]);
    }
}
