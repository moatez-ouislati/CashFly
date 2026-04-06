<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // ✅ FIX: string instead of int + exact validation
    #[ORM\Column(type: 'string', length: 8)]
    #[Assert\NotBlank(message: 'Le CIN est obligatoire.')]
    #[Assert\Regex(pattern: '/^[0-9]{8}$/', message: 'Le CIN doit contenir exactement 8 chiffres.')]
    private ?string $cin = null;

    #[ORM\Column(type: 'string', length: 8)]
    #[Assert\NotBlank(message: 'Le téléphone est obligatoire.')]
    #[Assert\Regex(pattern: '/^[0-9]{8}$/', message: 'Le téléphone doit contenir exactement 8 chiffres.')]
    private ?string $tel = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Regex(pattern: '/^[A-Za-zÀ-ÿ]+$/', message: 'Le nom ne doit contenir que des lettres.')]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire.')]
    #[Assert\Regex(pattern: '/^[A-Za-zÀ-ÿ]+$/', message: 'Le prénom ne doit contenir que des lettres.')]
    private ?string $prenom = null;

    // ✅ keep case-sensitive email
    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ['collation' => 'utf8mb4_bin'])]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'adresse email n'est pas valide.")]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password = '';

    #[Assert\NotBlank(message: 'Le mot de passe est obligatoire.', groups: ['registration'])]
    #[Assert\Length(min: 6, minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.', groups: ['registration'])]
    private ?string $plainPassword = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    // ✅ FIXED mapping (camelCase → DB exact)
    #[ORM\Column(name: "yearsExperience", type: "string", length: 2, nullable: true)]
    private ?string $yearsExperience = null;

    #[ORM\Column(name: "highestProfit", type: "string", length: 10, nullable: true)]
    private ?string $highestProfit = null;

    #[ORM\Column(name: "budget", type: "string", length: 50, nullable: true)]
    private ?string $budget = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $active = true;

    // ✅ FIXED mapping
    #[ORM\Column(name: "face_image", type: "string", length: 255, nullable: true)]
    private ?string $faceImage = null;

    // ── Getters & Setters ─────────────────────────────────

    public function getId(): ?int { return $this->id; }

    public function getCin(): ?string { return $this->cin; }
    public function setCin(?string $cin): static { $this->cin = $cin; return $this; }

    public function getTel(): ?string { return $this->tel; }
    public function setTel(?string $tel): static { $this->tel = $tel; return $this; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(?string $nom): static { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(?string $prenom): static { $this->prenom = $prenom; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): static { $this->email = $email; return $this; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }

    public function getPlainPassword(): ?string { return $this->plainPassword; }
    public function setPlainPassword(?string $plainPassword): static { $this->plainPassword = $plainPassword; return $this; }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static { $this->roles = $roles; return $this; }

    public function getYearsExperience(): ?string { return $this->yearsExperience; }
    public function setYearsExperience(?string $yearsExperience): static { $this->yearsExperience = $yearsExperience; return $this; }

    public function getHighestProfit(): ?string { return $this->highestProfit; }
    public function setHighestProfit(?string $highestProfit): static { $this->highestProfit = $highestProfit; return $this; }

    public function getBudget(): ?string { return $this->budget; }
    public function setBudget(?string $budget): static { $this->budget = $budget; return $this; }

    public function isActive(): bool { return $this->active; }
    public function setActive(bool $active): static { $this->active = $active; return $this; }

    public function getFaceImage(): ?string { return $this->faceImage; }
    public function setFaceImage(?string $faceImage): static { $this->faceImage = $faceImage; return $this; }

    // ── Security ─────────────────────────────────

    public function getUserIdentifier(): string { return (string) $this->email; }

    public function eraseCredentials(): void { $this->plainPassword = null; }

    public function getPrimaryRole(): string
    {
        if (in_array('ROLE_ADMIN', $this->roles)) return 'ROLE_ADMIN';
        if (in_array('ROLE_PROPRIETAIRE', $this->roles)) return 'ROLE_PROPRIETAIRE';
        return 'ROLE_INVESTISSEUR';
    }

    public function getFullName(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }
}