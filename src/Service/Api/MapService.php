<?php

namespace App\Service\Api;

class MapService
{
    private const MAPBOX_TOKEN = 'pk.eyJ1IjoibWVkZGVic291aGlyIiwiYSI6ImNtbWFmNTFwbjBjZjQycHM5aGJ6eWZwM2EifQ.KPD4BbIrpnNnio0CI9k03Q';
    private const NOMINATIM_URL = 'https://nominatim.openstreetmap.org/search';

    public function getMapboxToken(): string
    {
        return self::MAPBOX_TOKEN;
    }

    public function geocodeAddress(string $address): ?array
    {
        $url = self::NOMINATIM_URL . '?' . http_build_query([
            'q' => $address . ', Tunisia',
            'format' => 'json',
            'limit' => 1,
        ]);

        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTPHEADER => [
                    'User-Agent: CashFlyApp/1.0',
                ],
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data[0])) {
                return [
                    'lat' => (float) $data[0]['lat'],
                    'lon' => (float) $data[0]['lon'],
                    'display_name' => $data[0]['display_name'] ?? $address,
                ];
            }

            return $this->getTunisCoordinates();
        } catch (\Exception $e) {
            return $this->getTunisCoordinates();
        }
    }

    public function getDefaultCoordinates(): array
    {
        return $this->getTunisCoordinates();
    }

    private function getTunisCoordinates(): array
    {
        return [
            'lat' => 36.8065,
            'lon' => 10.1815,
            'display_name' => 'Tunis, Tunisia',
        ];
    }

    public function getStaticMapUrl(float $lat, float $lon, int $zoom = 13, int $width = 600, int $height = 400): string
    {
        return "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/pin-s+ff6b35({$lon},{$lat})/{$lon},{$lat},{$zoom},0/{$width}x{$height}?access_token=" . self::MAPBOX_TOKEN;
    }

    public function getMapboxStyleUrl(): string
    {
        return 'mapbox://styles/mapbox/streets-v11';
    }
}
