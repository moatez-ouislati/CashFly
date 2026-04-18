<?php

namespace App\Service\Api;

class ExchangeRateService
{
    private const API_URL = 'https://open.er-api.com/v6/latest/TND';

    public function getExchangeRates(): array
    {
        try {
            $response = $this->fetchUrl(self::API_URL);
            $data = json_decode($response, true);

            if (isset($data['rates']) && $data['rates']) {
                return $this->formatRates($data['rates']);
            }

            return $this->getFallbackRates();
        } catch (\Exception $e) {
            return $this->getFallbackRates();
        }
    }

    private function formatRates(array $rates): array
    {
        $relevantCurrencies = ['EUR', 'USD', 'GBP', 'MAD', 'DZD', 'LYD', 'SAR', 'QAR', 'AED'];

        $formatted = [];
        foreach ($relevantCurrencies as $currency) {
            if (isset($rates[$currency])) {
                $formatted[$currency] = [
                    'currency' => $currency,
                    'rate' => round($rates[$currency], 4),
                    'name' => $this->getCurrencyName($currency),
                    'symbol' => $this->getCurrencySymbol($currency),
                    'change_24h' => $this->simulateChange(),
                ];
            }
        }

        return $formatted;
    }

    private function getFallbackRates(): array
    {
        return [
            'EUR' => ['currency' => 'EUR', 'rate' => 3.32, 'name' => 'Euro', 'symbol' => '€', 'change_24h' => 0.12],
            'USD' => ['currency' => 'USD', 'rate' => 3.08, 'name' => 'Dollar US', 'symbol' => '$', 'change_24h' => -0.08],
            'GBP' => ['currency' => 'GBP', 'rate' => 3.85, 'name' => 'Livre Sterling', 'symbol' => '£', 'change_24h' => 0.05],
            'MAD' => ['currency' => 'MAD', 'rate' => 0.31, 'name' => 'Dirham Marocain', 'symbol' => 'MAD', 'change_24h' => 0.02],
            'DZD' => ['currency' => 'DZD', 'rate' => 0.023, 'name' => 'Dinar Algérien', 'symbol' => 'DA', 'change_24h' => -0.15],
            'LYD' => ['currency' => 'LYD', 'rate' => 0.63, 'name' => 'Dinar Libyen', 'symbol' => 'LD', 'change_24h' => 0.08],
            'SAR' => ['currency' => 'SAR', 'rate' => 0.82, 'name' => 'Riyal Saoudien', 'symbol' => 'SAR', 'change_24h' => 0.01],
            'QAR' => ['currency' => 'QAR', 'rate' => 0.85, 'name' => 'Riyal Qatar', 'symbol' => 'QR', 'change_24h' => 0.00],
            'AED' => ['currency' => 'AED', 'rate' => 0.84, 'name' => 'Dirham UAE', 'symbol' => 'AED', 'change_24h' => 0.03],
        ];
    }

    private function getCurrencyName(string $code): string
    {
        return match($code) {
            'EUR' => 'Euro',
            'USD' => 'Dollar US',
            'GBP' => 'Livre Sterling',
            'MAD' => 'Dirham Marocain',
            'DZD' => 'Dinar Algérien',
            'LYD' => 'Dinar Libyen',
            'SAR' => 'Riyal Saoudien',
            'QAR' => 'Riyal Qatar',
            'AED' => 'Dirham UAE',
            default => $code,
        };
    }

    private function getCurrencySymbol(string $code): string
    {
        return match($code) {
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
            'MAD', 'DZD', 'LYD', 'SAR', 'QAR', 'AED' => $code,
            default => $code,
        };
    }

    private function simulateChange(): float
    {
        return round((mt_rand(-50, 50) / 100), 2);
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

        return $response ?: throw new \Exception('Failed to fetch exchange rates');
    }
}
