<?php

namespace App\Service;

use App\Entity\Investissement;
use App\Entity\RendementInvestissement;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class InvestmentService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createInvestissement(array $data, User $investisseur): Investissement
    {
        $investissement = new Investissement();
        $investissement->setInvestisseur($investisseur);
        $investissement->setEntreprise($data['entreprise']);
        $investissement->setMontant($data['montant'] ?? 0.0);
        $investissement->setTauxRendementPrevu($data['taux_rendement'] ?? null);
        $investissement->setDureeMois($data['duree_mois'] ?? null);
        $investissement->setDescription($data['description'] ?? null);
        $investissement->setStatut('EN_ATTENTE');
        $investissement->setDateInvestissement(new \DateTime());
        
        $this->entityManager->persist($investissement);
        $this->entityManager->flush();

        return $investissement;
    }

    public function approveInvestissement(int $id): bool
    {
        $investissement = $this->entityManager->getRepository(Investissement::class)->find($id);
        if (!$investissement) return false;

        $investissement->setStatut('ACTIF');
        $this->entityManager->flush();
        return true;
    }

    public function rejectInvestissement(int $id): bool
    {
        $investissement = $this->entityManager->getRepository(Investissement::class)->find($id);
        if (!$investissement) return false;

        $investissement->setStatut('ANNULE');
        $this->entityManager->flush();
        return true;
    }

    public function calculateRendement(int $investissementId, float $gain, float $perte): RendementInvestissement
    {
        $investissement = $this->entityManager->getRepository(Investissement::class)->find($investissementId);
        if (!$investissement) throw new \Exception("Investissement non trouvé");

        $rendement = new RendementInvestissement();
        $rendement->setInvestissement($investissement);
        $rendement->setGain((string)$gain);
        $rendement->setPerte((string)$perte);
        $rendement->setDateCalcul(new \DateTime());
        
        $currentValue = (float)$investissement->getMontant() + $gain - $perte;
        $rendement->setValeurPortefeuille((string)$currentValue);

        $this->entityManager->persist($rendement);
        $this->entityManager->flush();

        return $rendement;
    }

    public function getPortfolioStats(User $user): array
    {
        $repository = $this->entityManager->getRepository(Investissement::class);
        $investments = $repository->findBy(['investisseur' => $user]);
        
        $totalInvested = 0;
        $totalGain = 0;
        $activeCount = 0;

        foreach ($investments as $inv) {
            $totalInvested += (float)$inv->getMontant();
            if ($inv->getStatut() === 'ACTIF') {
                $activeCount++;
            }
            
            // Calculer les gains réels via RendementInvestissement
            $rendements = $this->entityManager->getRepository(RendementInvestissement::class)->findBy(['investissement' => $inv]);
            foreach ($rendements as $rend) {
                $totalGain += (float)$rend->getGain() - (float)$rend->getPerte();
            }
        }

        return [
            'totalInvested' => $totalInvested,
            'totalGain' => $totalGain,
            'count' => $activeCount,
            'totalCount' => count($investments),
            'sectorDistribution' => $this->getSectorDistribution($user),
            'monthlyEvolution' => $this->getMonthlyEvolution($user),
            'averageYield' => $this->getAverageYield($user),
            'expectedTotalGain' => $this->getExpectedTotalGain($user)
        ];
    }

    private function getSectorDistribution(User $user): array
    {
        $repository = $this->entityManager->getRepository(Investissement::class);
        $investments = $repository->findBy(['investisseur' => $user, 'statut' => 'ACTIF']);
        
        $distribution = [];
        foreach ($investments as $inv) {
            $sector = $inv->getEntreprise()->getSecteur() ?? 'Autre';
            if (!isset($distribution[$sector])) {
                $distribution[$sector] = 0;
            }
            $distribution[$sector] += (float)$inv->getMontant();
        }
        
        return $distribution;
    }

    private function getMonthlyEvolution(User $user): array
    {
        $repository = $this->entityManager->getRepository(Investissement::class);
        $investments = $repository->findBy(['investisseur' => $user, 'statut' => 'ACTIF'], ['dateInvestissement' => 'ASC']);
        
        $evolution = [];
        foreach ($investments as $inv) {
            $month = $inv->getDateInvestissement()->format('Y-m');
            if (!isset($evolution[$month])) {
                $evolution[$month] = 0;
            }
            $evolution[$month] += (float)$inv->getMontant();
        }
        
        $result = [];
        foreach ($evolution as $month => $total) {
            $result[] = ['month' => $month, 'total' => $total];
        }
        
        return array_slice($result, -12);
    }

    private function getAverageYield(User $user): float
    {
        $repository = $this->entityManager->getRepository(Investissement::class);
        $investments = $repository->findBy(['investisseur' => $user, 'statut' => 'ACTIF']);
        
        if (empty($investments)) return 0.0;
        
        $totalYield = 0;
        $count = 0;
        foreach ($investments as $inv) {
            if ($inv->getTauxRendementPrevu()) {
                $totalYield += (float)$inv->getTauxRendementPrevu();
                $count++;
            }
        }
        
        return $count > 0 ? round($totalYield / $count, 2) : 0.0;
    }

    private function getExpectedTotalGain(User $user): float
    {
        $repository = $this->entityManager->getRepository(Investissement::class);
        $investments = $repository->findBy(['investisseur' => $user, 'statut' => 'ACTIF']);
        
        $expected = 0;
        foreach ($investments as $inv) {
            $montant = (float)$inv->getMontant();
            $taux = (float)$inv->getTauxRendementPrevu() / 100;
            $expected += $montant * $taux;
        }
        
        return $expected;
    }
}
