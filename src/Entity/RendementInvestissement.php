<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'rendement_investissement', indexes: [new ORM\Index(name: 'fk_rend_inv_invest', columns: ['id_investissement'])])]
class RendementInvestissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_rendement', type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Investissement::class)]
    #[ORM\JoinColumn(name: 'id_investissement', referencedColumnName: 'id_investissement', nullable: false, onDelete: 'CASCADE')]
    private ?Investissement $investissement = null;

    #[ORM\Column(name: 'date_calcul', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCalcul = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, nullable: true, options: ['default' => '0.00'])]
    private ?string $gain = '0.00';

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, nullable: true, options: ['default' => '0.00'])]
    private ?string $perte = '0.00';

    #[ORM\Column(name: 'valeur_portefeuille', type: Types::DECIMAL, precision: 15, scale: 2)]
    private string $valeurPortefeuille;
}
