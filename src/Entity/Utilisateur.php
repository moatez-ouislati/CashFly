<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: "utilisateurs")]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_utilisateur", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "cin", type: "integer", nullable: true)]
    private ?int $cin = null;

    #[ORM\Column(name: "tel", length: 25, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(name: "nom", length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(name: "prenom", length: 100, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(name: "nom_complet", length: 100)]
    private ?string $nomComplet = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $email = null;

    #[ORM\Column(name: "mot_de_passe", length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $role = null;

    #[ORM\Column(name: "face_image", length: 255, nullable: true)]
    private ?string $faceImage = null;

    #[ORM\Column(name: "yearsExperience", length: 100, nullable: true)]
    private ?string $yearsExperience = null;

    #[ORM\Column(name: "highestProfit", length: 100, nullable: true)]
    private ?string $highestProfit = null;

    #[ORM\Column(name: "budget", length: 100, nullable: true)]
    private ?string $budget = null;

    #[ORM\Column(type: "integer", options: ["default" => 1])]
    private int $active = 1;

    #[ORM\Column(name: "date_creation", type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTimeInterface $dateCreation;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }

    public function getCin(): ?int { return $this->cin; }
    public function setCin(?int $cin): self { $this->cin = $cin; return $this; }

    public function getTel(): ?string { return $this->tel; }
    public function setTel(?string $tel): self { $this->tel = $tel; return $this; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(?string $nom): self { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(?string $prenom): self { $this->prenom = $prenom; return $this; }

    public function getNomComplet(): ?string { return $this->nomComplet; }
    public function setNomComplet(string $nomComplet): self { $this->nomComplet = $nomComplet; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }

    public function getRole(): ?string { return $this->role; }
    public function setRole(string $role): self { $this->role = $role; return $this; }

    public function getFaceImage(): ?string { return $this->faceImage; }
    public function setFaceImage(?string $faceImage): self { $this->faceImage = $faceImage; return $this; }

    public function getYearsExperience(): ?string { return $this->yearsExperience; }
    public function setYearsExperience(?string $yearsExperience): self { $this->yearsExperience = $yearsExperience; return $this; }

    public function getHighestProfit(): ?string { return $this->highestProfit; }
    public function setHighestProfit(?string $highestProfit): self { $this->highestProfit = $highestProfit; return $this; }

    public function getBudget(): ?string { return $this->budget; }
    public function setBudget(?string $budget): self { $this->budget = $budget; return $this; }

    public function getActive(): int { return $this->active; }
    public function setActive(int $active): self { $this->active = $active; return $this; }

    public function getDateCreation(): \DateTimeInterface { return $this->dateCreation; }
    public function setDateCreation(\DateTimeInterface $dateCreation): self { $this->dateCreation = $dateCreation; return $this; }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = [];
        switch ($this->role) {
            case 'proprietaire': $roles[] = 'ROLE_PROPRIETAIRE'; break;
            case 'investisseur': $roles[] = 'ROLE_INVESTISSEUR'; break;
            case 'administrateur': $roles[] = 'ROLE_ADMIN'; break;
        }
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
    }
}
