<?php

namespace App\Service;

class ForecastService
{
    public function calculateGrowth(array $historicalData): array
    {
        if (count($historicalData) < 2) {
            return [
                'growth_rate' => 0,
                'trend' => 'stable',
                'prediction' => [],
            ];
        }

        $values = array_column($historicalData, 'nb_entreprises');
        $months = array_column($historicalData, 'month');
        
        $n = count($values);
        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumX2 = 0;
        
        foreach ($values as $i => $y) {
            $x = $i + 1;
            $sumX += $x;
            $sumY += $y;
            $sumXY += $x * $y;
            $sumX2 += $x * $x;
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;
        
        $lastValue = end($values);
        $predictions = [];
        $lastMonth = end($months);
        
        for ($i = 1; $i <= 6; $i++) {
            $predictedValue = max(0, round($intercept + $slope * ($n + $i)));
            $predictions[] = [
                'month' => $this->getNextMonth($lastMonth, $i),
                'predicted' => (int) $predictedValue,
            ];
        }
        
        $avgValue = array_sum($values) / $n;
        $growthRate = $avgValue > 0 ? (($lastValue - $values[0]) / $values[0]) * 100 : 0;
        
        $trend = $slope > 0.5 ? 'up' : ($slope < -0.5 ? 'down' : 'stable');
        
        return [
            'growth_rate' => round($growthRate, 1),
            'trend' => $trend,
            'slope' => round($slope, 2),
            'prediction' => $predictions,
            'historical' => $historicalData,
        ];
    }

    public function calculateCapitalGrowth(array $historicalData): array
    {
        if (count($historicalData) < 2) {
            return [
                'total_growth' => 0,
                'avg_capital' => 0,
                'prediction' => [],
            ];
        }

        $values = array_column($historicalData, 'total_capital');
        $months = array_column($historicalData, 'month');
        
        $n = count($values);
        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumX2 = 0;
        
        foreach ($values as $i => $y) {
            $x = $i + 1;
            $sumX += $x;
            $sumY += $y;
            $sumXY += $x * $y;
            $sumX2 += $x * $x;
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;
        
        $lastMonth = end($months);
        $lastValue = end($values);
        $firstValue = $values[0];
        
        $predictions = [];
        for ($i = 1; $i <= 6; $i++) {
            $predictedValue = max(0, round($intercept + $slope * ($n + $i), 2));
            $predictions[] = [
                'month' => $this->getNextMonth($lastMonth, $i),
                'predicted' => (float) $predictedValue,
            ];
        }
        
        $totalGrowth = $firstValue > 0 ? (($lastValue - $firstValue) / $firstValue) * 100 : 0;
        
        return [
            'total_growth' => round($totalGrowth, 1),
            'current_total' => (float) $lastValue,
            'avg_capital' => round(array_sum($values) / $n, 2),
            'slope' => round($slope, 2),
            'prediction' => $predictions,
            'historical' => $historicalData,
        ];
    }

    public function getSectorForecast(array $sectorData): array
    {
        $forecast = [];
        
        foreach ($sectorData as $sector) {
            $forecast[] = [
                'secteur' => $sector['secteur'],
                'nb_entreprises' => (int) $sector['nb_entreprises'],
                'total_capital' => (float) $sector['total_capital'],
                'avg_capital' => round((float) $sector['avg_capital'], 2),
                'projected_growth' => $this->estimateGrowth($sector),
            ];
        }
        
        usort($forecast, fn($a, $b) => $b['total_capital'] <=> $a['total_capital']);
        
        return $forecast;
    }

    private function estimateGrowth(array $sector): float
    {
        $base = (float) $sector['avg_capital'];
        
        if ($base > 1000000) {
            return rand(5, 15) / 10;
        } elseif ($base > 100000) {
            return rand(10, 25) / 10;
        } else {
            return rand(15, 35) / 10;
        }
    }

    private function getNextMonth(string $lastMonth, int $offset): string
    {
        $date = new \DateTime($lastMonth . '-01');
        $date->modify("+{$offset} month");
        return $date->format('Y-m');
    }
}
