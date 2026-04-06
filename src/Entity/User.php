<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Entity User
 * 
 * Cette entité gère les utilisateurs de la plateforme CashFly.
 * Elle implémente UserInterface et PasswordAuthenticatedUserInterface pour la sécurité Symfony.
 * 
 * Maintenance :
 * - Pour ajouter un champ, utilisez 'php bin/console make:entity User'
 * - Les contraintes de validation (Assert) sont cruciales pour la sécurité des données.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'utilisateurs')]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EquatableInterface
{
    public function isEqualTo(UserInterface $user): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        if (!$this->active) {
            throw new CustomUserMessageAuthenticationException('Votre compte est inactif. Veuillez contacter l\'administrateur.');
        }

        return true;
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_utilisateur')]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Assert\NotBlank(message: 'L\'email est obligatoire')]
    #[Assert\Email(message: 'L\'email {{ value }} n\'est pas un email valide.')]
    #[Assert\Length(max: 100, maxMessage: 'L\'email ne peut pas dépasser 100 caractères')]
    private ?string $email = null;

    #[ORM\Column(name: 'mot_de_passe', length: 255)]
    private ?string $password = null;

    #[ORM\Column(name: 'role', type: 'string', length: 50)]
    #[Assert\NotBlank(message: 'Le rôle est obligatoire')]
    #[Assert\Choice(choices: ['administrateur', 'proprietaire', 'investisseur'], message: 'Rôle invalide')]
    private ?string $dbRole = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type(type: 'integer', message: 'Le CIN doit être un nombre')]
    #[Assert\Regex(pattern: '/^[0-9]{8}$/', message: 'Le CIN doit comporter exactement 8 chiffres')]
    private ?int $cin = null;

    #[ORM\Column(length: 11, nullable: true)]
    #[Assert\Regex(pattern: '/^[0-9]{8,11}$/', message: 'Le numéro de téléphone doit comporter entre 8 et 11 chiffres')]
    private ?string $tel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Assert\Length(min: 2, max: 50, minMessage: 'Le nom doit faire au moins {{ limit }} caractères')]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ\s\-]+$/',
        message: 'Le nom ne peut contenir que des lettres'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    #[Assert\Length(min: 2, max: 50, minMessage: 'Le prénom doit faire au moins {{ limit }} caractères')]
    #[Assert\Regex(
        pattern: '/^[a-zA-ZÀ-ÿ\s\-]+$/',
        message: 'Le prénom ne peut contenir que des lettres'
    )]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(name: 'yearsExperience', length: 2, nullable: true)]
    private ?string $yearsExperience = null;

    #[ORM\Column(name: 'highestProfit', length: 10, nullable: true)]
    private ?string $highestProfit = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $budget = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => 1])]
    private ?bool $active = true;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $face_image = null;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = [];
        
        // Map DB roles to Symfony roles
        switch ($this->dbRole) {
            case 'administrateur':
                $roles[] = 'ROLE_ADMIN';
                break;
            case 'proprietaire':
                $roles[] = 'ROLE_PROPRIETAIRE';
                break;
            case 'investisseur':
                $roles[] = 'ROLE_INVESTISSEUR';
                break;
            default:
                $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setDbRole(string $role): static
    {
        $this->dbRole = $role;
        return $this;
    }

    public function getDbRole(): ?string
    {
        return $this->dbRole;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(?int $cin): self
    {
        $this->cin = $cin;
        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;
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

    public function getYearsExperience(): ?string
    {
        return $this->yearsExperience;
    }

    public function setYearsExperience(?string $yearsExperience): self
    {
        $this->yearsExperience = $yearsExperience;
        return $this;
    }

    public function getHighestProfit(): ?string
    {
        return $this->highestProfit;
    }

    public function setHighestProfit(?string $highestProfit): self
    {
        $this->highestProfit = $highestProfit;
        return $this;
    }

    public function getBudget(): ?string
    {
        return $this->budget;
    }

    public function setBudget(?string $budget): self
    {
        $this->budget = $budget;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function getFaceImage(): ?string
    {
        return $this->face_image;
    }

    public function setFaceImage(?string $face_image): self
    {
        $this->face_image = $face_image;
        return $this;
    }

    /**
     * Vérifie si le profil de l'utilisateur est complet selon son rôle.
     */
    public function isProfileComplete(): bool
    {
        if (empty($this->nom) || empty($this->prenom) || empty($this->tel) || empty($this->cin)) {
            return false;
        }

        if ($this->dbRole === 'investisseur') {
            return !empty($this->yearsExperience) && !empty($this->highestProfit) && !empty($this->budget);
        }

        return true;
    }
}
