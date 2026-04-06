<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\Table(name: 'documents')]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_document')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: 'documents')]
    #[ORM\JoinColumn(name: 'id_entreprise', referencedColumnName: 'id_entreprise', nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez sélectionner une entreprise.')]
    private ?Entreprise $entreprise = null;

    // Rempli automatiquement, pas par l'utilisateur
    #[ORM\Column(name: 'id_utilisateur', nullable: true)]
    private ?int $idUtilisateur = null;

    #[ORM\Column(name: 'nom_document', length: 255)]
    #[Assert\NotBlank(message: 'Le nom du document est obligatoire.')]
    #[Assert\Length(
        min: 4,
        max: 255,
        minMessage: 'Le nom du document doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $nomDocument = null;

    #[ORM\Column(name: 'type_document', length: 50, nullable: true)]
    #[Assert\NotBlank(message: 'Le type de document est obligatoire.')]
    #[Assert\Length(
        max: 50,
        maxMessage: 'Le type de document ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $typeDocument = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\NotBlank(message: 'Le statut est obligatoire.')]
    #[Assert\Choice(
        choices: ['en_attente', 'validé', 'rejeté'],
        message: 'Le statut doit être : en_attente, validé ou rejeté.'
    )]
    private ?string $statut = null;

    // Optionnel — chemin généré automatiquement lors de l'upload
    #[ORM\Column(name: 'chemin_fichier', length: 255, nullable: true)]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le chemin du fichier ne peut pas dépasser {{ limit }} caractères.'
    )]
    private ?string $cheminFichier = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: 'La description est obligatoire.')]
    #[Assert\Length(
        min: 10,
        minMessage: 'La description doit contenir au moins {{ limit }} caractères.'
    )]
    private ?string $description = null;

    // Optionnel — généré automatiquement par OCR
    #[ORM\Column(name: 'texte_ocr', type: Types::TEXT, nullable: true)]
    private ?string $texteOcr = null;

    // Auto-initialisé dans __construct, NotNull suffit
    #[ORM\Column(name: 'date_upload', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Assert\NotNull(message: 'La date d\'upload est obligatoire.')]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeInterface $dateUpload = null;

    public function __construct()
    {
        $this->dateUpload = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?int $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getTexteOcr(): ?string
    {
        return $this->texteOcr;
    }

    public function setTexteOcr(?string $texteOcr): static
    {
        $this->texteOcr = $texteOcr;
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
}