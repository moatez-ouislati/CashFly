<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'participation_jpo')]
class ParticipationJpo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_participation', type: Types::INTEGER)]
    private ?int $idParticipation = null;

    #[ORM\Column(name: 'id_evenement', type: Types::INTEGER)]
    private ?int $idEvenement = null;

    #[ORM\Column(name: 'id_entreprise', type: Types::INTEGER, nullable: true)]
    private ?int $idEntreprise = null;

    #[ORM\Column(name: 'id_utilisateur', type: Types::INTEGER)]
    private ?int $idUtilisateur = null;

    #[ORM\Column(name: 'statut', type: Types::STRING, length: 50, nullable: true, options: ['default' => 'confirmé'])]
    private ?string $statut = 'confirmé';

    #[ORM\Column(name: 'date_inscription', type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\Column(name: 'badge_genere', type: Types::BOOLEAN, nullable: true, options: ['default' => 0])]
    private ?bool $badgeGenere = false;

    public function getIdParticipation(): ?int
    {
        return $this->idParticipation;
    }

    public function getIdEvenement(): ?int
    {
        return $this->idEvenement;
    }

    public function setIdEvenement(int $idEvenement): static
    {
        $this->idEvenement = $idEvenement;
        return $this;
    }

    public function getIdEntreprise(): ?int
    {
        return $this->idEntreprise;
    }

    public function setIdEntreprise(?int $idEntreprise): static
    {
        $this->idEntreprise = $idEntreprise;
        return $this;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(?\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;
        return $this;
    }

    public function isBadgeGenere(): ?bool
    {
        return $this->badgeGenere;
    }

    public function setBadgeGenere(?bool $badgeGenere): static
    {
        $this->badgeGenere = $badgeGenere;
        return $this;
    }
}
