<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(
    name: 'investissement',
    indexes: [
        new ORM\Index(name: 'fk_inv_user', columns: ['id_investisseur']),
        new ORM\Index(name: 'fk_inv_entreprise', columns: ['id_entreprise']),
    ]
)]
class Investissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_investissement', type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_investisseur', referencedColumnName: 'id_utilisateur', nullable: false, onDelete: 'CASCADE')]
    private ?User $investisseur = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class)]
    #[ORM\JoinColumn(name: 'id_entreprise', referencedColumnName: 'id_entreprise', nullable: false, onDelete: 'CASCADE')]
    private ?Entreprise $entreprise = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2)]
    private string $montant;

    #[ORM\Column(
        name: 'date_investissement',
        type: Types::DATETIME_MUTABLE,
        nullable: true,
        columnDefinition: 'DATETIME DEFAULT CURRENT_TIMESTAMP'
    )]
    private ?\DateTimeInterface $dateInvestissement = null;

    #[ORM\Column(
        name: 'statut',
        type: 'string',
        length: 20,
        nullable: true,
        columnDefinition: "ENUM('EN_ATTENTE','ACTIF','TERMINE','ANNULE') DEFAULT 'EN_ATTENTE'"
    )]
    private ?string $statut = 'EN_ATTENTE';

    #[ORM\Column(name: 'taux_rendement_prevu', type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $tauxRendementPrevu = null;

    #[ORM\Column(name: 'duree_mois', type: 'integer', nullable: true)]
    private ?int $dureeMois = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, columnDefinition: 'TEXT DEFAULT NULL')]
    private ?string $description = null;
}
