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
    public const STATUT_BROUILLON = 'brouillon';
    public const STATUT_SOUMIS = 'soumis';
    public const STATUT_EN_REVISION = 'en_revision';
    public const STATUT_APPROUVE = 'approuve';
    public const STATUT_REJETE = 'rejete';

    public const STATUTS = [
        self::STATUT_BROUILLON => 'Brouillon',
        self::STATUT_SOUMIS => 'Soumis',
        self::STATUT_EN_REVISION => 'En révision',
        self::STATUT_APPROUVE => 'Approuvé',
        self::STATUT_REJETE => 'Rejeté',
    ];

    public const STATUTS_ACTION = [
        self::STATUT_BROUILLON => [self::STATUT_SOUMIS],
        self::STATUT_SOUMIS => [self::STATUT_APPROUVE, self::STATUT_REJETE, self::STATUT_EN_REVISION],
        self::STATUT_EN_REVISION => [self::STATUT_APPROUVE, self::STATUT_REJETE],
        self::STATUT_REJETE => [self::STATUT_SOUMIS],
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_document')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: 'documents')]
    #[ORM\JoinColumn(name: 'id_entreprise', referencedColumnName: 'id_entreprise', nullable: false)]
    #[Assert\NotBlank(message: 'Veuillez sélectionner une entreprise.')]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(name: 'id_utilisateur', nullable: true)]
    private ?int $idUtilisateur = null;

    #[ORM\Column(name: 'nom_document', length: 255)]
    #[Assert\NotBlank(message: 'Le nom du document est obligatoire.')]
    #[Assert\Length(min: 4, max: 255)]
    private ?string $nomDocument = null;

    #[ORM\Column(name: 'type_document', length: 50, nullable: true)]
    #[Assert\NotBlank(message: 'Le type de document est obligatoire.')]
    private ?string $typeDocument = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $statut = self::STATUT_BROUILLON;

    #[ORM\Column(name: 'chemin_fichier', length: 255, nullable: true)]
    private ?string $cheminFichier = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: 'La description est obligatoire.')]
    #[Assert\Length(min: 10)]
    private ?string $description = null;

    #[ORM\Column(name: 'texte_ocr', type: Types::TEXT, nullable: true)]
    private ?string $texteOcr = null;

    #[ORM\Column(name: 'date_upload', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $dateUpload = null;

    #[ORM\Column(name: 'commentaire', type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(name: 'id_validateur', nullable: true)]
    private ?int $idValidateur = null;

    #[ORM\Column(name: 'nom_validateur', length: 150, nullable: true)]
    private ?string $nomValidateur = null;

    #[ORM\Column(name: 'date_validation', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateValidation = null;

    #[ORM\Column(name: 'historique', type: Types::JSON, nullable: true)]
    private ?array $historique = [];

    public function __construct()
    {
        $this->dateUpload = new \DateTime();
        $this->statut = self::STATUT_BROUILLON;
        $this->historique = [];
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

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getIdValidateur(): ?int
    {
        return $this->idValidateur;
    }

    public function setIdValidateur(?int $idValidateur): static
    {
        $this->idValidateur = $idValidateur;
        return $this;
    }

    public function getNomValidateur(): ?string
    {
        return $this->nomValidateur;
    }

    public function setNomValidateur(?string $nomValidateur): static
    {
        $this->nomValidateur = $nomValidateur;
        return $this;
    }

    public function getDateValidation(): ?\DateTimeInterface
    {
        return $this->dateValidation;
    }

    public function setDateValidation(?\DateTimeInterface $dateValidation): static
    {
        $this->dateValidation = $dateValidation;
        return $this;
    }

    public function getHistorique(): ?array
    {
        return $this->historique;
    }

    public function setHistorique(?array $historique): static
    {
        $this->historique = $historique;
        return $this;
    }

    public function addToHistorique(string $action, ?string $details = null, ?string $user = null): static
    {
        $this->historique[] = [
            'date' => (new \DateTime())->format('d/m/Y H:i:s'),
            'action' => $action,
            'details' => $details,
            'user' => $user,
        ];
        return $this;
    }

    public function getStatutLabel(): string
    {
        if ($this->statut === null) {
            return 'Brouillon';
        }
        return self::STATUTS[$this->statut] ?? $this->statut ?? 'Inconnu';
    }

    public function getAvailableActions(): array
    {
        return self::STATUTS_ACTION[$this->statut] ?? [];
    }

    public function canTransitionTo(string $newStatut): bool
    {
        return in_array($newStatut, $this->getAvailableActions());
    }

    public function isApprouve(): bool
    {
        return $this->statut === self::STATUT_APPROUVE;
    }

    public function isRejete(): bool
    {
        return $this->statut === self::STATUT_REJETE;
    }

    public function isEnAttente(): bool
    {
        return in_array($this->statut, [self::STATUT_SOUMIS, self::STATUT_EN_REVISION]);
    }
}
