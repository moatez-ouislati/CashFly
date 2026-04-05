<?php

namespace App\Service;

use App\Entity\Operation;
use App\Entity\Tresorerie;
use App\Repository\OperationRepository;
use App\Repository\TresorerieRepository;
use Doctrine\ORM\EntityManagerInterface;

class TransactionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private OperationRepository $operationRepository,
        private TresorerieRepository $tresorerieRepository
    ) {}

    public function createOperation(
        Tresorerie $tresorerie,
        string $type,
        float $montant,
        ?string $categorie = null,
        ?string $description = null
    ): Operation {
        $operation = new Operation();
        $operation->setTresorerie($tresorerie);
        $operation->setType($type);
        $operation->setMontant(number_format($montant, 2, '.', ''));
        $operation->setCategorie($categorie);
        $operation->setDescription($description);
        $operation->setDateOperation(new \DateTime());
        $operation->setReference('OP-' . strtoupper(substr(uniqid(), -5)));

        if ($type === Operation::TYPE_REVENU) {
            $tresorerie->addSolde($montant);
        } else {
            $tresorerie->subtractSolde($montant);
        }

        $this->entityManager->persist($operation);
        $this->entityManager->persist($tresorerie);
        $this->entityManager->flush();

        return $operation;
    }

    public function getStatistics(): array
    {
        return [
            'totalRevenus' => $this->operationRepository->getTotalRevenus(),
            'totalDepenses' => $this->operationRepository->getTotalDepenses(),
            'totalOperations' => count($this->operationRepository->findAll()),
            'soldeTotal' => $this->tresorerieRepository->getTotalSolde(),
        ];
    }

    public function getMontantByCategory(): array
    {
        return $this->operationRepository->getMontantByCategory();
    }

    public function searchOperations(string $query): array
    {
        return $this->operationRepository->search($query);
    }
}
