<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateurs')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_utilisateur')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $cin = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 150, unique: true)]
    private ?string $email = null;

    #[ORM\Column(name: 'mot_de_passe', length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $role = 'investisseur';

    #[ORM\Column(name: 'date_creation', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(name: 'yearsExperience', length: 50, nullable: true)]
    private ?string $yearsExperience = null;

    #[ORM\Column(name: 'highestProfit', length: 50, nullable: true)]
    private ?string $highestProfit = null;

    #[ORM\Column(name: 'budget', length: 50, nullable: true)]
    private ?string $budget = null;

    #[ORM\Column(nullable: true)]
    private ?int $active = 1;

    #[ORM\Column(name: 'face_image', length: 255, nullable: true)]
    private ?string $faceImage = null;

    #[ORM\OneToOne(targetEntity: UserKyc::class, mappedBy: 'utilisateur')]
    private ?UserKyc $userKyc = null;

    #[ORM\OneToMany(targetEntity: Entreprise::class, mappedBy: 'proprietaire')]
    private Collection $entreprises;

    #[ORM\OneToMany(targetEntity: Investissement::class, mappedBy: 'investisseur')]
    private Collection $investissements;

    public function __construct()
    {
        $this->entreprises = new ArrayCollection();
        $this->investissements = new ArrayCollection();
        $this->dateCreation = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(?int $cin): static
    {
        $this->cin = $cin;
        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): static
    {
        $this->tel = $tel;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->password;
    }

    public function setMotDePasse(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        if ($this->role) {
            $roles[] = 'ROLE_' . strtoupper($this->role);
        }
        return array_unique($roles);
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function isAdmin(): bool
    {
        return str_contains($this->role ?? '', 'administrateur');
    }

    public function isInvestisseur(): bool
    {
        return str_contains($this->role ?? '', 'investisseur');
    }

    public function isProprietaire(): bool
    {
        return str_contains($this->role ?? '', 'proprietaire');
    }

    public function getYearsExperience(): ?string
    {
        return $this->yearsExperience;
    }

    public function setYearsExperience(?string $yearsExperience): static
    {
        $this->yearsExperience = $yearsExperience;
        return $this;
    }

    public function getHighestProfit(): ?string
    {
        return $this->highestProfit;
    }

    public function setHighestProfit(?string $highestProfit): static
    {
        $this->highestProfit = $highestProfit;
        return $this;
    }

    public function getBudget(): ?string
    {
        return $this->budget;
    }

    public function setBudget(?string $budget): static
    {
        $this->budget = $budget;
        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): static
    {
        $this->active = $active;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active === 1;
    }

    public function getFaceImage(): ?string
    {
        return $this->faceImage;
    }

    public function setFaceImage(?string $faceImage): static
    {
        $this->faceImage = $faceImage;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getUserKyc(): ?UserKyc
    {
        return $this->userKyc;
    }

    public function setUserKyc(?UserKyc $userKyc): static
    {
        $this->userKyc = $userKyc;
        return $this;
    }

    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function getInvestissements(): Collection
    {
        return $this->investissements;
    }

    public function getFullName(): string
    {
        $prenom = $this->prenom ?? '';
        $nom = $this->nom ?? '';
        $fullName = trim($prenom . ' ' . $nom);
        return $fullName ?: ($this->email ?? 'User');
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {
    }

    public function verifyPassword(string $plainPassword): bool
    {
        $hashedPassword = $this->password;
        
        if (str_starts_with($hashedPassword, '$2')) {
            return password_verify($plainPassword, $hashedPassword);
        }
        
        return $hashedPassword === $plainPassword;
    }
}
