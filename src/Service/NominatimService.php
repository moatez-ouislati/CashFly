<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class NominatimService
{
    private const BASE_URL = 'https://nominatim.openstreetmap.org';
    private const USER_AGENT = 'CashFlyApp/1.0';

    public function __construct(
        private HttpClientInterface $httpClient
    ) {}

    public function geocode(string $address, string $city = '', string $country = ''): ?array
    {
        $query = trim($address . ' ' . $city . ' ' . $country);
        
        try {
            $response = $this->httpClient->request('GET', self::BASE_URL . '/search', [
                'query' => [
                    'q' => $query,
                    'format' => 'json',
                    'limit' => 1,
                    'addressdetails' => 1,
                ],
                'headers' => [
                    'User-Agent' => self::USER_AGENT,
                ],
            ]);

            $data = $response->toArray();

            if (empty($data)) {
                return null;
            }

            return [
                'latitude' => (float) $data[0]['lat'],
                'longitude' => (float) $data[0]['lon'],
                'display_name' => $data[0]['display_name'] ?? null,
                'type' => $data[0]['type'] ?? null,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    public function reverseGeocode(float $latitude, float $longitude): ?array
    {
        try {
            $response = $this->httpClient->request('GET', self::BASE_URL . '/reverse', [
                'query' => [
                    'lat' => $latitude,
                    'lon' => $longitude,
                    'format' => 'json',
                    'addressdetails' => 1,
                ],
                'headers' => [
                    'User-Agent' => self::USER_AGENT,
                ],
            ]);

            $data = $response->toArray();

            if (empty($data) || isset($data['error'])) {
                return null;
            }

            return [
                'display_name' => $data['display_name'] ?? null,
                'road' => $data['address']['road'] ?? null,
                'city' => $data['address']['city'] ?? $data['address']['town'] ?? $data['address']['village'] ?? null,
                'state' => $data['address']['state'] ?? null,
                'country' => $data['address']['country'] ?? null,
                'postcode' => $data['address']['postcode'] ?? null,
                'country_code' => $data['address']['country_code'] ?? null,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    public function searchLocations(string $query, int $limit = 5): array
    {
        try {
            $response = $this->httpClient->request('GET', self::BASE_URL . '/search', [
                'query' => [
                    'q' => $query,
                    'format' => 'json',
                    'limit' => $limit,
                    'addressdetails' => 1,
                ],
                'headers' => [
                    'User-Agent' => self::USER_AGENT,
                ],
            ]);

            $data = $response->toArray();

            return array_map(function ($item) {
                return [
                    'latitude' => (float) $item['lat'],
                    'longitude' => (float) $item['lon'],
                    'display_name' => $item['display_name'] ?? null,
                    'type' => $item['type'] ?? null,
                ];
            }, $data);
        } catch (\Exception $e) {
            return [];
        }
    }
}
