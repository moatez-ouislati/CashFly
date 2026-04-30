<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controller responsible for displaying the interactive map.
 * Restricted to authenticated users.
 */
#[IsGranted('ROLE_USER')]
class MapController extends AbstractController
{
    /**
     * Renders the map and fetches the companies based on user role.
     * Admin/Investor see all companies, Owners see only their own.
     */
    #[Route('/map', name: 'app_map')]
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $user = $this->getUser();
        $showAll = $this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_INVESTISSEUR');
        
        $entreprises = $entrepriseRepository->findMapEntreprises($user, $showAll);
        
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
