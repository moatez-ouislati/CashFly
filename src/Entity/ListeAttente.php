<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'liste_attente')]
class ListeAttente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_attente', type: Types::INTEGER)]
    private ?int $idAttente = null;

    #[ORM\Column(name: 'id_evenement', type: Types::INTEGER)]
    private ?int $idEvenement = null;

    #[ORM\Column(name: 'id_utilisateur', type: Types::INTEGER)]
    private ?int $idUtilisateur = null;

    #[ORM\Column(name: 'date_demande', type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $dateDemande = null;

    #[ORM\Column(name: 'position', type: Types::INTEGER)]
    private ?int $position = null;

    public function getIdAttente(): ?int
    {
        return $this->idAttente;
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

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;
        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(?\DateTimeInterface $dateDemande): static
    {
        $this->dateDemande = $dateDemande;
        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;
        return $this;
    }
}
