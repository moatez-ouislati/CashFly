<?php

namespace App\Controller\Api;

use App\Entity\Operation;
use App\Entity\Utilisateur;
use App\Repository\OperationRepository;
use App\Service\JwtService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/operations')]
class OperationApiController extends BaseApiController
{
    public function __construct(
        private OperationRepository $operationRepository,
        private EntityManagerInterface $entityManager,
        private JwtService $jwtService
    ) {}

    #[Route('', name: 'api_operations_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $limit = $request->query->getInt('limit', 100);
        $offset = $request->query->getInt('offset', 0);
        
        $operations = $this->operationRepository->findBy(
            [],
            ['dateOperation' => 'DESC'],
            $limit,
            $offset
        );
        
        return $this->success([
            'operations' => array_map(fn($o) => $this->formatOperation($o), $operations),
            'total' => count($operations),
        ]);
    }

    #[Route('/revenus', name: 'api_operations_revenus', methods: ['GET'])]
    public function revenus(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $operations = $this->operationRepository->findBy(
            ['type' => 'revenu'],
            ['dateOperation' => 'DESC']
        );
        
        $total = $this->operationRepository->getTotalRevenus();
        
        return $this->success([
            'operations' => array_map(fn($o) => $this->formatOperation($o), $operations),
            'total' => count($operations),
            'total_montant' => $total,
        ]);
    }

    #[Route('/depenses', name: 'api_operations_depenses', methods: ['GET'])]
    public function depenses(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $operations = $this->operationRepository->findBy(
            ['type' => 'depense'],
            ['dateOperation' => 'DESC']
        );
        
        $total = $this->operationRepository->getTotalDepenses();
        
        return $this->success([
            'operations' => array_map(fn($o) => $this->formatOperation($o), $operations),
            'total' => count($operations),
            'total_montant' => $total,
        ]);
    }

    #[Route('/{id}', name: 'api_operations_show', methods: ['GET'])]
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $operation = $this->operationRepository->find($id);
        
        if (!$operation) {
            return $this->notFound('Operation non trouvee');
        }
        
        return $this->success([
            'operation' => $this->formatOperation($operation, true),
        ]);
    }

    #[Route('', name: 'api_operations_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['type']) || !in_array($data['type'], ['revenu', 'depense'])) {
            return $this->validationError(['type' => 'Type invalide (doit etre revenu ou depense)']);
        }
        
        if (!isset($data['montant']) || !is_numeric($data['montant'])) {
            return $this->validationError(['montant' => 'Montant invalide']);
        }
        
        $operation = new Operation();
        $operation->setType($data['type']);
        $operation->setMontant(number_format((float) $data['montant'], 2, '.', ''));
        $operation->setDescription($data['description'] ?? null);
        $operation->setCategorie($data['categorie'] ?? null);
        $operation->setReference($data['reference'] ?? null);
        $operation->setFacture($data['facture'] ?? null);
        $operation->setPdfUrl($data['pdf_url'] ?? null);
        $operation->setDateOperation(new \DateTime($data['date'] ?? 'now'));
        
        $this->entityManager->persist($operation);
        $this->entityManager->flush();
        
        return $this->created([
            'operation' => $this->formatOperation($operation),
        ], 'Operation creee');
    }

    #[Route('/{id}', name: 'api_operations_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $operation = $this->operationRepository->find($id);
        
        if (!$operation) {
            return $this->notFound('Operation non trouvee');
        }
        
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['type'])) {
            $operation->setType($data['type']);
        }
        
        if (isset($data['montant'])) {
            $operation->setMontant(number_format((float) $data['montant'], 2, '.', ''));
        }
        
        if (array_key_exists('description', $data)) {
            $operation->setDescription($data['description']);
        }
        
        if (array_key_exists('categorie', $data)) {
            $operation->setCategorie($data['categorie']);
        }
        
        if (array_key_exists('reference', $data)) {
            $operation->setReference($data['reference']);
        }
        
        $this->entityManager->flush();
        
        return $this->updated([
            'operation' => $this->formatOperation($operation),
        ]);
    }

    #[Route('/{id}', name: 'api_operations_delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $operation = $this->operationRepository->find($id);
        
        if (!$operation) {
            return $this->notFound('Operation non trouvee');
        }
        
        $this->entityManager->remove($operation);
        $this->entityManager->flush();
        
        return $this->deleted('Operation supprimee');
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

    private function formatOperation(Operation $operation, bool $detailed = false): array
    {
        $data = [
            'id' => $operation->getId(),
            'type' => $operation->getType(),
            'montant' => $operation->getMontant(),
            'description' => $operation->getDescription(),
            'categorie' => $operation->getCategorie(),
            'reference' => $operation->getReference(),
            'facture' => $operation->getFacture(),
            'pdf_url' => $operation->getPdfUrl(),
            'date_operation' => $operation->getDateOperation()?->format('Y-m-d H:i:s'),
        ];
        
        return $data;
    }
}
