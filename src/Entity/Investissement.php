<?php

namespace App\Entity;

use App\Repository\InvestissementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity Investissement
 * 
 * Permet à un investisseur de placer des fonds dans une entreprise.
 * 
 * Maintenance :
 * - Le statut change selon l'évolution du placement.
 * - Le taux de rendement est exprimé en pourcentage.
 */
#[ORM\Entity(repositoryClass: InvestissementRepository::class)]
#[ORM\Table(name: 'investissement')]
class Investissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_investissement')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_investisseur', referencedColumnName: 'id_utilisateur', nullable: false, onDelete: 'CASCADE')]
    private ?User $investisseur = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class)]
    #[ORM\JoinColumn(name: 'id_entreprise', referencedColumnName: 'id_entreprise', nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull(message: 'Veuillez sélectionner une entreprise')]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    #[Assert\NotBlank(message: 'Le montant est obligatoire')]
    #[Assert\Type(type: 'numeric', message: 'Le montant doit être un nombre')]
    #[Assert\Positive(message: 'Le montant doit être strictement supérieur à zéro')]
    private ?string $montant = null;

    #[ORM\Column(name: 'date_investissement', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Assert\NotNull(message: 'La date est obligatoire')]
    #[Assert\LessThanOrEqual('now', message: 'La date ne peut pas être dans le futur')]
    private ?\DateTimeInterface $dateInvestissement = null;

    #[ORM\Column(length: 20, options: ['default' => 'EN_ATTENTE'])]
    #[Assert\NotBlank(message: 'Le statut est obligatoire')]
    #[Assert\Choice(choices: ['EN_ATTENTE', 'ACTIF', 'TERMINE', 'ANNULE'], message: 'Statut invalide')]
    private ?string $statut = 'EN_ATTENTE';

    #[ORM\Column(name: 'taux_rendement_prevu', type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    #[Assert\Type(type: 'numeric', message: 'Le taux doit être un nombre')]
    #[Assert\Range(min: 0, max: 100, notInRangeMessage: 'Le taux doit être entre {{ min }}% et {{ max }}%')]
    private ?string $tauxRendementPrevu = null;

    #[ORM\Column(name: 'duree_mois', nullable: true)]
    #[Assert\Positive(message: 'La durée doit être positive')]
    #[Assert\Type(type: 'integer', message: 'La durée doit être un nombre entier')]
    #[Assert\Range(min: 1, max: 120, notInRangeMessage: 'La durée doit être entre 1 et 120 mois')]
    private ?int $dureeMois = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 2000, maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères')]
    private ?string $description = null;

    public function __construct()
    {
        $this->dateInvestissement = new \DateTime();
        $this->statut = 'EN_ATTENTE';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvestisseur(): ?User
    {
        return $this->investisseur;
    }

    public function setInvestisseur(?User $investisseur): self
    {
        $this->investisseur = $investisseur;
        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;
        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function getDateInvestissement(): ?\DateTimeInterface
    {
        return $this->dateInvestissement;
    }

    public function setDateInvestissement(\DateTimeInterface $dateInvestissement): self
    {
        $this->dateInvestissement = $dateInvestissement;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getTauxRendementPrevu(): ?string
    {
        return $this->tauxRendementPrevu;
    }

    public function setTauxRendementPrevu(?string $tauxRendementPrevu): self
    {
        $this->tauxRendementPrevu = $tauxRendementPrevu;
        return $this;
    }

    public function getDureeMois(): ?int
    {
        return $this->dureeMois;
    }

    public function setDureeMois(?int $dureeMois): self
    {
        $this->dureeMois = $dureeMois;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Calcule le profit attendu (ROI) en TND.
     */
    public function calculateExpectedROI(): float
    {
        return (float)$this->montant * ((float)$this->tauxRendementPrevu / 100);
    }

    /**
     * Calcule le montant total final (Capital + ROI).
     */
    public function calculateTotalReturn(): float
    {
        return (float)$this->montant + $this->calculateExpectedROI();
    }
}
