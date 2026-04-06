<?php

namespace App\Entity;

use App\Repository\JourneePorteOuverteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity JourneePorteOuverte
 * 
 * Événements organisés par les entreprises pour attirer des investisseurs.
 * 
 * Maintenance :
 * - La date doit être future ou présente.
 * - Le nombre de participants est limité par max_participants.
 */
#[ORM\Entity(repositoryClass: JourneePorteOuverteRepository::class)]
#[ORM\Table(name: 'journées_portes_ouvertes')]
class JourneePorteOuverte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_evenement')]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire')]
    #[Assert\Length(min: 5, max: 200, minMessage: 'Le titre doit faire au moins {{ limit }} caractères')]
    private ?string $titre = null;

    #[ORM\Column(name: 'date_evenement', type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: 'La date est obligatoire')]
    #[Assert\GreaterThanOrEqual('today', message: 'La date ne peut pas être dans le passé')]
    private ?\DateTimeInterface $dateEvenement = null;

    #[ORM\Column(length: 200, nullable: true)]
    #[Assert\NotBlank(message: 'Le lieu est obligatoire')]
    #[Assert\Length(min: 3, max: 200, minMessage: 'Le lieu doit faire au moins {{ limit }} caractères')]
    private ?string $lieu = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 3000, maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères')]
    private ?string $description = null;

    #[ORM\Column(name: 'image_path', length: 500, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\Column(name: 'max_participants', options: ['default' => 50])]
    #[Assert\NotNull(message: 'Le nombre maximum est obligatoire')]
    #[Assert\Positive(message: 'Le nombre maximum doit être positif')]
    #[Assert\Range(min: 1, max: 1000, notInRangeMessage: 'Le nombre de participants doit être entre 1 et 1000')]
    private ?int $maxParticipants = 50;

    #[ORM\Column(name: 'current_participants', options: ['default' => 0])]
    #[Assert\PositiveOrZero(message: 'Le nombre actuel doit être positif ou nul')]
    private ?int $currentParticipants = 0;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_createur', referencedColumnName: 'id_utilisateur', nullable: true)]
    private ?User $createur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDateEvenement(): ?\DateTimeInterface
    {
        return $this->dateEvenement;
    }

    public function setDateEvenement(\DateTimeInterface $dateEvenement): self
    {
        $this->dateEvenement = $dateEvenement;
        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;
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

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;
        return $this;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(int $maxParticipants): self
    {
        $this->maxParticipants = $maxParticipants;
        return $this;
    }

    public function getCurrentParticipants(): ?int
    {
        return $this->currentParticipants;
    }

    public function setCurrentParticipants(int $currentParticipants): self
    {
        $this->currentParticipants = $currentParticipants;
        return $this;
    }

    public function getCreateur(): ?User
    {
        return $this->createur;
    }

    public function setCreateur(?User $createur): self
    {
        $this->createur = $createur;
        return $this;
    }
}
