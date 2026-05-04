<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'utilisateurs', indexes: [new ORM\Index(name: 'email', columns: ['email'])])]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_utilisateur', type: 'integer')]
    private ?int $id = null;

    // ✅ FIX: string instead of int + exact validation
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\NotBlank(message: 'Le CIN est obligatoire.')]
    #[Assert\Regex(pattern: '/^[0-9]{8}$/', message: 'Le CIN doit contenir exactement 8 chiffres.')]
    private ?int $cin = null;

    #[ORM\Column(type: 'string', length: 11, nullable: true)]
    #[Assert\NotBlank(message: 'Le téléphone est obligatoire.')]
    #[Assert\Regex(pattern: '/^[0-9]{8}$/', message: 'Le téléphone doit contenir exactement 8 chiffres.')]
    private ?string $tel = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Regex(pattern: '/^[A-Za-zÀ-ÿ]+$/', message: 'Le nom ne doit contenir que des lettres.')]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire.')]
    #[Assert\Regex(pattern: '/^[A-Za-zÀ-ÿ]+$/', message: 'Le prénom ne doit contenir que des lettres.')]
    private ?string $prenom = null;

    // ✅ keep case-sensitive email
    #[ORM\Column(type: 'string', length: 100, nullable: false)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'adresse email n'est pas valide.")]
    private ?string $email = null;

    #[ORM\Column(name: 'mot_de_passe', type: 'string', length: 255)]
    private string $password = '';

    #[Assert\NotBlank(message: 'Le mot de passe est obligatoire.', groups: ['registration'])]
    #[Assert\Length(min: 6, minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.', groups: ['registration'])]
    private ?string $plainPassword = null;

    #[ORM\Column(name: 'role', type: 'string', columnDefinition: "ENUM('proprietaire','investisseur','administrateur') NOT NULL")]
    private string $roles = 'investisseur';

    #[ORM\Column(name: 'date_creation', type: 'datetime', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $dateCreation = null;

    // ✅ FIXED mapping (camelCase → DB exact)
    #[ORM\Column(name: "yearsExperience", type: "string", length: 2, nullable: true)]
    private ?string $yearsExperience = null;

    #[ORM\Column(name: "highestProfit", type: "string", length: 10, nullable: true)]
    private ?string $highestProfit = null;

    #[ORM\Column(name: "budget", type: "string", length: 50, nullable: true)]
    private ?string $budget = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['default' => true])]
    private ?bool $active = true;

    // ✅ FIXED mapping
    #[ORM\Column(name: "face_image", type: "string", length: 255, nullable: true)]
    private ?string $faceImage = null;

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

    // ── Getters & Setters ─────────────────────────────────

    public function getId(): ?int { return $this->id; }

    public function getCin(): ?int { return $this->cin; }
    public function setCin(?int $cin): static { $this->cin = $cin; return $this; }

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
        $rawRole = strtoupper($this->roles);
        $role = $rawRole === 'ADMINISTRATEUR' ? 'ROLE_ADMIN' : 'ROLE_' . $rawRole;
        return [$role];
    }

    public function setRoles(string|array $roles): static 
    { 
        if (is_array($roles)) {
            // Take the first role from the array (e.g. ROLE_PROPRIETAIRE -> proprietaire)
            $role = !empty($roles) ? $roles[0] : 'investisseur';
            $role = str_replace('ROLE_', '', strtoupper($role));
            $this->roles = strtolower($role === 'ADMIN' ? 'administrateur' : $role);
        } else {
            $this->roles = $roles;
        }
        
        return $this; 
    }

    public function getDateCreation(): ?\DateTimeInterface { return $this->dateCreation; }
    public function setDateCreation(?\DateTimeInterface $dateCreation): static { $this->dateCreation = $dateCreation; return $this; }

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

    /**
     * @return Collection<int, Entreprise>
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprise $entreprise): static
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises->add($entreprise);
            $entreprise->setProprietaire($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): static
    {
        if ($this->entreprises->removeElement($entreprise)) {
            // set the owning side to null (unless already changed)
            if ($entreprise->getProprietaire() === $this) {
                $entreprise->setProprietaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Investissement>
     */
    public function getInvestissements(): Collection
    {
        return $this->investissements;
    }

    public function addInvestissement(Investissement $investissement): static
    {
        if (!$this->investissements->contains($investissement)) {
            $this->investissements->add($investissement);
            $investissement->setInvestisseur($this);
        }

        return $this;
    }

    public function removeInvestissement(Investissement $investissement): static
    {
        if ($this->investissements->removeElement($investissement)) {
            // set the owning side to null (unless already changed)
            if ($investissement->getInvestisseur() === $this) {
                $investissement->setInvestisseur(null);
            }
        }

        return $this;
    }

    // ── Security ─────────────────────────────────

    public function getUserIdentifier(): string { return (string) $this->email; }

    public function eraseCredentials(): void { $this->plainPassword = null; }

    public function getPrimaryRole(): string
    {
        $rawRole = strtoupper($this->roles);
        return $rawRole === 'ADMINISTRATEUR' ? 'ROLE_ADMIN' : 'ROLE_' . $rawRole;
    }

    public function getFullName(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function getDbRole(): string
    {
        return $this->roles;
    }

    public function needsInvestorProfileCompletion(): bool
    {
        if ($this->getPrimaryRole() !== 'ROLE_INVESTISSEUR') {
            return false;
        }

        return empty($this->budget) || empty($this->yearsExperience) || empty($this->highestProfit);
    }
}
