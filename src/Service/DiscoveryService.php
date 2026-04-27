<?php

namespace App\Service;

use App\Entity\JourneePorteOuverte;
use App\Repository\JourneePorteOuverteRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DiscoveryService
{
    public function __construct(
        private readonly JourneePorteOuverteRepository $jpoRepository,
        private readonly HttpClientInterface $httpClient,
        private readonly string $geminiApiKey = '' // Optional for now
    ) {}

    public function getNearbySuggestion(JourneePorteOuverte $currentEvent): ?array
    {
        $lat = $currentEvent->getLatitude();
        $lng = $currentEvent->getLongitude();

        // If current event has no coordinates, we can't find nearby ones
        if ($lat === null || $lng === null) {
            return null;
        }

        // Search for events within 50km, occurring 12-36 hours AFTER the reference event
        $candidates = $this->jpoRepository->findNearbyEvents(
            $lat, 
            $lng, 
            50, // 50 km
            12, // Min 12 hours after
            36, // Max 36 hours after
            $currentEvent->getIdEvenement(),
            $currentEvent->getDateEvenement() // Reference is the current event's time
        );

        if (empty($candidates)) {
            return null;
        }

        // Rank by subject similarity
        $suggestedEvent = $this->findBestSubjectMatch($currentEvent, $candidates);

        // Generate a friendly AI hook
        $hook = $this->generateAiHook($currentEvent, $suggestedEvent);

        return [
            'event' => $suggestedEvent,
            'hook' => $hook
        ];
    }

    private function findBestSubjectMatch(JourneePorteOuverte $source, array $candidates): JourneePorteOuverte
    {
        $sourceText = strtolower($source->getTitre() . ' ' . $source->getDescription());
        $keywords = ['tech', 'fintech', 'immobilier', 'startup', 'finance', 'web', 'marketing', 'vendeur', 'investir', 'banque'];
        
        $sourceKeywords = [];
        foreach ($keywords as $kw) {
            if (str_contains($sourceText, $kw)) $sourceKeywords[] = $kw;
        }

        $bestScore = -1;
        $bestCandidate = $candidates[0];

        foreach ($candidates as $candidate) {
            $candidateText = strtolower($candidate->getTitre() . ' ' . $candidate->getDescription());
            $score = 0;
            foreach ($sourceKeywords as $kw) {
                if (str_contains($candidateText, $kw)) $score += 10;
            }
            
            // Title word match (3+ chars)
            $sourceTitleWords = explode(' ', strtolower($source->getTitre()));
            foreach ($sourceTitleWords as $word) {
                if (strlen($word) > 3 && str_contains($candidateText, $word)) $score += 5;
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestCandidate = $candidate;
            }
        }

        return $bestCandidate;
    }

    private function generateAiHook(JourneePorteOuverte $current, JourneePorteOuverte $target): string
    {
        // If we have an API key, we could call Gemini here.
        // For now, let's use a smart template-based AI message
        
        $distance = $this->calculateDistance(
            $current->getLatitude(), $current->getLongitude(),
            $target->getLatitude(), $target->getLongitude()
        );

        return sprintf(
            "Puisque vous participez à '%s', ne manquez pas '%s' ! C'est à seulement %.1f km et il reste quelques places pour dans %d jours.",
            $current->getTitre(),
            $target->getTitre(),
            $distance,
            $target->getDateEvenement()->diff(new \DateTime())->days
        );
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
