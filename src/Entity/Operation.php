<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity Operation
 * 
 * Enregistre une transaction financière (revenu ou dépense).
 * 
 * Maintenance :
 * - Les opérations impactent le solde de la Tresorerie liée.
 * - La référence et la facture sont facultatives mais validées si présentes.
 */
#[ORM\Entity(repositoryClass: OperationRepository::class)]
#[ORM\Table(name: 'opérations')]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_operation')]
    private ?int $id = null;

    #[ORM\Column(length: 30, unique: true, nullable: true)]
    #[Assert\Length(min: 3, max: 30, minMessage: 'La référence doit faire au moins {{ limit }} caractères', maxMessage: 'La référence ne peut pas dépasser {{ limit }} caractères')]
    private ?string $reference = null;

    #[ORM\Column(length: 50, unique: true, nullable: true)]
    #[Assert\Length(min: 3, max: 50, minMessage: 'Le numéro de facture doit faire au moins {{ limit }} caractères', maxMessage: 'Le numéro de facture ne peut pas dépasser {{ limit }} caractères')]
    private ?string $facture = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message: 'L\'URL du PDF est invalide')]
    private ?string $pdf_url = null;

    #[ORM\ManyToOne(targetEntity: Tresorerie::class)]
    #[ORM\JoinColumn(name: 'id_tresorerie', referencedColumnName: 'id_tresorerie', nullable: false)]
    #[Assert\NotNull(message: 'Veuillez sélectionner un compte de trésorerie')]
    private ?Tresorerie $tresorerie = null;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank(message: 'Le type de transaction est obligatoire')]
    #[Assert\Choice(choices: ['revenu', 'depense'], message: 'Type de transaction invalide')]
    private ?string $type = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    #[Assert\NotBlank(message: 'Le montant est obligatoire')]
    #[Assert\Type(type: 'numeric', message: 'Le montant doit être un nombre')]
    #[Assert\Positive(message: 'Le montant doit être strictement supérieur à zéro')]
    private ?string $montant = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\NotBlank(message: 'La catégorie est obligatoire')]
    #[Assert\Length(min: 2, max: 50, maxMessage: 'La catégorie ne peut pas dépasser {{ limit }} caractères')]
    private ?string $categorie = null;

    #[ORM\Column(name: 'date_operation', type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Assert\NotNull(message: 'La date est obligatoire')]
    #[Assert\LessThanOrEqual('now', message: 'La date ne peut pas être dans le futur')]
    private ?\DateTimeInterface $dateOperation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 1000, maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères')]
    private ?string $description = null;

    public function __construct()
    {
        $this->dateOperation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    public function getFacture(): ?string
    {
        return $this->facture;
    }

    public function setFacture(?string $facture): self
    {
        $this->facture = $facture;
        return $this;
    }

    public function getPdfUrl(): ?string
    {
        return $this->pdf_url;
    }

    public function setPdfUrl(?string $pdf_url): self
    {
        $this->pdf_url = $pdf_url;
        return $this;
    }

    public function getTresorerie(): ?Tresorerie
    {
        return $this->tresorerie;
    }

    public function setTresorerie(?Tresorerie $tresorerie): self
    {
        $this->tresorerie = $tresorerie;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
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

    /**
     * Get devise from linked tresorerie (virtual, not persisted)
     */
    public function getDevise(): ?string
    {
        return $this->tresorerie?->getDevise();
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getDateOperation(): ?\DateTimeInterface
    {
        return $this->dateOperation;
    }

    public function setDateOperation(?\DateTimeInterface $dateOperation): self
    {
        $this->dateOperation = $dateOperation;
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
     * Vérifie si l'opération est un virement interne.
     */
    public function isVirementInterne(): bool
    {
        return $this->categorie === 'Virement Interne';
    }

    /**
     * Vérifie si l'opération est une dépense importante (> 1000 TND).
     */
    public function isLargeExpense(): bool
    {
        return $this->type === 'depense' && (float)$this->montant > 1000;
    }
}
