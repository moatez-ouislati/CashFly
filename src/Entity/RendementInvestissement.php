<?php

namespace App\Entity;

use App\Repository\RendementInvestissementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendementInvestissementRepository::class)]
#[ORM\Table(name: 'rendement_investissement')]
class RendementInvestissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idRendement = null;

    #[ORM\ManyToOne(targetEntity: Investissement::class, inversedBy: 'rendements')]
    #[ORM\JoinColumn(name: 'id_investissement', referencedColumnName: 'id_investissement', nullable: false)]
    private ?Investissement $investissement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCalcul = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, nullable: true)]
    private ?string $gain = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, nullable: true)]
    private ?string $perte = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private ?string $valeurPortefeuille = '0.00';

    public function __construct()
    {
        $this->dateCalcul = new \DateTime();
    }

    public function getIdRendement(): ?int
    {
        return $this->idRendement;
    }

    public function getId(): ?int
    {
        return $this->idRendement;
    }

    public function getInvestissement(): ?Investissement
    {
        return $this->investissement;
    }

    public function setInvestissement(?Investissement $investissement): static
    {
        $this->investissement = $investissement;
        return $this;
    }

    public function getDateCalcul(): ?\DateTimeInterface
    {
        return $this->dateCalcul;
    }

    public function setDateCalcul(\DateTimeInterface $dateCalcul): static
    {
        $this->dateCalcul = $dateCalcul;
        return $this;
    }

    public function getGain(): ?string
    {
        return $this->gain;
    }

    public function getGainFloat(): float
    {
        return $this->gain !== null ? (float) $this->gain : 0;
    }

    public function setGain(?string $gain): static
    {
        $this->gain = $gain;
        return $this;
    }

    public function getPerte(): ?string
    {
        return $this->perte;
    }

    public function getPerteFloat(): float
    {
        return $this->perte !== null ? (float) $this->perte : 0;
    }

    public function setPerte(?string $perte): static
    {
        $this->perte = $perte;
        return $this;
    }

    public function getValeurPortefeuille(): ?string
    {
        return $this->valeurPortefeuille;
    }

    public function getValeurPortefeuilleFloat(): float
    {
        return (float) $this->valeurPortefeuille;
    }

    public function setValeurPortefeuille(string $valeurPortefeuille): static
    {
        $this->valeurPortefeuille = $valeurPortefeuille;
        return $this;
    }

    public function getRendementNet(): float
    {
        return $this->getGainFloat() - $this->getPerteFloat();
    }
}
