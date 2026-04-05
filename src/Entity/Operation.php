<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperationRepository::class)]
#[ORM\Table(name: 'OPÉRATIONS')]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_operation')]
    private ?int $idOperation = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pdfUrl = null;

    #[ORM\ManyToOne(targetEntity: Tresorerie::class)]
    #[ORM\JoinColumn(name: 'id_tresorerie', referencedColumnName: 'id_tresorerie', nullable: true)]
    private ?Tresorerie $tresorerie = null;

    #[ORM\Column(length: 20)]
    private ?string $type = 'depense';

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private ?string $montant = '0.00';

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $categorie = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateOperation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: OperationNote::class, mappedBy: 'operation', cascade: ['persist', 'remove'])]
    private Collection $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->dateOperation = new \DateTime();
    }

    public function getIdOperation(): ?int
    {
        return $this->idOperation;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;
        return $this;
    }

    public function getFacture(): ?string
    {
        return $this->facture;
    }

    public function setFacture(?string $facture): static
    {
        $this->facture = $facture;
        return $this;
    }

    public function getPdfUrl(): ?string
    {
        return $this->pdfUrl;
    }

    public function setPdfUrl(?string $pdfUrl): static
    {
        $this->pdfUrl = $pdfUrl;
        return $this;
    }

    public function getTresorerie(): ?Tresorerie
    {
        return $this->tresorerie;
    }

    public function setTresorerie(?Tresorerie $tresorerie): static
    {
        $this->tresorerie = $tresorerie;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function isRevenu(): bool
    {
        return $this->type === 'revenu';
    }

    public function isDepense(): bool
    {
        return $this->type === 'depense';
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getDateOperation(): ?\DateTimeInterface
    {
        return $this->dateOperation;
    }

    public function setDateOperation(\DateTimeInterface $dateOperation): static
    {
        $this->dateOperation = $dateOperation;
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

    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(OperationNote $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setOperation($this);
        }
        return $this;
    }

    public function removeNote(OperationNote $note): static
    {
        if ($this->notes->removeElement($note)) {
            if ($note->getOperation() === $this) {
                $note->setOperation(null);
            }
        }
        return $this;
    }

    public function getReferenceFormatted(): string
    {
        return 'OP-' . str_pad((string) $this->idOperation, 5, '0', STR_PAD_LEFT);
    }
}
