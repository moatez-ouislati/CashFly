<?php

namespace App\Entity;

use App\Repository\ParticipationJpoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticipationJpoRepository::class)]
#[ORM\Table(name: 'participation_jpo')]
class ParticipationJpo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_participation')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: JourneePorteOuverte::class)]
    #[ORM\JoinColumn(name: 'id_evenement', referencedColumnName: 'id_evenement', nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull(message: 'Veuillez sélectionner un événement')]
    private ?JourneePorteOuverte $evenement = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class)]
    #[ORM\JoinColumn(name: 'id_entreprise', referencedColumnName: 'id_entreprise', nullable: true)]
    private ?Entreprise $entreprise = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_utilisateur', nullable: false)]
    #[Assert\NotNull(message: 'L\'utilisateur est obligatoire')]
    private ?User $utilisateur = null;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank(message: 'Le rôle est obligatoire')]
    #[Assert\Choice(choices: ['VISITEUR', 'ENTREPRISE', 'INVESTISSEUR'], message: 'Rôle invalide')]
    private ?string $role = null;

    #[ORM\Column(length: 20, options: ['default' => 'EN_ATTENTE'])]
    #[Assert\Choice(choices: ['EN_ATTENTE', 'CONFIRME', 'ANNULE'], message: 'Statut invalide')]
    private ?string $statut = 'EN_ATTENTE';

    #[ORM\Column(name: 'date_inscription', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\Column(name: 'badge_genere', type: Types::BOOLEAN, options: ['default' => 0])]
    private ?bool $badgeGenere = false;

    public function __construct()
    {
        $this->dateInscription = new \DateTime();
        $this->badgeGenere = false;
        $this->statut = 'en_attente';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvenement(): ?JourneePorteOuverte
    {
        return $this->evenement;
    }

    public function setEvenement(?JourneePorteOuverte $evenement): self
    {
        $this->evenement = $evenement;
        return $this;
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;
        return $this;
    }

    public function isBadgeGenere(): ?bool
    {
        return $this->badgeGenere;
    }

    public function setBadgeGenere(bool $badgeGenere): self
    {
        $this->badgeGenere = $badgeGenere;
        return $this;
    }
}
