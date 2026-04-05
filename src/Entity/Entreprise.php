<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
#[ORM\Table(name: 'entreprises')]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_entreprise')]
    private ?int $idEntreprise = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $secteur = null;

    #[ORM\Column(name: 'forme_juridique', length: 50, nullable: true)]
    private ?string $formeJuridique = null;

    #[ORM\Column(name: 'date_creation', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, nullable: true)]
    private ?string $capital = '0.00';

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'entreprises')]
    #[ORM\JoinColumn(name: 'id_proprietaire', referencedColumnName: 'id_utilisateur', nullable: false)]
    private ?Utilisateur $proprietaire = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\OneToMany(targetEntity: Tresorerie::class, mappedBy: 'entreprise')]
    private Collection $tresoreries;

    #[ORM\OneToMany(targetEntity: Investissement::class, mappedBy: 'entreprise')]
    private Collection $investissements;

    public function __construct()
    {
        $this->tresoreries = new ArrayCollection();
        $this->investissements = new ArrayCollection();
    }

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

    public function getCapitalFloat(): float
    {
        return (float) ($this->capital ?? 0);
    }

    public function setCapital(?string $capital): static
    {
        $this->capital = $capital;
        return $this;
    }

    public function getProprietaire(): ?Utilisateur
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Utilisateur $proprietaire): static
    {
        $this->proprietaire = $proprietaire;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getTresoreries(): Collection
    {
        return $this->tresoreries;
    }

    public function getInvestissements(): Collection
    {
        return $this->investissements;
    }
}
