<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity Entreprise
 * 
 * Représente une PME gérée par un propriétaire.
 * 
 * Maintenance :
 * - Les validations sont faites via Symfony Validator (Assert).
 * - Utilisée dans EntrepriseType pour les formulaires.
 */
#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
#[ORM\Table(name: 'entreprises')]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_entreprise')]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Le nom de l\'entreprise est obligatoire')]
    #[Assert\Length(min: 2, max: 150, minMessage: 'Le nom doit comporter au moins {{ limit }} caractères')]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\NotBlank(message: 'Le secteur d\'activité est obligatoire')]
    #[Assert\Length(min: 3, max: 100, minMessage: 'Le secteur doit faire au moins {{ limit }} caractères')]
    private ?string $secteur = null;

    #[ORM\Column(name: 'forme_juridique', length: 50, nullable: true)]
    #[Assert\NotBlank(message: 'La forme juridique est obligatoire')]
    #[Assert\Choice(choices: ['SARL', 'SA', 'SUARL', 'SNC'], message: 'Forme juridique invalide')]
    private ?string $forme_juridique = null;

    #[ORM\Column(name: 'date_creation', type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotNull(message: 'La date de création est obligatoire')]
    #[Assert\LessThanOrEqual('today', message: 'La date de création ne peut pas être dans le futur')]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, options: ['default' => '0.00'])]
    #[Assert\NotBlank(message: 'Le capital est obligatoire')]
    #[Assert\PositiveOrZero(message: 'Le capital doit être positif ou nul')]
    #[Assert\Type(type: 'numeric', message: 'Le capital doit être un nombre')]
    private ?string $capital = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_proprietaire', referencedColumnName: 'id_utilisateur', nullable: false)]
    private ?User $proprietaire = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: -90, max: 90, notInRangeMessage: 'Latitude invalide')]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: -180, max: 180, notInRangeMessage: 'Longitude invalide')]
    private ?float $longitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'L\'adresse ne peut pas dépasser {{ limit }} caractères')]
    private ?string $adresse = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(?string $secteur): self
    {
        $this->secteur = $secteur;
        return $this;
    }

    public function getFormeJuridique(): ?string
    {
        return $this->forme_juridique;
    }

    public function setFormeJuridique(?string $forme_juridique): self
    {
        $this->forme_juridique = $forme_juridique;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getCapital(): ?string
    {
        return $this->capital;
    }

    public function setCapital(?string $capital): self
    {
        $this->capital = $capital;
        return $this;
    }

    public function getProprietaire(): ?User
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?User $proprietaire): self
    {
        $this->proprietaire = $proprietaire;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * Calcule l'âge de l'entreprise en années.
     */
    public function getAge(): int
    {
        if (!$this->dateCreation) {
            return 0;
        }
        return $this->dateCreation->diff(new \DateTime())->y;
    }

    /**
     * Retourne un résumé formaté de l'entreprise.
     */
    public function getSummary(): string
    {
        return sprintf('%s (%s) - Créée le %s', $this->nom, $this->secteur, $this->dateCreation ? $this->dateCreation->format('d/m/Y') : 'N/A');
    }
}
