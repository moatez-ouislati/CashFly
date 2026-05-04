<?php

namespace App\Service\Api;

class WorldBankService
{
    private const BASE_URL = 'https://api.worldbank.org/v2';
    private const COUNTRY = 'TN';

    public function getGdp(string $country = self::COUNTRY, int $perPage = 20): array
    {
        $url = self::BASE_URL . "/country/{$country}/indicator/NY.GDP.MKTP.CD?format=json&per_page={$perPage}";
        return $this->fetchData($url, 'GDP (current USD)');
    }

    public function getInflation(string $country = self::COUNTRY, int $perPage = 20): array
    {
        $url = self::BASE_URL . "/country/{$country}/indicator/FP.CPI.TOTL.ZG?format=json&per_page={$perPage}";
        return $this->fetchData($url, 'Inflation rate (%)');
    }

    public function getEconomicGrowth(string $country = self::COUNTRY, int $perPage = 20): array
    {
        $url = self::BASE_URL . "/country/{$country}/indicator/NY.GDP.MKTP.KD.ZG?format=json&per_page={$perPage}";
        return $this->fetchData($url, 'GDP Growth (%)');
    }

    public function getGdpPerCapita(string $country = self::COUNTRY, int $perPage = 20): array
    {
        $url = self::BASE_URL . "/country/{$country}/indicator/NY.GDP.PCAP.CD?format=json&per_page={$perPage}";
        return $this->fetchData($url, 'GDP per capita (USD)');
    }

    public function getUnemploymentRate(string $country = self::COUNTRY, int $perPage = 20): array
    {
        $url = self::BASE_URL . "/country/{$country}/indicator/SL.UEM.TOTL.ZS?format=json&per_page={$perPage}";
        return $this->fetchData($url, 'Unemployment rate (%)');
    }

    public function getTradeBalance(string $country = self::COUNTRY, int $perPage = 20): array
    {
        $url = self::BASE_URL . "/country/{$country}/indicator/TG.VAL.TOTL.GD.ZS?format=json&per_page={$perPage}";
        return $this->fetchData($url, 'Trade (% of GDP)');
    }

    public function getAllIndicators(string $country = self::COUNTRY): array
    {
        return [
            'gdp' => $this->getGdp($country, 10),
            'inflation' => $this->getInflation($country, 10),
            'growth' => $this->getEconomicGrowth($country, 10),
            'gdpPerCapita' => $this->getGdpPerCapita($country, 10),
            'unemployment' => $this->getUnemploymentRate($country, 10),
        ];
    }

    public function getLatestIndicators(string $country = self::COUNTRY): array
    {
        $indicators = $this->getAllIndicators($country);

        return [
            'gdp' => $this->getLatestValue($indicators['gdp']),
            'inflation' => $this->getLatestValue($indicators['inflation']),
            'growth' => $this->getLatestValue($indicators['growth']),
            'gdpPerCapita' => $this->getLatestValue($indicators['gdpPerCapita']),
            'unemployment' => $this->getLatestValue($indicators['unemployment']),
        ];
    }

    public function getCountryInfo(string $country = self::COUNTRY): array
    {
        $url = self::BASE_URL . "/country/{$country}?format=json";

        try {
            $response = $this->fetchUrl($url);
            $data = json_decode($response, true);

            if (isset($data[1][0])) {
                $countryData = $data[1][0];
                return [
                    'name' => $countryData['name'] ?? 'Tunisie',
                    'capital' => $countryData['capitalCity'] ?? 'Tunis',
                    'region' => $countryData['region']['value'] ?? 'Middle East & North Africa',
                    'income' => $countryData['incomeLevel']['value'] ?? 'Lower middle income',
                    'population' => $countryData['population'] ?? 0,
                ];
            }
        } catch (\Exception $e) {
            // Fallback
        }

        return [
            'name' => 'Tunisie',
            'capital' => 'Tunis',
            'region' => 'Middle East & North Africa',
            'income' => 'Lower middle income',
            'population' => 12000000,
        ];
    }

    private function fetchData(string $url, string $indicatorName): array
    {
        try {
            $response = $this->fetchUrl($url);
            $data = json_decode($response, true);

            if (isset($data[1]) && is_array($data[1])) {
                return $this->formatData($data[1], $indicatorName);
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function formatData(array $data, string $indicatorName): array
    {
        $formatted = [];
        foreach ($data as $item) {
            if (isset($item['value']) && $item['value'] !== null) {
                $formatted[] = [
                    'year' => $item['date'] ?? '',
                    'value' => $item['value'],
                    'valueTnd' => $this->usdToTnd($item['value']),
                    'indicator' => $indicatorName,
                ];
            }
        }
        return $formatted;
    }

    private function usdToTnd(float $usd): float
    {
        return $usd * 3.10;
    }

    private function getLatestValue(array $data): ?array
    {
        if (empty($data)) {
            return null;
        }
        return $data[0];
    }

    private function fetchUrl(string $url): string
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response ?: '';
    }
}
