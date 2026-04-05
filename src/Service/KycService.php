<?php

namespace App\Service;

use App\Entity\UserKyc;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class KycService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function enrollKyc(Utilisateur $user): UserKyc
    {
        $kyc = new UserKyc();
        $kyc->setUtilisateur($user);
        $kyc->setCreatedAt(new \DateTime());
        $kyc->setIsVerified(0);

        $this->entityManager->persist($kyc);
        $this->entityManager->flush();

        return $kyc;
    }

    public function verifyUser(Utilisateur $user): bool
    {
        $kyc = $user->getUserKyc();
        if (!$kyc) {
            $kyc = $this->enrollKyc($user);
        }

        $kyc->setIsVerified(1);
        $kyc->setVerifiedAt(new \DateTime());
        
        $this->entityManager->flush();

        return true;
    }

    public function isUserVerified(Utilisateur $user): bool
    {
        $kyc = $user->getUserKyc();
        return $kyc && $kyc->isVerified();
    }

    public function getPendingVerifications(): array
    {
        return $this->entityManager->getRepository(UserKyc::class)
            ->findBy(['isVerified' => 0]);
    }
}
