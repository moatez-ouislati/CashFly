<?php

namespace App\Security;

use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

final class LegacyCompatiblePasswordHasher implements PasswordHasherInterface
{
    private NativePasswordHasher $nativeHasher;

    public function __construct()
    {
        $this->nativeHasher = new NativePasswordHasher();
    }

    public function hash(string $plainPassword): string
    {
        return $this->nativeHasher->hash($plainPassword);
    }

    public function verify(string $hashedPassword, string $plainPassword, ?string $salt = null): bool
    {
        if ($this->isLegacyMd5Hash($hashedPassword)) {
            return hash_equals(strtolower($hashedPassword), md5($plainPassword));
        }

        return $this->nativeHasher->verify($hashedPassword, $plainPassword, $salt);
    }

    public function needsRehash(string $hashedPassword): bool
    {
        return $this->isLegacyMd5Hash($hashedPassword) || $this->nativeHasher->needsRehash($hashedPassword);
    }

    private function isLegacyMd5Hash(string $hashedPassword): bool
    {
        return strlen($hashedPassword) === 32 && ctype_xdigit($hashedPassword);
    }
}
