<?php

namespace App\Controller\Api;

use App\Entity\Investissement;
use App\Entity\Utilisateur;
use App\Repository\EntrepriseRepository;
use App\Repository\InvestissementRepository;
use App\Service\JwtService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/investissements')]
class InvestissementApiController extends BaseApiController
{
    public function __construct(
        private InvestissementRepository $investissementRepository,
        private EntrepriseRepository $entrepriseRepository,
        private EntityManagerInterface $entityManager,
        private ValidationService $validation,
        private JwtService $jwtService
    ) {}

    #[Route('', name: 'api_investissements_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        if ($user->isProprietaire() || $user->isAdmin()) {
            $investissements = $this->investissementRepository->findBy([], ['dateInvestissement' => 'DESC']);
        } else {
            $investissements = $this->investissementRepository->findBy(['investisseur' => $user]);
        }
        
        return $this->success([
            'investissements' => array_map(fn($i) => $this->formatInvestissement($i), $investissements),
            'total' => count($investissements),
            'total_montant' => array_sum(array_map(fn($i) => (float) $i->getMontant(), $investissements)),
        ]);
    }

    #[Route('/actifs', name: 'api_investissements_actifs', methods: ['GET'])]
    public function actifs(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $investissements = $this->investissementRepository->findBy(['statut' => 'ACTIF']);
        
        return $this->success([
            'investissements' => array_map(fn($i) => $this->formatInvestissement($i), $investissements),
            'total' => count($investissements),
        ]);
    }

    #[Route('/en-attente', name: 'api_investissements_en_attente', methods: ['GET'])]
    public function enAttente(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $investissements = $this->investissementRepository->findBy(['statut' => 'EN_ATTENTE']);
        
        return $this->success([
            'investissements' => array_map(fn($i) => $this->formatInvestissement($i), $investissements),
            'total' => count($investissements),
        ]);
    }

    #[Route('/{id}', name: 'api_investissements_show', methods: ['GET'])]
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $investissement = $this->investissementRepository->find($id);
        
        if (!$investissement) {
            return $this->notFound('Investissement non trouve');
        }
        
        return $this->success([
            'investissement' => $this->formatInvestissement($investissement, true),
        ]);
    }

    #[Route('', name: 'api_investissements_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $data = json_decode($request->getContent(), true);
        
        $this->validation->clearErrors();
        if (!$this->validation->validateRequired($data['entreprise_id'] ?? '', 'entreprise_id')) {
            return $this->validationError($this->validation->getErrors());
        }
        
        if (!$this->validation->validatePositiveNumber($data['montant'] ?? '', 'montant')) {
            return $this->validationError($this->validation->getErrors());
        }
        
        $entreprise = $this->entrepriseRepository->find($data['entreprise_id']);
        
        if (!$entreprise) {
            return $this->notFound('Entreprise non trouvee');
        }
        
        $investissement = new Investissement();
        $investissement->setEntreprise($entreprise);
        $investissement->setInvestisseur($user);
        $investissement->setMontant(number_format((float) $data['montant'], 2, '.', ''));
        $investissement->setStatut('EN_ATTENTE');
        $investissement->setDateInvestissement(new \DateTime());
        
        if (isset($data['taux_rendement'])) {
            $investissement->setTauxRendementPrevu(number_format((float) $data['taux_rendement'], 2, '.', ''));
        }
        
        if (isset($data['duree_mois'])) {
            $investissement->setDureeMois((int) $data['duree_mois']);
        }
        
        if (isset($data['description'])) {
            $investissement->setDescription($data['description']);
        }
        
        $this->entityManager->persist($investissement);
        $this->entityManager->flush();
        
        return $this->created([
            'investissement' => $this->formatInvestissement($investissement),
        ], 'Investissement cree');
    }

    #[Route('/{id}', name: 'api_investissements_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $investissement = $this->investissementRepository->find($id);
        
        if (!$investissement) {
            return $this->notFound('Investissement non trouve');
        }
        
        if ($investissement->getInvestisseur()->getId() !== $user->getId() && !$user->isAdmin()) {
            return $this->forbidden();
        }
        
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['montant'])) {
            $investissement->setMontant(number_format((float) $data['montant'], 2, '.', ''));
        }
        
        if (isset($data['taux_rendement'])) {
            $investissement->setTauxRendementPrevu(number_format((float) $data['taux_rendement'], 2, '.', ''));
        }
        
        if (isset($data['duree_mois'])) {
            $investissement->setDureeMois((int) $data['duree_mois']);
        }
        
        if (isset($data['description'])) {
            $investissement->setDescription($data['description']);
        }
        
        if ($user->isAdmin() && isset($data['statut'])) {
            $this->validation->clearErrors();
            if ($this->validation->validateStatut($data['statut'])) {
                $investissement->setStatut($data['statut']);
            }
        }
        
        $this->entityManager->flush();
        
        return $this->updated([
            'investissement' => $this->formatInvestissement($investissement),
        ]);
    }

    #[Route('/{id}/statut', name: 'api_investissements_statut', methods: ['POST'])]
    public function changeStatut(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user || !$user->isAdmin()) {
            return $this->forbidden('Seuls les administrateurs peuvent changer le statut');
        }
        
        $investissement = $this->investissementRepository->find($id);
        
        if (!$investissement) {
            return $this->notFound('Investissement non trouve');
        }
        
        $data = json_decode($request->getContent(), true);
        
        $this->validation->clearErrors();
        if (!$this->validation->validateStatut($data['statut'] ?? '')) {
            return $this->validationError($this->validation->getErrors());
        }
        
        $investissement->setStatut($data['statut']);
        $this->entityManager->flush();
        
        return $this->success([
            'investissement' => $this->formatInvestissement($investissement),
        ], 'Statut mis a jour');
    }

    #[Route('/{id}', name: 'api_investissements_delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $investissement = $this->investissementRepository->find($id);
        
        if (!$investissement) {
            return $this->notFound('Investissement non trouve');
        }
        
        if ($investissement->getInvestisseur()->getId() !== $user->getId() && !$user->isAdmin()) {
            return $this->forbidden();
        }
        
        $this->entityManager->remove($investissement);
        $this->entityManager->flush();
        
        return $this->deleted('Investissement supprime');
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

    private function formatInvestissement(Investissement $investissement, bool $detailed = false): array
    {
        $data = [
            'id' => $investissement->getId(),
            'montant' => $investissement->getMontant(),
            'taux_rendement_prevu' => $investissement->getTauxRendementPrevu(),
            'duree_mois' => $investissement->getDureeMois(),
            'description' => $investissement->getDescription(),
            'statut' => $investissement->getStatut(),
            'date_investissement' => $investissement->getDateInvestissement()?->format('Y-m-d H:i:s'),
            'entreprise' => $investissement->getEntreprise() ? [
                'id' => $investissement->getEntreprise()->getId(),
                'nom' => $investissement->getEntreprise()->getNom(),
                'secteur' => $investissement->getEntreprise()->getSecteur(),
            ] : null,
            'investisseur' => $investissement->getInvestisseur() ? [
                'id' => $investissement->getInvestisseur()->getId(),
                'nom' => $investissement->getInvestisseur()->getNom(),
                'prenom' => $investissement->getInvestisseur()->getPrenom(),
                'full_name' => $investissement->getInvestisseur()->getFullName(),
            ] : null,
        ];
        
        if ($detailed) {
            $data['gain_attendu'] = $investissement->getGainAttendu();
            $data['rendements'] = array_map(fn($r) => [
                'id' => $r->getId(),
                'gain' => $r->getGain(),
                'perte' => $r->getPerte(),
                'valeur_portefeuille' => $r->getValeurPortefeuille(),
                'date_calcul' => $r->getDateCalcul()?->format('Y-m-d'),
            ], $investissement->getRendements()->toArray());
        }
        
        return $data;
    }
}
