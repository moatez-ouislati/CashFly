<?php

namespace App\Entity;

use App\Repository\TresorerieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TresorerieRepository::class)]
#[ORM\Table(name: 'trésorerie')]
class Tresorerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_tresorerie')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class)]
    #[ORM\JoinColumn(name: 'id_entreprise', referencedColumnName: 'id_entreprise', nullable: false)]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(name: 'nom_compte', length: 100)]
    private ?string $nom_compte = null;

    #[ORM\Column(name: 'type_compte', type: 'string', length: 20)]
    private ?string $type_compte = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, options: ['default' => '0.00'])]
    private ?string $solde = null;

    #[ORM\Column(length: 3, options: ['default' => 'EUR'])]
    private ?string $devise = 'EUR';

    #[ORM\Column(name: 'derniere_maj', type: Types::DATETIME_MUTABLE, nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $derniere_maj = null;

    #[ORM\Column(length: 34, nullable: true)]
    private ?string $rib = null;

    #[ORM\Column(name: 'numero_compte', length: 30, nullable: true, unique: true)]
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

    public function getDevise(): ?string
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
}
