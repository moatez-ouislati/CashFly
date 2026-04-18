<?php

namespace App\Controller\Api;

use App\Entity\Tresorerie;
use App\Entity\Utilisateur;
use App\Repository\EntrepriseRepository;
use App\Repository\TresorerieRepository;
use App\Service\JwtService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tresorerie')]
class TresorerieApiController extends BaseApiController
{
    public function __construct(
        private TresorerieRepository $tresorerieRepository,
        private EntrepriseRepository $entrepriseRepository,
        private EntityManagerInterface $entityManager,
        private JwtService $jwtService
    ) {}

    #[Route('', name: 'api_tresorerie_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $tresoreries = $this->tresorerieRepository->findAll();
        
        $totalSolde = $this->tresorerieRepository->getTotalSolde();
        $soldeByType = $this->tresorerieRepository->getSoldeByType();
        
        return $this->success([
            'tresoreries' => array_map(fn($t) => $this->formatTresorerie($t), $tresoreries),
            'total' => count($tresoreries),
            'total_solde' => $totalSolde,
            'solde_by_type' => $soldeByType,
        ]);
    }

    #[Route('/{id}', name: 'api_tresorerie_show', methods: ['GET'])]
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $tresorerie = $this->tresorerieRepository->find($id);
        
        if (!$tresorerie) {
            return $this->notFound('Tresorerie non trouvee');
        }
        
        return $this->success([
            'tresorerie' => $this->formatTresorerie($tresorerie, true),
        ]);
    }

    #[Route('', name: 'api_tresorerie_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['entreprise_id'])) {
            return $this->validationError(['entreprise_id' => 'ID entreprise requis']);
        }
        
        $entreprise = $this->entrepriseRepository->find($data['entreprise_id']);
        
        if (!$entreprise) {
            return $this->notFound('Entreprise non trouvee');
        }
        
        if ($entreprise->getProprietaire()->getId() !== $user->getId() && !$user->isAdmin()) {
            return $this->forbidden();
        }
        
        $tresorerie = new Tresorerie();
        $tresorerie->setEntreprise($entreprise);
        $tresorerie->setNomCompte($data['nom_compte'] ?? 'Compte courant');
        $tresorerie->setTypeCompte($data['type_compte'] ?? 'courant');
        $tresorerie->setSolde($data['solde'] ?? '0.00');
        $tresorerie->setDevise($data['devise'] ?? 'TND');
        $tresorerie->setRib($data['rib'] ?? null);
        $tresorerie->setNumeroCompte($data['numero_compte'] ?? null);
        
        $this->entityManager->persist($tresorerie);
        $this->entityManager->flush();
        
        return $this->created([
            'tresorerie' => $this->formatTresorerie($tresorerie),
        ], 'Compte cree');
    }

    #[Route('/{id}', name: 'api_tresorerie_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $tresorerie = $this->tresorerieRepository->find($id);
        
        if (!$tresorerie) {
            return $this->notFound('Tresorerie non trouvee');
        }
        
        if ($tresorerie->getEntreprise()->getProprietaire()->getId() !== $user->getId() && !$user->isAdmin()) {
            return $this->forbidden();
        }
        
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['nom_compte'])) {
            $tresorerie->setNomCompte($data['nom_compte']);
        }
        
        if (isset($data['type_compte'])) {
            $tresorerie->setTypeCompte($data['type_compte']);
        }
        
        if (isset($data['solde'])) {
            $tresorerie->setSolde(number_format((float) $data['solde'], 2, '.', ''));
        }
        
        if (isset($data['devise'])) {
            $tresorerie->setDevise($data['devise']);
        }
        
        if (isset($data['rib'])) {
            $tresorerie->setRib($data['rib']);
        }
        
        if (isset($data['numero_compte'])) {
            $tresorerie->setNumeroCompte($data['numero_compte']);
        }
        
        $this->entityManager->flush();
        
        return $this->updated([
            'tresorerie' => $this->formatTresorerie($tresorerie),
        ]);
    }

    #[Route('/{id}', name: 'api_tresorerie_delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $tresorerie = $this->tresorerieRepository->find($id);
        
        if (!$tresorerie) {
            return $this->notFound('Tresorerie non trouvee');
        }
        
        if ($tresorerie->getEntreprise()->getProprietaire()->getId() !== $user->getId() && !$user->isAdmin()) {
            return $this->forbidden();
        }
        
        $this->entityManager->remove($tresorerie);
        $this->entityManager->flush();
        
        return $this->deleted('Compte supprime');
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

    private function formatTresorerie(Tresorerie $tresorerie, bool $detailed = false): array
    {
        $data = [
            'id' => $tresorerie->getId(),
            'nom_compte' => $tresorerie->getNomCompte(),
            'type_compte' => $tresorerie->getTypeCompte(),
            'solde' => $tresorerie->getSolde(),
            'devise' => $tresorerie->getDevise(),
            'rib' => $tresorerie->getRib(),
            'numero_compte' => $tresorerie->getNumeroCompte(),
            'entreprise' => $tresorerie->getEntreprise() ? [
                'id' => $tresorerie->getEntreprise()->getId(),
                'nom' => $tresorerie->getEntreprise()->getNom(),
            ] : null,
        ];
        
        return $data;
    }
}
