<?php

namespace App\Service;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class JwtService
{
    private string $secretKey;
    private int $ttl;
    private EntityManagerInterface $entityManager;
    private array $validTokens = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->secretKey = $_ENV['JWT_SECRET'] ?? 'cashfly_jwt_secret_key_2024_secure';
        $this->ttl = (int) ($_ENV['JWT_TTL'] ?? 86400);
        $this->entityManager = $entityManager;
    }

    public function createToken(Utilisateur $user): array
    {
        $issuedAt = time();
        $expiresAt = $issuedAt + $this->ttl;
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expiresAt,
            'sub' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
        ];

        $token = $this->base64UrlEncode(json_encode($payload));
        $signature = $this->sign($token);
        
        $fullToken = $token . '.' . $signature;
        
        $this->validTokens[$fullToken] = [
            'user_id' => $user->getId(),
            'expires_at' => $expiresAt,
        ];
        
        return [
            'token' => $fullToken,
            'expires_at' => date('Y-m-d H:i:s', $expiresAt),
            'token_type' => 'Bearer',
        ];
    }

    public function validateToken(string $token): ?array
    {
        $parts = explode('.', $token);
        
        if (count($parts) !== 2) {
            return null;
        }
        
        [$payloadBase64, $signature] = $parts;
        
        $expectedSignature = $this->sign($payloadBase64);
        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }
        
        $payload = json_decode($this->base64UrlDecode($payloadBase64), true);
        
        if (!$payload || !isset($payload['exp'])) {
            return null;
        }
        
        if ($payload['exp'] < time()) {
            return null;
        }
        
        return $payload;
    }

    public function getUserFromToken(string $token): ?Utilisateur
    {
        $payload = $this->validateToken($token);
        
        if (!$payload || !isset($payload['sub'])) {
            return null;
        }
        
        return $this->entityManager->getRepository(Utilisateur::class)->find($payload['sub']);
    }

    public function createRefreshToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function refreshAccessToken(string $refreshToken): ?array
    {
        return null;
    }

    public function revokeToken(string $token): void
    {
        if (isset($this->validTokens[$token])) {
            unset($this->validTokens[$token]);
        }
    }

    private function sign(string $data): string
    {
        return hash_hmac('sha256', $data, $this->secretKey, true);
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
