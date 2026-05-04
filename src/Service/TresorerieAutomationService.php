<?php

namespace App\Service;

use App\Entity\Entreprise;
use App\Entity\Operation;
use App\Entity\Tresorerie;
use Doctrine\ORM\EntityManagerInterface;

class TresorerieAutomationService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function createFromEntrepriseCapital(Entreprise $entreprise): Tresorerie
    {
        $capital = (float) ($entreprise->getCapital() ?? 0);

        $tresorerie = new Tresorerie();
        $tresorerie->setEntreprise($entreprise);
        $tresorerie->setNomCompte('Trésorerie Principale - ' . $entreprise->getNom());
        $tresorerie->setTypeCompte('BANQUE');
        $tresorerie->setDevise('TND');
        $tresorerie->setSolde(number_format(max(0, $capital), 2, '.', ''));
        $tresorerie->setDerniereMaj(new \DateTime());
        $tresorerie->setNumeroCompte($this->generateNumeroCompte());

        $this->entityManager->persist($tresorerie);
        $this->createInitialOperation($tresorerie);

        return $tresorerie;
    }

    public function createInitialOperation(Tresorerie $tresorerie): ?Operation
    {
        $solde = (float) ($tresorerie->getSolde() ?? 0);
        $devise = $tresorerie->getDevise() ?? 'TND';
        
        if ($solde <= 0) {
            return null;
        }

        $operation = new Operation();
        $operation->setTresorerie($tresorerie);
        $operation->setType('revenu');
        $operation->setMontant(number_format($solde, 2, '.', ''));
        $operation->setReference('SOLDE-INIT-' . date('Ymd-His') . '-' . substr(uniqid('', true), -4));
        $operation->setCategorie('Solde Initial');
        $operation->setDescription('Création automatique de trésorerie avec solde initial.');
        $operation->setDateOperation(new \DateTime());

        $this->entityManager->persist($operation);

        return $operation;
    }

    public function generateNumeroCompte(): string
    {
        return date('ymd') . random_int(100000000, 999999999);
    }
}
