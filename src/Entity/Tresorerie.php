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
    private ?int $idTresorerie = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: 'tresoreries')]
    #[ORM\JoinColumn(name: 'id_entreprise', referencedColumnName: 'id_entreprise', nullable: false)]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(name: 'nom_compte', length: 100, nullable: true)]
    private ?string $nomCompte = null;

    #[ORM\Column(name: 'type_compte', length: 50, nullable: true)]
    private ?string $typeCompte = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private ?string $solde = '0.00';

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $devise = 'EUR';

    #[ORM\Column(name: 'derniere_maj', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $derniereMaj = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $rib = null;

    #[ORM\Column(name: 'numero_compte', length: 50, nullable: true)]
    private ?string $numeroCompte = null;

    public function __construct()
    {
        $this->derniereMaj = new \DateTime();
    }

    public function getIdTresorerie(): ?int
    {
        return $this->idTresorerie;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;
        return $this;
    }

    public function getNomCompte(): ?string
    {
        return $this->nomCompte;
    }

    public function setNomCompte(?string $nomCompte): static
    {
        $this->nomCompte = $nomCompte;
        return $this;
    }

    public function getTypeCompte(): ?string
    {
        return $this->typeCompte;
    }

    public function setTypeCompte(?string $typeCompte): static
    {
        $this->typeCompte = $typeCompte;
        return $this;
    }

    public function getSolde(): ?string
    {
        return $this->solde;
    }

    public function getSoldeFloat(): float
    {
        return (float) ($this->solde ?? 0);
    }

    public function setSolde(string $solde): static
    {
        $this->solde = $solde;
        $this->derniereMaj = new \DateTime();
        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(?string $devise): static
    {
        $this->devise = $devise;
        return $this;
    }

    public function getDerniereMaj(): ?\DateTimeInterface
    {
        return $this->derniereMaj;
    }

    public function setDerniereMaj(\DateTimeInterface $derniereMaj): static
    {
        $this->derniereMaj = $derniereMaj;
        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(?string $rib): static
    {
        $this->rib = $rib;
        return $this;
    }

    public function getNumeroCompte(): ?string
    {
        return $this->numeroCompte;
    }

    public function setNumeroCompte(?string $numeroCompte): static
    {
        $this->numeroCompte = $numeroCompte;
        return $this;
    }
}
