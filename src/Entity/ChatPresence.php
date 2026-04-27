<?php

namespace App\Entity;

use App\Repository\ChatPresenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatPresenceRepository::class)]
#[ORM\Table(name: 'chat_presence')]
class ChatPresence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_presence', type: Types::INTEGER)]
    private ?int $idPresence = null;

    #[ORM\Column(name: 'id_room', type: Types::INTEGER)]
    private int $idRoom;

    #[ORM\Column(name: 'id_utilisateur', type: Types::INTEGER)]
    private int $idUtilisateur;

    #[ORM\Column(name: 'last_seen', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $lastSeen;

    #[ORM\Column(name: 'is_online', type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isOnline = true;

    public function __construct()
    {
        $this->lastSeen = new \DateTime();
    }

    public function getIdPresence(): ?int { return $this->idPresence; }

    public function getIdRoom(): int { return $this->idRoom; }
    public function setIdRoom(int $idRoom): static { $this->idRoom = $idRoom; return $this; }

    public function getIdUtilisateur(): int { return $this->idUtilisateur; }
    public function setIdUtilisateur(int $idUtilisateur): static { $this->idUtilisateur = $idUtilisateur; return $this; }

    public function getLastSeen(): \DateTimeInterface { return $this->lastSeen; }
    public function setLastSeen(\DateTimeInterface $lastSeen): static { $this->lastSeen = $lastSeen; return $this; }

    public function isOnline(): bool { return $this->isOnline; }
    public function setIsOnline(bool $isOnline): static { $this->isOnline = $isOnline; return $this; }
}
