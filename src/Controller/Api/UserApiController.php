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

#[Route('/api/users')]
class UserApiController extends BaseApiController
{
    public function __construct(
        private UtilisateurRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidationService $validation,
        private JwtService $jwtService
    ) {}

    #[Route('', name: 'api_users_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $authUser = $this->getUserFromRequest($request);
        
        if (!$authUser || !$authUser->isAdmin()) {
            return $this->forbidden('Acces reserve aux administrateurs');
        }
        
        $users = $this->userRepository->findBy([], ['dateCreation' => 'DESC']);
        
        return $this->success([
            'users' => array_map(fn($u) => $this->formatUser($u), $users),
            'total' => count($users),
        ]);
    }

    #[Route('/{id}', name: 'api_users_show', methods: ['GET'])]
    public function show(int $id, Request $request): JsonResponse
    {
        $authUser = $this->getUserFromRequest($request);
        
        if (!$authUser) {
            return $this->unauthorized();
        }
        
        $user = $this->userRepository->find($id);
        
        if (!$user) {
            return $this->notFound('Utilisateur non trouve');
        }
        
        if (!$authUser->isAdmin() && $authUser->getId() !== $id) {
            return $this->forbidden();
        }
        
        return $this->success([
            'user' => $this->formatUser($user, true),
        ]);
    }

    #[Route('', name: 'api_users_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $authUser = $this->getUserFromRequest($request);
        
        if (!$authUser || !$authUser->isAdmin()) {
            return $this->forbidden('Acces reserve aux administrateurs');
        }
        
        $data = json_decode($request->getContent(), true);
        
        if ($this->userRepository->findOneBy(['email' => $data['email'] ?? ''])) {
            return $this->validationError(['email' => 'Cet email existe deja']);
        }
        
        $this->validation->clearErrors();
        
        $valid = true;
        $valid = $this->validation->validateEmail($data['email'] ?? '') && $valid;
        $valid = $this->validation->validateRequired($data['nom'] ?? '', 'nom') && $valid;
        
        if (isset($data['cin'])) {
            $valid = $this->validation->validateCin($data['cin']) && $valid;
        }
        
        if (isset($data['tel'])) {
            $valid = $this->validation->validatePhone($data['tel']) && $valid;
        }
        
        if (isset($data['role'])) {
            $valid = $this->validation->validateRole($data['role']) && $valid;
        }
        
        if ($this->validation->hasErrors()) {
            return $this->validationError($this->validation->getErrors());
        }
        
        $user = new Utilisateur();
        $user->setEmail($data['email']);
        $user->setNom($data['nom']);
        $user->setPrenom($data['prenom'] ?? '');
        $user->setCin($data['cin'] ?? null);
        $user->setTel($data['tel'] ?? null);
        $user->setRole($data['role'] ?? 'investisseur');
        $user->setActive($data['active'] ?? 1);
        $user->setDateCreation(new \DateTime());
        
        if (isset($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }
        
        if (isset($data['years_experience'])) {
            $user->setYearsExperience($data['years_experience']);
        }
        
        if (isset($data['highest_profit'])) {
            $user->setHighestProfit($data['highest_profit']);
        }
        
        if (isset($data['budget'])) {
            $user->setBudget($data['budget']);
        }
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        return $this->created([
            'user' => $this->formatUser($user),
        ], 'Utilisateur cree');
    }

    #[Route('/{id}', name: 'api_users_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $authUser = $this->getUserFromRequest($request);
        
        if (!$authUser) {
            return $this->unauthorized();
        }
        
        $user = $this->userRepository->find($id);
        
        if (!$user) {
            return $this->notFound('Utilisateur non trouve');
        }
        
        if (!$authUser->isAdmin() && $authUser->getId() !== $id) {
            return $this->forbidden();
        }
        
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['nom'])) {
            $user->setNom($data['nom']);
        }
        
        if (isset($data['prenom'])) {
            $user->setPrenom($data['prenom']);
        }
        
        if (isset($data['cin'])) {
            $this->validation->clearErrors();
            if ($this->validation->validateCin($data['cin'])) {
                $user->setCin($data['cin']);
            }
        }
        
        if (isset($data['tel'])) {
            $this->validation->clearErrors();
            if ($this->validation->validatePhone($data['tel'])) {
                $user->setTel($data['tel']);
            }
        }
        
        if (isset($data['email']) && $data['email'] !== $user->getEmail()) {
            if (!$this->userRepository->findOneBy(['email' => $data['email']])) {
                $user->setEmail($data['email']);
            }
        }
        
        if ($authUser->isAdmin() && isset($data['role'])) {
            $this->validation->clearErrors();
            if ($this->validation->validateRole($data['role'])) {
                $user->setRole($data['role']);
            }
        }
        
        if ($authUser->isAdmin() && isset($data['active'])) {
            $user->setActive($data['active'] ? 1 : 0);
        }
        
        if (isset($data['password']) && !empty($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }
        
        if (isset($data['years_experience'])) {
            $user->setYearsExperience($data['years_experience']);
        }
        
        if (isset($data['highest_profit'])) {
            $user->setHighestProfit($data['highest_profit']);
        }
        
        if (isset($data['budget'])) {
            $user->setBudget($data['budget']);
        }
        
        $this->entityManager->flush();
        
        return $this->updated([
            'user' => $this->formatUser($user),
        ]);
    }

    #[Route('/{id}/toggle-active', name: 'api_users_toggle_active', methods: ['POST'])]
    public function toggleActive(int $id, Request $request): JsonResponse
    {
        $authUser = $this->getUserFromRequest($request);
        
        if (!$authUser || !$authUser->isAdmin()) {
            return $this->forbidden('Acces reserve aux administrateurs');
        }
        
        $user = $this->userRepository->find($id);
        
        if (!$user) {
            return $this->notFound('Utilisateur non trouve');
        }
        
        $user->setActive($user->isActive() ? 0 : 1);
        $this->entityManager->flush();
        
        return $this->success([
            'user' => $this->formatUser($user),
        ], 'Statut mis a jour');
    }

    #[Route('/{id}', name: 'api_users_delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        $authUser = $this->getUserFromRequest($request);
        
        if (!$authUser || !$authUser->isAdmin()) {
            return $this->forbidden('Acces reserve aux administrateurs');
        }
        
        $user = $this->userRepository->find($id);
        
        if (!$user) {
            return $this->notFound('Utilisateur non trouve');
        }
        
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        
        return $this->deleted('Utilisateur supprime');
    }

    #[Route('/stats/by-role', name: 'api_users_stats_by_role', methods: ['GET'])]
    public function statsByRole(Request $request): JsonResponse
    {
        $authUser = $this->getUserFromRequest($request);
        
        if (!$authUser || !$authUser->isAdmin()) {
            return $this->forbidden('Acces reserve aux administrateurs');
        }
        
        $stats = $this->userRepository->countByRole();
        
        return $this->success([
            'stats' => $stats,
        ]);
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

    private function formatUser(Utilisateur $user, bool $detailed = false): array
    {
        $data = [
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
        ];
        
        if ($detailed) {
            $data['years_experience'] = $user->getYearsExperience();
            $data['highest_profit'] = $user->getHighestProfit();
            $data['budget'] = $user->getBudget();
            $data['entreprises_count'] = $user->getEntreprises()->count();
            $data['investissements_count'] = $user->getInvestissements()->count();
        }
        
        return $data;
    }
}
