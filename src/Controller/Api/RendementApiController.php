<?php

namespace App\Controller\Api;

use App\Entity\RendementInvestissement;
use App\Entity\Utilisateur;
use App\Repository\InvestissementRepository;
use App\Repository\RendementInvestissementRepository;
use App\Service\JwtService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/rendements')]
class RendementApiController extends BaseApiController
{
    public function __construct(
        private RendementInvestissementRepository $rendementRepository,
        private InvestissementRepository $investissementRepository,
        private EntityManagerInterface $entityManager,
        private JwtService $jwtService
    ) {}

    #[Route('', name: 'api_rendements_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $rendements = $this->rendementRepository->findBy([], ['dateCalcul' => 'DESC']);
        
        $totalGain = $this->rendementRepository->getTotalGains();
        $totalPerte = $this->rendementRepository->getTotalPertes();
        
        return $this->success([
            'rendements' => array_map(fn($r) => $this->formatRendement($r), $rendements),
            'total' => count($rendements),
            'total_gain' => $totalGain,
            'total_perte' => $totalPerte,
            'rendement_net' => $totalGain - $totalPerte,
        ]);
    }

    #[Route('/by-investissement/{investissementId}', name: 'api_rendements_by_investissement', methods: ['GET'])]
    public function byInvestissement(int $investissementId, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $investissement = $this->investissementRepository->find($investissementId);
        
        if (!$investissement) {
            return $this->notFound('Investissement non trouve');
        }
        
        $rendements = $this->rendementRepository->findBy(['investissement' => $investissement]);
        
        return $this->success([
            'rendements' => array_map(fn($r) => $this->formatRendement($r), $rendements),
            'total' => count($rendements),
        ]);
    }

    #[Route('/{id}', name: 'api_rendements_show', methods: ['GET'])]
    public function show(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $rendement = $this->rendementRepository->find($id);
        
        if (!$rendement) {
            return $this->notFound('Rendement non trouve');
        }
        
        return $this->success([
            'rendement' => $this->formatRendement($rendement, true),
        ]);
    }

    #[Route('', name: 'api_rendements_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['investissement_id'])) {
            return $this->validationError(['investissement_id' => 'ID investissement requis']);
        }
        
        $investissement = $this->investissementRepository->find($data['investissement_id']);
        
        if (!$investissement) {
            return $this->notFound('Investissement non trouve');
        }
        
        $rendement = new RendementInvestissement();
        $rendement->setInvestissement($investissement);
        $rendement->setDateCalcul(new \DateTime($data['date_calcul'] ?? 'now'));
        $rendement->setGain($data['gain'] ?? '0.00');
        $rendement->setPerte($data['perte'] ?? '0.00');
        $rendement->setValeurPortefeuille($data['valeur_portefeuille'] ?? '0.00');
        
        $this->entityManager->persist($rendement);
        $this->entityManager->flush();
        
        return $this->created([
            'rendement' => $this->formatRendement($rendement),
        ], 'Rendement cree');
    }

    #[Route('/{id}', name: 'api_rendements_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $rendement = $this->rendementRepository->find($id);
        
        if (!$rendement) {
            return $this->notFound('Rendement non trouve');
        }
        
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['gain'])) {
            $rendement->setGain(number_format((float) $data['gain'], 2, '.', ''));
        }
        
        if (isset($data['perte'])) {
            $rendement->setPerte(number_format((float) $data['perte'], 2, '.', ''));
        }
        
        if (isset($data['valeur_portefeuille'])) {
            $rendement->setValeurPortefeuille(number_format((float) $data['valeur_portefeuille'], 2, '.', ''));
        }
        
        if (isset($data['date_calcul'])) {
            $rendement->setDateCalcul(new \DateTime($data['date_calcul']));
        }
        
        $this->entityManager->flush();
        
        return $this->updated([
            'rendement' => $this->formatRendement($rendement),
        ]);
    }

    #[Route('/{id}', name: 'api_rendements_delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        $user = $this->getUserFromRequest($request);
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        $rendement = $this->rendementRepository->find($id);
        
        if (!$rendement) {
            return $this->notFound('Rendement non trouve');
        }
        
        $this->entityManager->remove($rendement);
        $this->entityManager->flush();
        
        return $this->deleted('Rendement supprime');
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

    private function formatRendement(RendementInvestissement $rendement, bool $detailed = false): array
    {
        $data = [
            'id' => $rendement->getIdRendement(),
            'gain' => $rendement->getGain(),
            'perte' => $rendement->getPerte(),
            'valeur_portefeuille' => $rendement->getValeurPortefeuille(),
            'rendement_net' => $rendement->getRendementNet(),
            'date_calcul' => $rendement->getDateCalcul()?->format('Y-m-d'),
            'investissement' => $rendement->getInvestissement() ? [
                'id' => $rendement->getInvestissement()->getId(),
                'montant' => $rendement->getInvestissement()->getMontant(),
            ] : null,
        ];
        
        if ($detailed) {
            $data['investissement_details'] = $rendement->getInvestissement() ? [
                'id' => $rendement->getInvestissement()->getId(),
                'montant' => $rendement->getInvestissement()->getMontant(),
                'taux_rendement' => $rendement->getInvestissement()->getTauxRendementPrevu(),
                'entreprise' => $rendement->getInvestissement()->getEntreprise()?->getNom(),
            ] : null;
        }
        
        return $data;
    }
}
