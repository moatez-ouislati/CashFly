<?php

namespace App\Security;

use Symfony\Component\PasswordHasher\Hasher\PasswordHashers;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class PlainTextPasswordHasher implements \Symfony\Component\PasswordHasher\PasswordHasherInterface
{
    public function __construct(
        private PasswordHashers $passwordHashers
    ) {}

    public function hash(string $plainPassword): string
    {
        return $this->passwordHashers->hashPassword($plainPassword);
    }

    public function verify(string $hashedPassword, string $plainPassword): bool
    {
        if (str_starts_with($hashedPassword, '$2')) {
            return password_verify($plainPassword, $hashedPassword);
        }
        
        return $hashedPassword === $plainPassword;
    }

    public function needsRehash(string $hashedPassword): bool
    {
        if (str_starts_with($hashedPassword, '$2')) {
            return password_needs_rehash($hashedPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        }
        return true;
    }
}
