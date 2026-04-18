<?php

namespace App\Controller\Api;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Service\JwtService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/auth')]
class AuthApiController extends BaseApiController
{
    public function __construct(
        private UtilisateurRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidationService $validation,
        private JwtService $jwtService
    ) {}

    #[Route('/register', name: 'api_auth_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!$data) {
            return $this->validationError(['body' => 'JSON invalide']);
        }
        
        $this->validation->clearErrors();
        
        $valid = true;
        $valid = $this->validation->validateEmail($data['email'] ?? '') && $valid;
        $valid = $this->validation->validateRequired($data['nom'] ?? '', 'nom') && $valid;
        $valid = $this->validation->validateRequired($data['prenom'] ?? '', 'prenom') && $valid;
        
        if (isset($data['cin'])) {
            $valid = $this->validation->validateCin($data['cin']) && $valid;
        }
        
        if (isset($data['tel'])) {
            $valid = $this->validation->validatePhone($data['tel']) && $valid;
        }
        
        if (isset($data['password'])) {
            $valid = $this->validation->validatePassword($data['password']) && $valid;
        }
        
        if ($this->userRepository->findOneBy(['email' => $data['email']])) {
            return $this->validationError(['email' => 'Cet email est deja utilise']);
        }
        
        if ($this->validation->hasErrors()) {
            return $this->validationError($this->validation->getErrors());
        }
        
        $user = new Utilisateur();
        $user->setEmail($data['email']);
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom']);
        $user->setCin($data['cin'] ?? null);
        $user->setTel($data['tel'] ?? null);
        $user->setRole($data['role'] ?? 'investisseur');
        $user->setActive(1);
        $user->setDateCreation(new \DateTime());
        
        if (isset($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        $tokens = $this->jwtService->createToken($user);
        
        return $this->created([
            'user' => $this->formatUser($user),
            'tokens' => $tokens,
        ], 'Utilisateur cree avec succes');
    }

    #[Route('/login', name: 'api_auth_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!$data || !isset($data['email'], $data['password'])) {
            return $this->validationError(['credentials' => 'Email et mot de passe requis']);
        }
        
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        
        if (!$user) {
            return $this->unauthorized('Email ou mot de passe incorrect');
        }
        
        if (!$user->isActive()) {
            return $this->forbidden('Votre compte a ete desactive');
        }
        
        if (!$user->verifyPassword($data['password'])) {
            return $this->unauthorized('Email ou mot de passe incorrect');
        }
        
        $tokens = $this->jwtService->createToken($user);
        $refreshToken = $this->jwtService->createRefreshToken();
        
        return $this->success([
            'user' => $this->formatUser($user),
            'tokens' => $tokens,
            'refresh_token' => $refreshToken,
        ], 'Connexion reussie');
    }

    #[Route('/logout', name: 'api_auth_logout', methods: ['POST'])]
    public function logout(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $authHeader = $request->headers->get('Authorization');
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
            $this->jwtService->revokeToken($token);
        }
        
        return $this->success(null, 'Deconnexion reussie');
    }

    #[Route('/refresh', name: 'api_auth_refresh', methods: ['POST'])]
    public function refresh(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!$data || !isset($data['refresh_token'])) {
            return $this->validationError(['refresh_token' => 'Refresh token requis']);
        }
        
        $tokens = $this->jwtService->refreshAccessToken($data['refresh_token']);
        
        if (!$tokens) {
            return $this->unauthorized('Refresh token invalide ou expire');
        }
        
        return $this->success($tokens, 'Token rafraichi');
    }

    #[Route('/me', name: 'api_auth_me', methods: ['GET'])]
    public function me(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        return $this->success([
            'user' => $this->formatUser($user),
        ]);
    }

    #[Route('/reset-password', name: 'api_auth_reset_password', methods: ['POST'])]
    public function resetPassword(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!$data || !isset($data['email'])) {
            return $this->validationError(['email' => 'Email requis']);
        }
        
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        
        if (!$user) {
            return $this->success(null, 'Si cet email existe, un lien de reinitialisation a ete envoye');
        }
        
        $resetToken = bin2hex(random_bytes(32));
        
        return $this->success([
            'reset_token' => $resetToken,
            'message' => 'Token de reinitialisation genere (envoyer par email en production)',
        ], 'Token de reinitialisation genere');
    }

    #[Route('/change-password', name: 'api_auth_change_password', methods: ['POST'])]
    public function changePassword(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $data = json_decode($request->getContent(), true);
        
        if (!$data || !isset($data['current_password'], $data['new_password'])) {
            return $this->validationError(['credentials' => 'Mot de passe actuel et nouveau requis']);
        }
        
        if (!$user->verifyPassword($data['current_password'])) {
            return $this->unauthorized('Mot de passe actuel incorrect');
        }
        
        $this->validation->clearErrors();
        if (!$this->validation->validatePassword($data['new_password'])) {
            return $this->validationError($this->validation->getErrors());
        }
        
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['new_password']);
        $user->setPassword($hashedPassword);
        
        $this->entityManager->flush();
        
        return $this->success(null, 'Mot de passe change avec succes');
    }

    private function getUserFromRequest(Request $request): ?Utilisateur
    {
        $authHeader = $request->headers->get('Authorization');
        
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return null;
        }
        
        $token = substr($authHeader, 7);
        
        return $this->jwtService->getUserFromToken($token);
    }

    private function formatUser(Utilisateur $user): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'full_name' => $user->getFullName(),
            'cin' => $user->getCin(),
            'tel' => $user->getTel(),
            'role' => $user->getRole(),
            'active' => $user->isActive(),
            'date_creation' => $user->getDateCreation()?->format('Y-m-d H:i:s'),
            'years_experience' => $user->getYearsExperience(),
            'highest_profit' => $user->getHighestProfit(),
            'budget' => $user->getBudget(),
        ];
    }
}
