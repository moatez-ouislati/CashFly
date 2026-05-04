<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_kyc')]
class UserKyc
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id_utilisateur', nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column(name: 'face_embedding', type: Types::BLOB, columnDefinition: 'BLOB NOT NULL')]
    private mixed $faceEmbedding = null;

    #[ORM\Column(name: 'is_verified', type: 'boolean', nullable: true, options: ['default' => 0])]
    private ?bool $isVerified = false;

    #[ORM\Column(name: 'verified_at', type: Types::DATETIME_MUTABLE, nullable: true, columnDefinition: 'TIMESTAMP NULL DEFAULT NULL')]
    private ?\DateTimeInterface $verifiedAt = null;

    #[ORM\Column(name: 'id_document_path', length: 255, nullable: true)]
    private ?string $idDocumentPath = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE, nullable: true, columnDefinition: 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_MUTABLE, nullable: true, columnDefinition: 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')]
    private ?\DateTimeInterface $updatedAt = null;
}
