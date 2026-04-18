<?php

namespace App\Controller\Api;

use App\Entity\Entreprise;
use App\Entity\Utilisateur;
use App\Repository\EntrepriseRepository;
use App\Repository\UtilisateurRepository;
use App\Service\JwtService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/entreprises')]
class EntrepriseApiController extends BaseApiController
{
    public function __construct(
        private EntrepriseRepository $entrepriseRepository,
        private UtilisateurRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private ValidationService $validation,
        private JwtService $jwtService
    ) {}

    #[Route('', name: 'api_entreprises_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        $entreprises = $this->entrepriseRepository->findBy([], ['dateCreation' => 'DESC']);
        
        return $this->success([
            'entreprises' => array_map(fn($e) => $this->formatEntreprise($e), $entreprises),
            'total' => count($entreprises),
        ]);
    }

    #[Route('/my', name: 'api_entreprises_my', methods: ['GET'])]
    public function myEntreprises(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $entreprises = $this->entrepriseRepository->findBy(['proprietaire' => $user]);
        
        return $this->success([
            'entreprises' => array_map(fn($e) => $this->formatEntreprise($e), $entreprises),
            'total' => count($entreprises),
        ]);
    }

    #[Route('/{id}', name: 'api_entreprises_show', methods: ['GET'])]
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $entreprise = $this->entrepriseRepository->find($id);
        
        if (!$entreprise) {
            return $this->notFound('Entreprise non trouvee');
        }
        
        return $this->success([
            'entreprise' => $this->formatEntreprise($entreprise, true),
        ]);
    }

    #[Route('', name: 'api_entreprises_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        if (!$user->isProprietaire() && !$user->isAdmin()) {
            return $this->forbidden('Seuls les proprietaires et administrateurs peuvent creer des entreprises');
        }
        
        $data = json_decode($request->getContent(), true);
        
        $this->validation->clearErrors();
        if (!$this->validation->validateRequired($data['nom'] ?? '', 'nom')) {
            return $this->validationError($this->validation->getErrors());
        }
        
        $entreprise = new Entreprise();
        $entreprise->setNom($data['nom']);
        $entreprise->setSecteur($data['secteur'] ?? null);
        $entreprise->setFormeJuridique($data['forme_juridique'] ?? null);
        $entreprise->setCapital($data['capital'] ?? '0.00');
        $entreprise->setAdresse($data['adresse'] ?? null);
        $entreprise->setLatitude($data['latitude'] ?? null);
        $entreprise->setLongitude($data['longitude'] ?? null);
        $entreprise->setDateCreation(new \DateTime());
        
        if (isset($data['proprietaire_id'])) {
            $proprietaire = $this->userRepository->find($data['proprietaire_id']);
            if ($proprietaire) {
                $entreprise->setProprietaire($proprietaire);
            }
        } else {
            $entreprise->setProprietaire($user);
        }
        
        $this->entityManager->persist($entreprise);
        $this->entityManager->flush();
        
        return $this->created([
            'entreprise' => $this->formatEntreprise($entreprise),
        ], 'Entreprise creee');
    }

    #[Route('/{id}', name: 'api_entreprises_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $entreprise = $this->entrepriseRepository->find($id);
        
        if (!$entreprise) {
            return $this->notFound('Entreprise non trouvee');
        }
        
        if ($entreprise->getProprietaire()->getId() !== $user->getId() && !$user->isAdmin()) {
            return $this->forbidden();
        }
        
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['nom'])) {
            $entreprise->setNom($data['nom']);
        }
        
        if (isset($data['secteur'])) {
            $entreprise->setSecteur($data['secteur']);
        }
        
        if (isset($data['forme_juridique'])) {
            $entreprise->setFormeJuridique($data['forme_juridique']);
        }
        
        if (isset($data['capital'])) {
            $entreprise->setCapital($data['capital']);
        }
        
        if (isset($data['adresse'])) {
            $entreprise->setAdresse($data['adresse']);
        }
        
        if (isset($data['latitude'])) {
            $entreprise->setLatitude($data['latitude']);
        }
        
        if (isset($data['longitude'])) {
            $entreprise->setLongitude($data['longitude']);
        }
        
        $this->entityManager->flush();
        
        return $this->updated([
            'entreprise' => $this->formatEntreprise($entreprise),
        ]);
    }

    #[Route('/{id}', name: 'api_entreprises_delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $entreprise = $this->entrepriseRepository->find($id);
        
        if (!$entreprise) {
            return $this->notFound('Entreprise non trouvee');
        }
        
        if ($entreprise->getProprietaire()->getId() !== $user->getId() && !$user->isAdmin()) {
            return $this->forbidden();
        }
        
        $this->entityManager->remove($entreprise);
        $this->entityManager->flush();
        
        return $this->deleted('Entreprise supprimee');
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

    private function formatEntreprise(Entreprise $entreprise, bool $detailed = false): array
    {
        $data = [
            'id' => $entreprise->getId(),
            'nom' => $entreprise->getNom(),
            'secteur' => $entreprise->getSecteur(),
            'forme_juridique' => $entreprise->getFormeJuridique(),
            'capital' => $entreprise->getCapital(),
            'adresse' => $entreprise->getAdresse(),
            'latitude' => $entreprise->getLatitude(),
            'longitude' => $entreprise->getLongitude(),
            'date_creation' => $entreprise->getDateCreation()?->format('Y-m-d H:i:s'),
            'proprietaire' => $entreprise->getProprietaire() ? [
                'id' => $entreprise->getProprietaire()->getId(),
                'nom' => $entreprise->getProprietaire()->getNom(),
                'prenom' => $entreprise->getProprietaire()->getPrenom(),
                'full_name' => $entreprise->getProprietaire()->getFullName(),
            ] : null,
        ];
        
        if ($detailed) {
            $data['investissements'] = array_map(fn($i) => [
                'id' => $i->getId(),
                'montant' => $i->getMontant(),
                'statut' => $i->getStatut(),
            ], $entreprise->getInvestissements()->toArray());
            $data['tresoreries'] = array_map(fn($t) => [
                'id' => $t->getId(),
                'solde' => $t->getSolde(),
                'type_compte' => $t->getTypeCompte(),
            ], $entreprise->getTresoreries()->toArray());
        }
        
        return $data;
    }
}
