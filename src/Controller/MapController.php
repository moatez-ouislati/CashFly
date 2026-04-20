<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MapController extends AbstractController
{
    #[Route('/map', name: 'app_map')]
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprises = $entrepriseRepository->findAllWithCoordinates();
        
        $data = array_map(fn($e) => [
            'id' => $e->getId(),
            'nom' => $e->getNom(),
            'secteur' => $e->getSecteur(),
            'adresse' => $e->getAdresse(),
            'latitude' => $e->getLatitude(),
            'longitude' => $e->getLongitude(),
        ], $entreprises);

        return $this->render('map/index.html.twig', [
            'entreprises' => $data,
        ]);
    }
}
