<?php

namespace App\Service\Api;

class AlphaVantageService
{
    private const BASE_URL = 'https://www.alphavantage.co/query';
    private const API_KEY = 'BFUC0I1ZFIQ59LIP';

    public function getTimeSeriesIntraday(string $symbol, string $interval = '5min'): array
    {
        $url = self::BASE_URL . '?' . http_build_query([
            'function' => 'TIME_SERIES_INTRADAY',
            'symbol' => $symbol,
            'interval' => $interval,
            'apikey' => self::API_KEY,
        ]);

        return $this->fetchData($url);
    }

    public function getTimeSeriesDaily(string $symbol): array
    {
        $url = self::BASE_URL . '?' . http_build_query([
            'function' => 'TIME_SERIES_DAILY',
            'symbol' => $symbol,
            'apikey' => self::API_KEY,
        ]);

        return $this->fetchData($url);
    }

    public function getGlobalQuote(string $symbol): array
    {
        $url = self::BASE_URL . '?' . http_build_query([
            'function' => 'GLOBAL_QUOTE',
            'symbol' => $symbol,
            'apikey' => self::API_KEY,
        ]);

        $data = $this->fetchData($url);
        
        if (isset($data['Global Quote'])) {
            return $this->formatGlobalQuote($data['Global Quote']);
        }
        
        return $this->getDefaultQuote($symbol);
    }

    public function getForexRates(string $fromCurrency, string $toCurrency = 'USD'): array
    {
        $url = self::BASE_URL . '?' . http_build_query([
            'function' => 'CURRENCY_EXCHANGE_RATE',
            'from_currency' => $fromCurrency,
            'to_currency' => $toCurrency,
            'apikey' => self::API_KEY,
        ]);

        $data = $this->fetchData($url);
        
        if (isset($data['Realtime Currency Exchange Rate'])) {
            return $this->formatForexRate($data['Realtime Currency Exchange Rate']);
        }
        
        return $this->getDefaultForexRate($fromCurrency, $toCurrency);
    }

    public function searchSymbol(string $keywords): array
    {
        $url = self::BASE_URL . '?' . http_build_query([
            'function' => 'SYMBOL_SEARCH',
            'keywords' => $keywords,
            'apikey' => self::API_KEY,
        ]);

        $data = $this->fetchData($url);
        
        if (isset($data['bestMatches'])) {
            return $this->formatSearchResults($data['bestMatches']);
        }
        
        return [];
    }

    public function getCryptoRatings(string $symbol): array
    {
        $url = self::BASE_URL . '?' . http_build_query([
            'function' => 'CRYPTO_RATING',
            'symbol' => $symbol,
            'apikey' => self::API_KEY,
        ]);

        return $this->fetchData($url);
    }

    public function getMarketStatus(): array
    {
        $url = self::BASE_URL . '?' . http_build_query([
            'function' => 'MARKET_STATUS',
            'apikey' => self::API_KEY,
        ]);

        $data = $this->fetchData($url);

        if (isset($data['market_status'])) {
            return $this->formatMarketStatus($data);
        }

        return $this->getDefaultMarketStatus();
    }

    private function formatMarketStatus(array $data): array
    {
        $markets = [];
        $marketStatuses = $data['markets'] ?? [];

        foreach ($marketStatuses as $market) {
            $markets[] = [
                'name' => $market['name'] ?? '',
                'acronym' => $market['acronym'] ?? '',
                'type' => $market['type'] ?? '',
                'status' => $market['current_status'] ?? 'unknown',
                'open_time' => $market['open_time'] ?? '',
                'close_time' => $market['close_time'] ?? '',
                'notes' => $market['notes'] ?? '',
            ];
        }

        return [
            'market_status' => $data['market_status'] ?? 'Unknown',
            'current_time' => $data['current_time'] ?? '',
            'markets' => $markets,
        ];
    }

    private function getDefaultMarketStatus(): array
    {
        $tunisiaStatus = $this->getTunisiaMarketStatus();

        return [
            'market_status' => 'Unknown',
            'current_time' => date('Y-m-d H:i:s'),
            'markets' => [
                [
                    'name' => 'Bourse de Tunis (BVMT)',
                    'acronym' => 'BVMT',
                    'type' => 'Equity',
                    'status' => $tunisiaStatus,
                    'open_time' => '08:00',
                    'close_time' => '13:00',
                    'notes' => 'Heure de Tunis (GMT+1)',
                    'country' => 'Tunisie',
                    'flag' => 'tn',
                ],
                [
                    'name' => 'New York Stock Exchange',
                    'acronym' => 'NYSE',
                    'type' => 'Equity',
                    'status' => $this->getUsMarketStatus(),
                    'open_time' => '09:30',
                    'close_time' => '16:00',
                    'notes' => 'Eastern Time (ET)',
                    'country' => 'Etats-Unis',
                    'flag' => 'us',
                ],
                [
                    'name' => 'NASDAQ',
                    'acronym' => 'NASDAQ',
                    'type' => 'Equity',
                    'status' => $this->getUsMarketStatus(),
                    'open_time' => '09:30',
                    'close_time' => '16:00',
                    'notes' => 'Eastern Time (ET)',
                    'country' => 'Etats-Unis',
                    'flag' => 'us',
                ],
                [
                    'name' => 'London Stock Exchange',
                    'acronym' => 'LSE',
                    'type' => 'Equity',
                    'status' => $this->getUkMarketStatus(),
                    'open_time' => '08:00',
                    'close_time' => '16:30',
                    'notes' => 'Greenwich Mean Time (GMT)',
                    'country' => 'Royaume-Uni',
                    'flag' => 'gb',
                ],
                [
                    'name' => 'Euronext Paris',
                    'acronym' => 'EPA',
                    'type' => 'Equity',
                    'status' => $this->getEuMarketStatus(),
                    'open_time' => '09:00',
                    'close_time' => '17:30',
                    'notes' => 'Central European Time (CET)',
                    'country' => 'France',
                    'flag' => 'fr',
                ],
                [
                    'name' => 'Deutsche Borse (Xetra)',
                    'acronym' => 'XETRA',
                    'type' => 'Equity',
                    'status' => $this->getEuMarketStatus(),
                    'open_time' => '09:00',
                    'close_time' => '17:30',
                    'notes' => 'Central European Time (CET)',
                    'country' => 'Allemagne',
                    'flag' => 'de',
                ],
            ],
        ];
    }

    private function getTunisiaMarketStatus(): string
    {
        $now = new \DateTime('now', new \DateTimeZone('Africa/Tunis'));
        $day = (int)$now->format('N');
        $hour = (int)$now->format('H');
        $minute = (int)$now->format('i');

        if ($day >= 6) {
            return 'closed';
        }

        $currentMinutes = $hour * 60 + $minute;
        $openMinutes = 8 * 60;
        $closeMinutes = 13 * 60;

        if ($currentMinutes >= $openMinutes && $currentMinutes < $closeMinutes) {
            return 'open';
        }

        return 'closed';
    }

    private function getUsMarketStatus(): string
    {
        $now = new \DateTime('now', new \DateTimeZone('America/New_York'));
        $day = (int)$now->format('N');
        $hour = (int)$now->format('H');
        $minute = (int)$now->format('i');

        if ($day >= 6) {
            return 'closed';
        }

        $currentMinutes = $hour * 60 + $minute;
        $openMinutes = 9 * 60 + 30;
        $closeMinutes = 16 * 60;

        if ($currentMinutes >= $openMinutes && $currentMinutes < $closeMinutes) {
            return 'open';
        }

        if ($currentMinutes >= $openMinutes - 30 && $currentMinutes < $openMinutes) {
            return 'pre';
        }

        if ($currentMinutes >= $closeMinutes && $currentMinutes < 20 * 60) {
            return 'after';
        }

        return 'closed';
    }

    private function getUkMarketStatus(): string
    {
        $now = new \DateTime('now', new \DateTimeZone('Europe/London'));
        $day = (int)$now->format('N');
        $hour = (int)$now->format('H');
        $minute = (int)$now->format('i');

        if ($day >= 6) {
            return 'closed';
        }

        $currentMinutes = $hour * 60 + $minute;
        $openMinutes = 8 * 60;
        $closeMinutes = 16 * 60 + 30;

        if ($currentMinutes >= $openMinutes && $currentMinutes < $closeMinutes) {
            return 'open';
        }

        return 'closed';
    }

    private function getEuMarketStatus(): string
    {
        $now = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $day = (int)$now->format('N');
        $hour = (int)$now->format('H');
        $minute = (int)$now->format('i');

        if ($day >= 6) {
            return 'closed';
        }

        $currentMinutes = $hour * 60 + $minute;
        $openMinutes = 9 * 60;
        $closeMinutes = 17 * 60 + 30;

        if ($currentMinutes >= $openMinutes && $currentMinutes < $closeMinutes) {
            return 'open';
        }

        return 'closed';
    }

    public function getGlobalMarketOverview(): array
    {
        $usMarket = $this->getGlobalQuote('SPY');
        $europeMarket = $this->getGlobalQuote('VGK');

        return [
            'us_market' => $usMarket,
            'europe_market' => $europeMarket,
            'market_status' => $this->getMarketStatus(),
        ];
    }

    public function getPopularStocks(): array
    {
        return [
            ['symbol' => 'IBM', 'name' => 'International Business Machines'],
            ['symbol' => 'AAPL', 'name' => 'Apple Inc.'],
            ['symbol' => 'GOOGL', 'name' => 'Alphabet Inc.'],
            ['symbol' => 'MSFT', 'name' => 'Microsoft Corporation'],
            ['symbol' => 'AMZN', 'name' => 'Amazon.com Inc.'],
            ['symbol' => 'TSLA', 'name' => 'Tesla Inc.'],
            ['symbol' => 'META', 'name' => 'Meta Platforms Inc.'],
            ['symbol' => 'NVDA', 'name' => 'NVIDIA Corporation'],
        ];
    }

    private function fetchData(string $url): array
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

            if ($httpCode !== 200 || !$response) {
                return [];
            }

            $data = json_decode($response, true);
            
            if (isset($data['Note']) || isset($data['Information'])) {
                return [];
            }

            return $data ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function formatGlobalQuote(array $quote): array
    {
        return [
            'symbol' => $quote['01. symbol'] ?? '',
            'price' => $quote['05. price'] ?? 0,
            'change' => $quote['09. change'] ?? 0,
            'change_percent' => $quote['10. change percent'] ?? '0%',
            'high' => $quote['03. high'] ?? 0,
            'low' => $quote['04. low'] ?? 0,
            'volume' => $quote['06. volume'] ?? 0,
            'latest_day' => $quote['07. latest trading day'] ?? '',
            'open' => $quote['02. open'] ?? 0,
            'previous_close' => $quote['08. previous close'] ?? 0,
        ];
    }

    private function formatForexRate(array $rate): array
    {
        return [
            'from_currency' => $rate['1. From_Currency Code'] ?? '',
            'to_currency' => $rate['3. To_Currency Code'] ?? '',
            'rate' => $rate['5. Exchange Rate'] ?? 0,
            'last_refreshed' => $rate['6. Last Refreshed'] ?? '',
        ];
    }

    private function formatSearchResults(array $matches): array
    {
        $results = [];
        foreach ($matches as $match) {
            $results[] = [
                'symbol' => $match['1. symbol'] ?? '',
                'name' => $match['2. name'] ?? '',
                'type' => $match['3. type'] ?? '',
                'region' => $match['4. region'] ?? '',
                'currency' => $match['8. Currency'] ?? '',
            ];
        }
        return $results;
    }

    private function getDefaultQuote(string $symbol): array
    {
        return [
            'symbol' => $symbol,
            'price' => 0,
            'change' => 0,
            'change_percent' => '0%',
            'high' => 0,
            'low' => 0,
            'volume' => 0,
            'latest_day' => date('Y-m-d'),
            'open' => 0,
            'previous_close' => 0,
        ];
    }

    private function getDefaultForexRate(string $from, string $to): array
    {
        return [
            'from_currency' => $from,
            'to_currency' => $to,
            'rate' => 0,
            'last_refreshed' => date('Y-m-d H:i:s'),
        ];
    }
}
