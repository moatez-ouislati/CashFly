<?php

namespace App\Entity;

use App\Repository\JourneePorteOuverteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JourneePorteOuverteRepository::class)]
#[ORM\Table(name: 'journées_portes_ouvertes')]
class JourneePorteOuverte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_evenement', type: Types::INTEGER)]
    private ?int $idEvenement = null;

    #[ORM\Column(name: 'titre', length: 200)]
    private string $titre;

    #[ORM\Column(name: 'date_evenement', type: Types::DATE_MUTABLE)]
    private \DateTimeInterface $dateEvenement;

    #[ORM\Column(name: 'lieu', length: 200, nullable: true)]
    private ?string $lieu = null;

    #[ORM\Column(name: 'description', type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'image_path', length: 500, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\Column(name: 'max_participants', type: Types::INTEGER, options: ['default' => 50])]
    private int $maxParticipants = 50;

    #[ORM\Column(name: 'current_participants', type: Types::INTEGER, options: ['default' => 0])]
    private int $currentParticipants = 0;

    #[ORM\Column(name: 'id_createur', type: Types::INTEGER, nullable: true)]
    private ?int $idCreateur = null;

    #[ORM\Column(name: 'latitude', type: Types::FLOAT, nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(name: 'longitude', type: Types::FLOAT, nullable: true)]
    private ?float $longitude = null;

    public function getIdEvenement(): ?int { return $this->idEvenement; }

    public function getTitre(): string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }

    public function getDateEvenement(): \DateTimeInterface { return $this->dateEvenement; }
    public function setDateEvenement(\DateTimeInterface $dateEvenement): static { $this->dateEvenement = $dateEvenement; return $this; }

    public function getLieu(): ?string { return $this->lieu; }
    public function setLieu(?string $lieu): static { $this->lieu = $lieu; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }

    public function getImagePath(): ?string { return $this->imagePath; }
    public function setImagePath(?string $imagePath): static { $this->imagePath = $imagePath; return $this; }

    public function getMaxParticipants(): int { return $this->maxParticipants; }
    public function setMaxParticipants(int $maxParticipants): static { $this->maxParticipants = $maxParticipants; return $this; }

    public function getCurrentParticipants(): int { return $this->currentParticipants; }
    public function setCurrentParticipants(int $currentParticipants): static { $this->currentParticipants = $currentParticipants; return $this; }

    public function getIdCreateur(): ?int { return $this->idCreateur; }
    public function setIdCreateur(?int $idCreateur): static { $this->idCreateur = $idCreateur; return $this; }

    public function getLatitude(): ?float { return $this->latitude; }
    public function setLatitude(?float $latitude): static { $this->latitude = $latitude; return $this; }

    public function getLongitude(): ?float { return $this->longitude; }
    public function setLongitude(?float $longitude): static { $this->longitude = $longitude; return $this; }

    public function isFull(): bool
    {
        return $this->currentParticipants >= $this->maxParticipants;
    }
}
