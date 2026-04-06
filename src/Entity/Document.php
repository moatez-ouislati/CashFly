<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity Document
 * 
 * Stocke les fichiers liés aux entreprises (factures, contrats, etc.).
 * 
 * Maintenance :
 * - Le champ 'chemin_fichier' contient le nom du fichier sur le serveur.
 * - Le champ 'texte_ocr' peut être utilisé pour la recherche plein texte.
 */
#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\Table(name: 'documents')]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_document')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class)]
    #[ORM\JoinColumn(name: 'id_entreprise', referencedColumnName: 'id_entreprise', nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull(message: 'Veuillez sélectionner une entreprise')]
    private ?Entreprise $entreprise = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_utilisateur', nullable: true)]
    private ?User $utilisateur = null;

    #[ORM\Column(name: 'nom_document', length: 255)]
    #[Assert\NotBlank(message: 'Le nom du document est obligatoire')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le nom doit faire au moins {{ limit }} caractères', maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères')]
    private ?string $nom = null;

    #[ORM\Column(name: 'type_document', length: 50, nullable: true)]
    #[Assert\NotBlank(message: 'Le type de document est obligatoire')]
    #[Assert\Choice(choices: ['pitch_deck', 'business_plan', 'statuts', 'contrat', 'facture', 'autre'], message: 'Type de document invalide')]
    private ?string $type = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Choice(choices: ['en_attente', 'valide', 'rejete'], message: 'Statut invalide')]
    private ?string $statut = 'en_attente';

    #[ORM\Column(name: 'chemin_fichier', length: 255, nullable: true)]
    private ?string $cheminFichier = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 1000, maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères')]
    private ?string $description = null;

    #[ORM\Column(name: 'texte_ocr', type: Types::TEXT, nullable: true)]
    private ?string $texteOcr = null;

    #[ORM\Column(name: 'date_upload', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $dateUpload = null;

    public function __construct()
    {
        $this->dateUpload = new \DateTime();
        $this->statut = 'en_attente';
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getCheminFichier(): ?string
    {
        return $this->cheminFichier;
    }

    public function setCheminFichier(?string $cheminFichier): self
    {
        $this->cheminFichier = $cheminFichier;
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

    public function getTexteOcr(): ?string
    {
        return $this->texteOcr;
    }

    public function setTexteOcr(?string $texteOcr): self
    {
        $this->texteOcr = $texteOcr;
        return $this;
    }

    public function getDateUpload(): ?\DateTimeInterface
    {
        return $this->dateUpload;
    }

    public function setDateUpload(\DateTimeInterface $dateUpload): self
    {
        $this->dateUpload = $dateUpload;
        return $this;
    }

    /**
     * Retourne le libellé lisible du type de document.
     */
    public function getTypeLabel(): string
    {
        return match($this->type) {
            'pitch_deck' => 'Pitch Deck',
            'business_plan' => 'Business Plan',
            'statuts' => 'Statuts',
            'contrat' => 'Contrat',
            'facture' => 'Facture',
            default => 'Autre'
        };
    }
}
