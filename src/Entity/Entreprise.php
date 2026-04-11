<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'entreprises')]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_entreprise', type: Types::INTEGER)]
    private ?int $idEntreprise = null;

    #[ORM\Column(name: 'nom', length: 150)]
    private ?string $nom = null;

    #[ORM\Column(name: 'secteur', length: 100, nullable: true)]
    private ?string $secteur = null;

    #[ORM\Column(name: 'forme_juridique', length: 50, nullable: true)]
    private ?string $formeJuridique = null;

    #[ORM\Column(name: 'date_creation', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(name: 'capital', type: Types::DECIMAL, precision: 15, scale: 2, options: ['default' => '0.00'])]
    private ?string $capital = '0.00';

    #[ORM\Column(name: 'id_proprietaire', type: Types::INTEGER)]
    private ?int $idProprietaire = null;

    public function getIdEntreprise(): ?int
    {
        return $this->idEntreprise;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(?string $secteur): static
    {
        $this->secteur = $secteur;
        return $this;
    }

    public function getFormeJuridique(): ?string
    {
        return $this->formeJuridique;
    }

    public function setFormeJuridique(?string $formeJuridique): static
    {
        $this->formeJuridique = $formeJuridique;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getCapital(): ?string
    {
        return $this->capital;
    }

    public function setCapital(string $capital): static
    {
        $this->capital = $capital;
        return $this;
    }

    public function getIdProprietaire(): ?int
    {
        return $this->idProprietaire;
    }

    public function setIdProprietaire(int $idProprietaire): static
    {
        $this->idProprietaire = $idProprietaire;
        return $this;
    }
}
