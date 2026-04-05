<?php

namespace App\Service;

use App\Entity\Investissement;
use App\Repository\InvestissementRepository;
use App\Repository\RendementInvestissementRepository;
use Doctrine\ORM\EntityManagerInterface;

class InvestmentService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private InvestissementRepository $investissementRepository,
        private RendementInvestissementRepository $rendementRepository
    ) {}

    public function createInvestissement(
        int $investisseurId,
        int $entrepriseId,
        float $montant,
        ?float $tauxRendement = null,
        ?int $dureeMois = null,
        ?string $description = null
    ): Investissement {
        $investissement = new Investissement();
        $investissement->setMontant(number_format($montant, 2, '.', ''));
        $investissement->setTauxRendementPrevu($tauxRendement ? number_format($tauxRendement, 2, '.', '') : null);
        $investissement->setDureeMois($dureeMois);
        $investissement->setDescription($description);
        $investissement->setStatut(Investissement::STATUT_EN_ATTENTE);
        $investissement->setDateInvestissement(new \DateTime());

        $this->entityManager->persist($investissement);
        $this->entityManager->flush();

        return $investissement;
    }

    public function approveInvestissement(int $id): bool
    {
        $investissement = $this->investissementRepository->find($id);
        if (!$investissement) {
            return false;
        }

        $investissement->setStatut(Investissement::STATUT_VALIDE);
        $this->entityManager->flush();

        return true;
    }

    public function rejectInvestissement(int $id): bool
    {
        $investissement = $this->investissementRepository->find($id);
        if (!$investissement) {
            return false;
        }

        $investissement->setStatut(Investissement::STATUT_REFUSE);
        $this->entityManager->flush();

        return true;
    }

    public function getTotalInvesti(): float
    {
        return $this->investissementRepository->getTotalMontantInvesti();
    }

    public function getTopInvestors(int $limit = 10): array
    {
        return $this->investissementRepository->getTopInvestors($limit);
    }

    public function getInvestissementStats(): array
    {
        $montantsByStatut = $this->investissementRepository->getMontantByStatut();
        
        return [
            'total' => $this->getTotalInvesti(),
            'pending' => $this->countByStatut($montantsByStatut, Investissement::STATUT_EN_ATTENTE)['total'] ?? 0,
            'validated' => $this->countByStatut($montantsByStatut, Investissement::STATUT_VALIDE)['total'] ?? 0,
            'rejected' => $this->countByStatut($montantsByStatut, Investissement::STATUT_REFUSE)['total'] ?? 0,
            'closed' => $this->countByStatut($montantsByStatut, Investissement::STATUT_CLOTURE)['total'] ?? 0,
        ];
    }

    private function countByStatut(array $data, string $statut): ?array
    {
        foreach ($data as $item) {
            if ($item['statut'] === $statut) {
                return $item;
            }
        }
        return null;
    }
}
