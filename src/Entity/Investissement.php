<?php

namespace App\Entity;

use App\Repository\InvestissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvestissementRepository::class)]
#[ORM\Table(name: 'investissement')]
class Investissement
{
    public const STATUT_EN_ATTENTE = 'EN_ATTENTE';
    public const STATUT_VALIDE = 'ACTIF';
    public const STATUT_REFUSE = 'ANNULE';
    public const STATUT_CLOTURE = 'TERMINE';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_investissement')]
    private ?int $idInvestissement = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'investissements')]
    #[ORM\JoinColumn(name: 'id_investisseur', referencedColumnName: 'id_utilisateur', nullable: false)]
    private ?Utilisateur $investisseur = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: 'investissements')]
    #[ORM\JoinColumn(name: 'id_entreprise', referencedColumnName: 'id_entreprise', nullable: false)]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private ?string $montant = '0.00';

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateInvestissement = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = self::STATUT_EN_ATTENTE;

    #[ORM\Column(name: 'taux_rendement_prevu', type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $tauxRendementPrevu = null;

    #[ORM\Column(name: 'duree_mois', nullable: true)]
    private ?int $dureeMois = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: RendementInvestissement::class, mappedBy: 'investissement', cascade: ['persist', 'remove'])]
    private Collection $rendements;

    public function __construct()
    {
        $this->rendements = new ArrayCollection();
        $this->dateInvestissement = new \DateTime();
        $this->statut = self::STATUT_EN_ATTENTE;
    }

    public function getIdInvestissement(): ?int
    {
        return $this->idInvestissement;
    }

    public function getId(): ?int
    {
        return $this->idInvestissement;
    }

    public function getNom(): ?string
    {
        return $this->entreprise ? $this->entreprise->getNom() : 'Investissement #' . $this->idInvestissement;
    }

    public function getInvestisseur(): ?Utilisateur
    {
        return $this->investisseur;
    }

    public function setInvestisseur(?Utilisateur $investisseur): static
    {
        $this->investisseur = $investisseur;
        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;
        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function getMontantFloat(): float
    {
        return (float) $this->montant;
    }

    public function setMontant(string $montant): static
    {
        $this->montant = $montant;
        return $this;
    }

    public function getDateInvestissement(): ?\DateTimeInterface
    {
        return $this->dateInvestissement;
    }

    public function setDateInvestissement(\DateTimeInterface $dateInvestissement): static
    {
        $this->dateInvestissement = $dateInvestissement;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function isEnAttente(): bool
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }

    public function isActif(): bool
    {
        return $this->statut === self::STATUT_VALIDE;
    }

    public function isTermine(): bool
    {
        return $this->statut === self::STATUT_CLOTURE;
    }

    public function isAnnule(): bool
    {
        return $this->statut === self::STATUT_REFUSE;
    }

    public function getTauxRendementPrevu(): ?string
    {
        return $this->tauxRendementPrevu;
    }

    public function getTauxRendementPrevuFloat(): ?float
    {
        return $this->tauxRendementPrevu !== null ? (float) $this->tauxRendementPrevu : null;
    }

    public function setTauxRendementPrevu(?string $tauxRendementPrevu): static
    {
        $this->tauxRendementPrevu = $tauxRendementPrevu;
        return $this;
    }

    public function getDureeMois(): ?int
    {
        return $this->dureeMois;
    }

    public function setDureeMois(?int $dureeMois): static
    {
        $this->dureeMois = $dureeMois;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getRendements(): Collection
    {
        return $this->rendements;
    }

    public function addRendement(RendementInvestissement $rendement): static
    {
        if (!$this->rendements->contains($rendement)) {
            $this->rendements->add($rendement);
            $rendement->setInvestissement($this);
        }
        return $this;
    }

    public function removeRendement(RendementInvestissement $rendement): static
    {
        if ($this->rendements->removeElement($rendement)) {
            if ($rendement->getInvestissement() === $this) {
                $rendement->setInvestissement(null);
            }
        }
        return $this;
    }

    public function getGainAttendu(): float
    {
        if (!$this->tauxRendementPrevu || !$this->dureeMois) {
            return 0;
        }
        return $this->getMontantFloat() * ($this->getTauxRendementPrevuFloat() / 100);
    }

    public function getStatutBadgeClass(): string
    {
        return match($this->statut) {
            self::STATUT_EN_ATTENTE => 'bg-warning',
            self::STATUT_VALIDE => 'bg-success',
            self::STATUT_REFUSE => 'bg-danger',
            self::STATUT_CLOTURE => 'bg-secondary',
            default => 'bg-secondary',
        };
    }
}
