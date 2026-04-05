<?php

namespace App\Entity;

use App\Repository\UserKycRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserKycRepository::class)]
#[ORM\Table(name: 'user_kyc')]
class UserKyc
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: Utilisateur::class, inversedBy: 'userKyc')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id_utilisateur', nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(name: 'face_embedding', length: 100, nullable: true)]
    private ?string $faceEmbedding = null;

    #[ORM\Column(name: 'is_verified')]
    private ?int $isVerified = 0;

    #[ORM\Column(name: 'verified_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $verifiedAt = null;

    #[ORM\Column(name: 'id_document_path', length: 255, nullable: true)]
    private ?string $idDocumentPath = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(name: 'updated_at', type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getFaceEmbedding(): ?string
    {
        return $this->faceEmbedding;
    }

    public function setFaceEmbedding(?string $faceEmbedding): static
    {
        $this->faceEmbedding = $faceEmbedding;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified === 1;
    }

    public function getIsVerified(): ?int
    {
        return $this->isVerified;
    }

    public function setIsVerified(int $isVerified): static
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeInterface
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?\DateTimeInterface $verifiedAt): static
    {
        $this->verifiedAt = $verifiedAt;
        return $this;
    }

    public function getIdDocumentPath(): ?string
    {
        return $this->idDocumentPath;
    }

    public function setIdDocumentPath(?string $idDocumentPath): static
    {
        $this->idDocumentPath = $idDocumentPath;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
