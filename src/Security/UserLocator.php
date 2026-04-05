<?php

namespace App\Security;

use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\User\UserInterface;

class UserLocator implements UserInterface
{
    private Utilisateur $user;
    private string $password;

    public function __construct(Utilisateur $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function getRoles(): array
    {
        return $this->user->getRoles();
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->user->getUserIdentifier();
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getUser(): Utilisateur
    {
        return $this->user;
    }

    public function isPasswordValid(): bool
    {
        $hashedPassword = $this->user->getPassword();
        
        if (str_starts_with($hashedPassword, '$2')) {
            return password_verify($this->password, $hashedPassword);
        }
        
        return $hashedPassword === $this->password;
    }
}
