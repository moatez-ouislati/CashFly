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
        $user = $this->getUser();
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $filterProprietaire = $isAdmin ? null : $user;

        $entrepriseStats = $this->entrepriseRepository->getEntreprisesByMonth(12, $filterProprietaire);
        $capitalStatsByMonth = $this->entrepriseRepository->getCapitalByMonth(12, $filterProprietaire);
        $sectorStats = $this->entrepriseRepository->getCapitalStatsBySector($filterProprietaire);
        
        $entrepriseForecast = $this->forecastService->calculateGrowth($entrepriseStats);
        $capitalForecast = $this->forecastService->calculateCapitalGrowth($capitalStatsByMonth);
        $sectorForecast = $this->forecastService->getSectorForecast($sectorStats);

        $entreprises = $isAdmin 
            ? $this->entrepriseRepository->findAll() 
            : $this->entrepriseRepository->findBy(['proprietaire' => $user]);
        
        $capitalStats = $this->entrepriseRepository->getCapitalStats($filterProprietaire);
        
        return $this->render('entreprise/forecast.html.twig', [
            'entreprise_forecast' => $entrepriseForecast,
            'capital_forecast' => $capitalForecast,
            'sector_forecast' => $sectorForecast,
            'capitalStats' => $capitalStats,
            'totalEntreprises' => (int) $capitalStats['total_entreprises'],
            'entreprises' => $entreprises,
        ]);
    }
}
