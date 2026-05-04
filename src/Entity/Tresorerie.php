<?php

namespace App\Entity;

use App\Repository\TresorerieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Entity Tresorerie
 * 
 * Gère les comptes bancaires, caisses, etc. d'une entreprise.
 * 
 * Maintenance :
 * - Le champ 'solde' est mis à jour lors des opérations (Operation).
 * - Le RIB et Numéro de compte ont des validations Regex strictes.
 */
#[ORM\Entity(repositoryClass: TresorerieRepository::class)]
#[ORM\Table(name: 'trésorerie')]
#[UniqueEntity(fields: ['numero_compte'], message: 'Ce numéro de compte existe déjà.')]
class Tresorerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_tresorerie')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class)]
    #[ORM\JoinColumn(name: 'id_entreprise', referencedColumnName: 'id_entreprise', nullable: false)]
    #[Assert\NotNull(message: 'Veuillez sélectionner une entreprise')]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(name: 'nom_compte', length: 100)]
    #[Assert\NotBlank(message: 'Le nom du compte est obligatoire')]
    #[Assert\Length(
        min: 3, 
        max: 100, 
        minMessage: 'Le nom doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $nom_compte = null;

    #[ORM\Column(name: 'type_compte', type: 'string', length: 20)]
    #[Assert\NotBlank(message: 'Le type de compte est obligatoire')]
    #[Assert\Choice(choices: ['CAISSE', 'BANQUE', 'CARTE', 'WALLET'], message: 'Type de compte invalide')]
    private ?string $type_compte = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, options: ['default' => '0.00'])]
    #[Assert\NotBlank(message: 'Le solde initial est obligatoire')]
    #[Assert\Type(type: 'numeric', message: 'Le solde doit être un nombre')]
    #[Assert\PositiveOrZero(message: 'Le solde ne peut pas être négatif')]
    private ?string $solde = null;

    #[ORM\Column(length: 3, options: ['default' => 'EUR'])]
    #[Assert\NotBlank(message: 'La devise est obligatoire')]
    #[Assert\Length(min: 3, max: 3, exactMessage: 'La devise doit comporter 3 caractères (ex: TND, EUR)')]
    private string $devise = 'EUR';

    #[ORM\Column(name: 'derniere_maj', type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $derniere_maj = null;

    #[ORM\Column(length: 34, nullable: true)]
    #[Assert\Regex(
        pattern: '/^[A-Z0-9]{15,34}$/',
        message: 'Le RIB/IBAN doit comporter entre 15 et 34 caractères (lettres majuscules et chiffres)'
    )]
    private ?string $rib = null;

    #[ORM\Column(name: 'numero_compte', length: 50, nullable: true, unique: true)]
    #[Assert\Regex(
        pattern: '/^[0-9]{5,20}$/',
        message: 'Le numéro de compte doit comporter entre 5 et 20 chiffres'
    )]
    private ?string $numero_compte = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomCompte(): ?string
    {
        return $this->nom_compte;
    }

    public function setNomCompte(string $nom_compte): self
    {
        $this->nom_compte = $nom_compte;
        return $this;
    }

    public function getTypeCompte(): ?string
    {
        return $this->type_compte;
    }

    public function setTypeCompte(string $type_compte): self
    {
        $this->type_compte = $type_compte;
        return $this;
    }

    public function getSolde(): ?string
    {
        return $this->solde;
    }

    public function setSolde(?string $solde): self
    {
        $this->solde = $solde;
        return $this;
    }

    public function getDevise(): string
    {
        return $this->devise;
    }

    public function setDevise(string $devise): self
    {
        $this->devise = $devise;
        return $this;
    }

    public function getDerniereMaj(): ?\DateTimeInterface
    {
        return $this->derniere_maj;
    }

    public function setDerniereMaj(?\DateTimeInterface $derniere_maj): self
    {
        $this->derniere_maj = $derniere_maj;
        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(?string $rib): self
    {
        $this->rib = $rib;
        return $this;
    }

    public function getNumeroCompte(): ?string
    {
        return $this->numero_compte;
    }

    public function setNumeroCompte(?string $numero_compte): self
    {
        $this->numero_compte = $numero_compte;
        return $this;
    }

    /**
     * Vérifie si le solde est en dessous du seuil critique (100 TND).
     */
    public function isSoldeCritique(): bool
    {
        return (float)$this->solde < 100;
    }
}
