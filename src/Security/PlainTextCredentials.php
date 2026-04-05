<?php

namespace App\Security;

use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CredentialsInterface;

class PlainTextCredentials implements CredentialsInterface
{
    public function __construct(
        private string $password
    ) {}

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function isResolved(): bool
    {
        return true;
    }
}
