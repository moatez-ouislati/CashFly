<?php

namespace App\Service;

use App\Entity\Investissement;
use App\Repository\InvestissementRepository;
use Doctrine\ORM\EntityManagerInterface;

class RiskScoringService
{
    private array $defaultWeights = [
        'montant' => 0.30,
        'taux_rendement_prevu' => 0.30,
        'duree_mois' => 0.20,
        'statut' => 0.20,
    ];

    private array $defaultRanges = [
        'montant' => ['min' => 0, 'max' => 100000],
        'taux_rendement_prevu' => ['min' => 0, 'max' => 50],
        'duree_mois' => ['min' => 0, 'max' => 60],
    ];

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setWeights(array $weights): self
    {
        $this->defaultWeights = array_merge($this->defaultWeights, $weights);
        return $this;
    }

    public function setRanges(array $ranges): self
    {
        $this->defaultRanges = array_merge($this->defaultRanges, $ranges);
        return $this;
    }

    public function calculateRiskScore(Investissement $investment): array
    {
        $weights = $this->defaultWeights;
        $ranges = $this->defaultRanges;

        $montant = $investment->getMontantFloat();
        $tauxRendement = $investment->getTauxRendementPrevuFloat() ?? 0;
        $dureeMois = $investment->getDureeMois() ?? 0;
        $statut = $investment->getStatut();

        $normalizedMontant = $this->normalize($montant, $ranges['montant']['min'], $ranges['montant']['max']);
        $normalizedTaux = $this->normalize($tauxRendement, $ranges['taux_rendement_prevu']['min'], $ranges['taux_rendement_prevu']['max']);
        $normalizedDuree = $this->normalize($dureeMois, $ranges['duree_mois']['min'], $ranges['duree_mois']['max']);
        $normalizedStatut = $this->normalizeStatut($statut);

        $score = (
            ($normalizedMontant * $weights['montant']) +
            ($normalizedTaux * $weights['taux_rendement_prevu']) +
            ($normalizedDuree * $weights['duree_mois']) +
            ($normalizedStatut * $weights['statut'])
        ) * 100;

        $score = min(100, max(0, round($score)));

        $riskLevel = $this->getRiskLevel($score);

        return [
            'score' => $score,
            'riskLevel' => $riskLevel,
            'components' => [
                'montant' => ['value' => $montant, 'normalized' => $normalizedMontant, 'weight' => $weights['montant']],
                'taux_rendement_prevu' => ['value' => $tauxRendement, 'normalized' => $normalizedTaux, 'weight' => $weights['taux_rendement_prevu']],
                'duree_mois' => ['value' => $dureeMois, 'normalized' => $normalizedDuree, 'weight' => $weights['duree_mois']],
                'statut' => ['value' => $statut, 'normalized' => $normalizedStatut, 'weight' => $weights['statut']],
            ],
        ];
    }

    public function calculateAllRisks(?array $weights = null): array
    {
        if ($weights !== null) {
            $this->setWeights($weights);
        }

        $repo = $this->em->getRepository(Investissement::class);
        $investissements = $repo->findAll();

        $results = [];
        foreach ($investissements as $investment) {
            $risk = $this->calculateRiskScore($investment);
            $results[] = [
                'investment' => $investment,
                'riskScore' => $risk['score'],
                'riskLevel' => $risk['riskLevel'],
                'components' => $risk['components'],
            ];
        }

        usort($results, function($a, $b) {
            return $b['riskScore'] - $a['riskScore'];
        });

        return $results;
    }

    public function getDistribution(array $risks): array
    {
        $distribution = ['LOW' => 0, 'MEDIUM' => 0, 'HIGH' => 0];
        foreach ($risks as $risk) {
            $distribution[$risk['riskLevel']]++;
        }
        return $distribution;
    }

    private function normalize(float $value, float $min, float $max): float
    {
        if ($max === $min) {
            return 0;
        }
        return min(1, max(0, ($value - $min) / ($max - $min)));
    }

    private function normalizeStatut(string $statut): float
    {
        return match($statut) {
            Investissement::STATUT_EN_ATTENTE => 1.0,
            Investissement::STATUT_REFUSE => 0.8,
            Investissement::STATUT_VALIDE => 0.2,
            Investissement::STATUT_CLOTURE => 0.1,
            default => 0.5,
        };
    }

    private function getRiskLevel(int $score): string
    {
        if ($score <= 30) {
            return 'LOW';
        } elseif ($score <= 60) {
            return 'MEDIUM';
        }
        return 'HIGH';
    }
}