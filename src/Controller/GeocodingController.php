<?php

namespace App\Controller;

use App\Service\NominatimService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/geocoding')]
class GeocodingController extends AbstractController
{
    public function __construct(
        private NominatimService $nominatimService
    ) {}

    #[Route('/geocode', name: 'api_geocode', methods: ['POST'])]
    public function geocode(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $address = $data['address'] ?? '';
        $city = $data['city'] ?? '';
        $country = $data['country'] ?? '';

        if (empty($address)) {
            return $this->json([
                'success' => false,
                'error' => 'L\'adresse est requise',
            ], 400);
        }

        $result = $this->nominatimService->geocode($address, $city, $country);

        if (!$result) {
            return $this->json([
                'success' => false,
                'error' => 'Aucune localisation trouvée pour cette adresse',
            ], 404);
        }

        return $this->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    #[Route('/reverse', name: 'api_reverse_geocode', methods: ['POST'])]
    public function reverseGeocode(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $latitude = $data['latitude'] ?? null;
        $longitude = $data['longitude'] ?? null;

        if ($latitude === null || $longitude === null) {
            return $this->json([
                'success' => false,
                'error' => 'Latitude et longitude sont requises',
            ], 400);
        }

        if (!is_numeric($latitude) || !is_numeric($longitude)) {
            return $this->json([
                'success' => false,
                'error' => 'Latitude et longitude doivent être des nombres',
            ], 400);
        }

        $result = $this->nominatimService->reverseGeocode((float) $latitude, (float) $longitude);

        if (!$result) {
            return $this->json([
                'success' => false,
                'error' => 'Aucune adresse trouvée pour ces coordonnées',
            ], 404);
        }

        return $this->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    #[Route('/search', name: 'api_search_locations', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '');
        $limit = (int) $request->query->get('limit', 5);

        if (empty($query) || strlen($query) < 3) {
            return $this->json([
                'success' => false,
                'error' => 'La requête doit contenir au moins 3 caractères',
            ], 400);
        }

        $results = $this->nominatimService->searchLocations($query, min($limit, 10));

        return $this->json([
            'success' => true,
            'data' => $results,
        ]);
    }
}
