<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_document')]
    private ?int $idDocument = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class)]
    #[ORM\JoinColumn(name: 'id_entreprise', referencedColumnName: 'id_entreprise', nullable: false)]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(name: 'nom_document', length: 255)]
    private ?string $nomDocument = null;

    #[ORM\Column(name: 'type_document', length: 50, nullable: true)]
    private ?string $typeDocument = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(name: 'chemin_fichier', length: 255, nullable: true)]
    private ?string $cheminFichier = null;

    #[ORM\Column(name: 'date_upload', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateUpload = null;

    public function __construct()
    {
        $this->dateUpload = new \DateTime();
    }

    public function getIdDocument(): ?int
    {
        return $this->idDocument;
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

    public function getNomDocument(): ?string
    {
        return $this->nomDocument;
    }

    public function setNomDocument(string $nomDocument): static
    {
        $this->nomDocument = $nomDocument;
        return $this;
    }

    public function getTypeDocument(): ?string
    {
        return $this->typeDocument;
    }

    public function setTypeDocument(?string $typeDocument): static
    {
        $this->typeDocument = $typeDocument;
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

    public function getCheminFichier(): ?string
    {
        return $this->cheminFichier;
    }

    public function setCheminFichier(?string $cheminFichier): static
    {
        $this->cheminFichier = $cheminFichier;
        return $this;
    }

    public function getDateUpload(): ?\DateTimeInterface
    {
        return $this->dateUpload;
    }

    public function setDateUpload(\DateTimeInterface $dateUpload): static
    {
        $this->dateUpload = $dateUpload;
        return $this;
    }

    public function getStatutBadgeClass(): string
    {
        return match($this->statut) {
            'PENDING' => 'bg-warning',
            'APPROVED' => 'bg-success',
            'REJECTED' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
