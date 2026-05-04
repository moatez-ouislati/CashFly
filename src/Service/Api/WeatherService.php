<?php

namespace App\Service\Api;

class WeatherService
{
    private const API_KEY = '17b5dff300384b65bfc2acc837425db4';
    private const BASE_URL = 'https://api.openweathermap.org/data/2.5/weather';

    public function getWeatherByCoordinates(float $lat, float $lon): array
    {
        $url = self::BASE_URL . "?lat={$lat}&lon={$lon}&units=metric&lang=fr&appid=" . self::API_KEY;
        return $this->fetchWeather($url);
    }

    public function getWeatherByCity(string $city): array
    {
        $url = self::BASE_URL . "?q=" . urlencode($city) . ",TN&units=metric&lang=fr&appid=" . self::API_KEY;
        return $this->fetchWeather($url);
    }

    private function fetchWeather(string $url): array
    {
        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 || $response === false) {
                return $this->getDefaultWeather();
            }

            $data = json_decode($response, true);
            return $this->formatWeatherData($data);
        } catch (\Exception $e) {
            return $this->getDefaultWeather();
        }
    }

    private function formatWeatherData(array $data): array
    {
        $weather = $data['weather'][0] ?? [];
        $main = $data['main'] ?? [];
        $wind = $data['wind'] ?? [];

        return [
            'city' => $data['name'] ?? 'Tunis',
            'country' => $data['sys']['country'] ?? 'TN',
            'temperature' => round($main['temp'] ?? 0),
            'feels_like' => round($main['feels_like'] ?? 0),
            'humidity' => $main['humidity'] ?? 0,
            'wind_speed' => round($wind['speed'] ?? 0 * 3.6),
            'description' => $weather['description'] ?? 'N/A',
            'icon' => $weather['icon'] ?? '01d',
            'condition' => $this->getWeatherCondition($weather['main'] ?? ''),
        ];
    }

    private function getWeatherCondition(string $main): string
    {
        return match($main) {
            'Clear' => 'Ensoleillé',
            'Clouds' => 'Nuageux',
            'Rain' => 'Pluvieux',
            'Drizzle' => 'Bruineux',
            'Thunderstorm' => 'Orageux',
            'Snow' => 'Neigeux',
            'Mist', 'Fog', 'Haze' => 'Brumeux',
            default => 'Variable',
        };
    }

    private function getDefaultWeather(): array
    {
        return [
            'city' => 'Tunis',
            'country' => 'TN',
            'temperature' => 22,
            'feels_like' => 21,
            'humidity' => 65,
            'wind_speed' => 15,
            'description' => 'Ensoleillé',
            'icon' => '01d',
            'condition' => 'Ensoleillé',
        ];
    }

    public function getWeatherIconUrl(string $icon): string
    {
        return "https://openweathermap.org/img/wn/{$icon}@2x.png";
    }
}
