<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'utilisateurs')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_utilisateur')]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $email = null;

    #[ORM\Column(name: 'mot_de_passe', length: 255)]
    private ?string $password = null;

    #[ORM\Column(name: 'role', type: 'string', length: 50)]
    private ?string $dbRole = null;

    #[ORM\Column(nullable: true)]
    private ?int $cin = null;

    #[ORM\Column(length: 11, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $date_creation = null;

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
        return $this->date_creation;
    }

    public function setDateCreation(?\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;
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
}
