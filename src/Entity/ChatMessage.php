<?php

namespace App\Entity;

use App\Repository\ChatMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatMessageRepository::class)]
#[ORM\Table(name: 'chat_messages')]
class ChatMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_message', type: Types::INTEGER)]
    private ?int $idMessage = null;

    #[ORM\Column(name: 'id_room', type: Types::INTEGER)]
    private int $idRoom;

    #[ORM\Column(name: 'id_utilisateur', type: Types::INTEGER)]
    private int $idUtilisateur;

    #[ORM\Column(name: 'message', type: Types::TEXT)]
    private string $message;

    #[ORM\Column(name: 'type', type: Types::STRING, length: 20, options: ['default' => 'text'])]
    private string $type = 'text';

    #[ORM\Column(name: 'reply_to', type: Types::INTEGER, nullable: true)]
    private ?int $replyTo = null;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(name: 'is_edited', type: Types::BOOLEAN, options: ['default' => false])]
    private bool $isEdited = false;

    #[ORM\Column(name: 'status', type: Types::STRING, length: 20, options: ['default' => 'active'])]
    private string $status = 'active';

    #[ORM\Column(name: 'edited_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $editedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getIdMessage(): ?int { return $this->idMessage; }

    public function getIdRoom(): int { return $this->idRoom; }
    public function setIdRoom(int $idRoom): static { $this->idRoom = $idRoom; return $this; }

    public function getIdUtilisateur(): int { return $this->idUtilisateur; }
    public function setIdUtilisateur(int $idUtilisateur): static { $this->idUtilisateur = $idUtilisateur; return $this; }

    public function getMessage(): string { return $this->message; }
    public function setMessage(string $message): static { $this->message = $message; return $this; }

    public function getType(): string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }

    public function getReplyTo(): ?int { return $this->replyTo; }
    public function setReplyTo(?int $replyTo): static { $this->replyTo = $replyTo; return $this; }

    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function isEdited(): bool { return $this->isEdited; }
    public function setIsEdited(bool $isEdited): static { $this->isEdited = $isEdited; return $this; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }

    public function getEditedAt(): ?\DateTimeInterface { return $this->editedAt; }
    public function setEditedAt(?\DateTimeInterface $editedAt): static { $this->editedAt = $editedAt; return $this; }
}
