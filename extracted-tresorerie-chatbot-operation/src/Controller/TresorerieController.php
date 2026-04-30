<?php

namespace App\Controller;

use App\Entity\Tresorerie;
use App\Entity\Operation;
use App\Form\TresorerieType;
use App\Form\TransfertType;
use App\Repository\TresorerieRepository;
use App\Repository\OperationRepository;
use App\Service\TresorerieAutomationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tresorerie')]
class TresorerieController extends AbstractController
{
    public function __construct(
        private TresorerieAutomationService $tresorerieAutomationService
    ) {}

    #[Route('/virement', name: 'app_tresorerie_virement', methods: ['GET', 'POST'])]
    public function virement(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(TransfertType::class, null, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $source = $data['source'] ?? null;
            $destination = $data['destination'] ?? null;
            $montant = $data['montant'];
            $description = $data['description'] ?? 'Virement interne';

            if (!$source || !$destination) {
                $this->addFlash('error', 'Veuillez sélectionner les comptes source et destination.');
                return $this->redirectToRoute('app_tresorerie_virement');
            }

            if ($source->getId() === $destination->getId()) {
                $this->addFlash('error', 'Le compte source et destination doivent être différents.');
                return $this->redirectToRoute('app_tresorerie_virement');
            }

            // Métier simple #1: un virement interne se fait dans la même entreprise.
            if ($source->getEntreprise()?->getId() !== $destination->getEntreprise()?->getId()) {
                $this->addFlash('error', 'Virement refusé : les deux comptes doivent appartenir à la même entreprise.');
                return $this->redirectToRoute('app_tresorerie_virement');
            }

            if ((float)$source->getSolde() < $montant) {
                $this->addFlash('error', 'Solde insuffisant sur le compte source.');
                return $this->redirectToRoute('app_tresorerie_virement');
            }

            // 1. Débiter la source
            $opSource = new Operation();
            $opSource->setTresorerie($source);
            $opSource->setType('depense');
            $opSource->setMontant($montant);
            $opSource->setReference('VIR-OUT-' . date('Ymd-His'));
            $opSource->setCategorie('Virement Interne');
            $opSource->setDescription($description . ' vers ' . $destination->getNomCompte());
            $opSource->setDateOperation(new \DateTime());
            
            $source->setSolde((string)((float)$source->getSolde() - $montant));
            $source->setDerniereMaj(new \DateTime());

            // 2. Créditer la destination
            $opDest = new Operation();
            $opDest->setTresorerie($destination);
            $opDest->setType('revenu');
            $opDest->setMontant($montant);
            $opDest->setReference('VIR-IN-' . date('Ymd-His'));
            $opDest->setCategorie('Virement Interne');
            $opDest->setDescription($description . ' depuis ' . $source->getNomCompte());
            $opDest->setDateOperation(new \DateTime());

            $destination->setSolde((string)((float)$destination->getSolde() + $montant));
            $destination->setDerniereMaj(new \DateTime());

            $entityManager->persist($opSource);
            $entityManager->persist($opDest);
            $entityManager->flush();

            $this->addFlash('success', 'Virement interne effectué avec succès.');
            return $this->redirectToRoute('app_tresorerie_index');
        }

        return $this->render('tresorerie/virement.html.twig', [
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/export', name: 'app_tresorerie_export', methods: ['GET'])]
    public function export(Request $request, TresorerieRepository $tresorerieRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PROPRIETAIRE');
        $user = $this->getUser();
        
        $entrepriseId = $request->query->get('entreprise');
        $search = $request->query->get('search');
        $typeCompte = $request->query->get('typeCompte');
        
        $qb = $tresorerieRepository->createQueryBuilder('t')
            ->join('t.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->orderBy('t.nom_compte', 'ASC');
            
        if ($entrepriseId) {
            $qb->andWhere('t.entreprise = :entId')
               ->setParameter('entId', $entrepriseId);
        }
        
        if ($search) {
            $qb->andWhere('t.nom_compte LIKE :search OR t.numero_compte LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }
        
        if ($typeCompte) {
            $qb->andWhere('t.typeCompte = :typeCompte')
               ->setParameter('typeCompte', $typeCompte);
        }
        
        $tresoreries = $qb->getQuery()->getResult();
        
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="utf-8">';
        $html .= '<style>';
        $html .= 'table { border-collapse: collapse; font-family: Arial, sans-serif; }';
        $html .= 'th { background-color: #2563eb; color: white; padding: 10px; border: 1px solid #cbd5e1; font-weight: bold; text-align: left; }';
        $html .= 'td { padding: 8px; border: 1px solid #cbd5e1; vertical-align: middle; }';
        $html .= '.title { background-color: #1e3a8a; color: white; font-size: 18px; font-weight: bold; text-align: center; height: 40px; }';
        $html .= '.meta { background-color: #f8fafc; font-weight: bold; color: #475569; }';
        $html .= '.amount { text-align: right; font-weight: bold; color: #0f172a; }';
        $html .= '</style></head><body>';
        $html .= '<table>';
        
        // --- Beautiful Header Section ---
        $html .= '<tr><td colspan="6" class="title">CASHFLY - MES TRÉSORERIES</td></tr>';
        $html .= '<tr><td colspan="3" class="meta">Date d\'export : ' . date('d/m/Y à H:i') . '</td>';
        $html .= '<td colspan="3" class="meta">Total des comptes exportés : ' . count($tresoreries) . '</td></tr>';
        
        $filtres = [];
        if ($search) $filtres[] = "Recherche: '$search'";
        if ($entrepriseId) $filtres[] = "Entreprise filtrée";
        if ($typeCompte) $filtres[] = "Type de compte: '$typeCompte'";
        
        if (!empty($filtres)) {
            $html .= '<tr><td colspan="6" class="meta">Filtres appliqués : ' . implode(' | ', $filtres) . '</td></tr>';
        }
        $html .= '<tr><td colspan="6"></td></tr>'; // Empty line
        
        // Data Headers
        $html .= '<tr>';
        $headers = ['Numéro Compte', 'Nom Compte', 'Type', 'Solde (TND)', 'Dernière Maj', 'Entreprise'];
        foreach ($headers as $h) {
            $html .= "<th>$h</th>";
        }
        $html .= '</tr>';
        
        foreach ($tresoreries as $t) {
            $html .= '<tr>';
            $html .= '<td>' . $t->getNumeroCompte() . '</td>';
            $html .= '<td>' . $t->getNomCompte() . '</td>';
            $html .= '<td>' . ucfirst($t->getTypeCompte() ?? '') . '</td>';
            $html .= '<td class="amount">' . number_format((float)$t->getSolde(), 2, ',', ' ') . '</td>';
            $html .= '<td>' . ($t->getDerniereMaj() ? $t->getDerniereMaj()->format('d/m/Y H:i') : '') . '</td>';
            $html .= '<td>' . ($t->getEntreprise() ? $t->getEntreprise()->getNom() : '') . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table></body></html>';
        
        return new Response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="mes_tresoreries_cashfly_' . date('Y-m-d') . '.xls"'
        ]);
    }

    #[Route('/', name: 'app_tresorerie_index', methods: ['GET'])]
    public function index(Request $request, TresorerieRepository $tresorerieRepository, \App\Repository\EntrepriseRepository $entrepriseRepository, \App\Repository\OperationRepository $operationRepository): Response
    {
        $user = $this->getUser();
        $entrepriseId = $request->query->get('entreprise');
        $search = $request->query->get('search');
        $typeCompte = $request->query->get('typeCompte');
        
        $qb = $tresorerieRepository->createQueryBuilder('t')
            ->join('t.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->orderBy('t.nom_compte', 'ASC');
            
        if ($entrepriseId) {
            $qb->andWhere('t.entreprise = :entId')
               ->setParameter('entId', $entrepriseId);
        }
        
        if ($search) {
            $qb->andWhere('t.nom_compte LIKE :search OR t.numero_compte LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }
        
        if ($typeCompte) {
            $qb->andWhere('t.typeCompte = :typeCompte')
               ->setParameter('typeCompte', $typeCompte);
        }
        
        $tresoreries = $qb->getQuery()->getResult();
        
        // Métier Avancé #1: Prévision de trésorerie (cash flow forecasting)
        $tresorerieForecast = $this->calculateCashFlowForecast($tresoreries, $operationRepository);

        // Métier Avancé #2: Ratio de Liquidité Automatique
        $liquiditeRatio = $this->calculateLiquiditeRatio($tresoreries, $operationRepository);

        $selectedEntreprise = null;
        if ($entrepriseId) {
            $selectedEntreprise = $entrepriseRepository->find($entrepriseId);
        }

        return $this->render('tresorerie/index.html.twig', [
            'tresoreries' => $tresoreries,
            'entreprises' => $entrepriseRepository->findBy(['proprietaire' => $user]),
            'selected_entreprise' => $selectedEntreprise,
            'search' => $search,
            'typeCompte' => $typeCompte,
            'entrepriseId' => $entrepriseId,
            'tresorerieForecast' => $tresorerieForecast,
            'liquiditeRatio' => $liquiditeRatio,
        ]);
    }

    /**
     * Métier Avancé: Calcul du Ratio de Liquidité
     * Ratio courant = Actifs courants / Passifs courants
     * > 1.5 = Excellent (vert)
     * 1.0 - 1.5 = Acceptable (jaune)
     * < 1.0 = Critique (rouge)
     */
    private function calculateLiquiditeRatio(array $tresoreries, \App\Repository\OperationRepository $operationRepository): array
    {
        $totalActifs = 0;
        $totalPassifs = 0;
        $ratioParEntreprise = [];

        foreach ($tresoreries as $tresorerie) {
            $solde = (float) $tresorerie->getSolde();
            $entrepriseNom = $tresorerie->getEntreprise()?->getNom() ?? 'Unknown';
            $entrepriseId = $tresorerie->getEntreprise()?->getId() ?? 0;

            if (!isset($ratioParEntreprise[$entrepriseId])) {
                $ratioParEntreprise[$entrepriseId] = [
                    'nom' => $entrepriseNom,
                    'actifs' => 0,
                    'passifs' => 0,
                ];
            }

            if ($solde >= 0) {
                $ratioParEntreprise[$entrepriseId]['actifs'] += $solde;
            } else {
                $ratioParEntreprise[$entrepriseId]['passifs'] += abs($solde);
            }

            $totalActifs += max(0, $solde);
            $totalPassifs += abs(min(0, $solde));
        }

        $totalRatio = $totalPassifs > 0 ? $totalActifs / $totalPassifs : ($totalActifs > 0 ? 99.99 : 0);

        $status = 'critique';
        $statusLabel = 'Critique';
        $statusColor = 'error';

        if ($totalRatio >= 1.5) {
            $status = 'excellent';
            $statusLabel = 'Excellent';
            $statusColor = 'green';
        } elseif ($totalRatio >= 1.0) {
            $status = 'acceptable';
            $statusLabel = 'Acceptable';
            $statusColor = 'amber';
        }

        $entrepriseRatios = [];
        foreach ($ratioParEntreprise as $entId => $data) {
            $ratio = $data['passifs'] > 0 ? $data['actifs'] / $data['passifs'] : ($data['actifs'] > 0 ? 99.99 : 0);

            $entStatus = $ratio >= 1.5 ? 'excellent' : ($ratio >= 1.0 ? 'acceptable' : 'critique');
            $entColor = $entStatus === 'excellent' ? 'green' : ($entStatus === 'acceptable' ? 'amber' : 'error');

            $entrepriseRatios[] = [
                'nom' => $data['nom'],
                'ratio' => round($ratio, 2),
                'actifs' => round($data['actifs'], 2),
                'passifs' => round($data['passifs'], 2),
                'status' => $entStatus,
                'color' => $entColor,
            ];
        }

        return [
            'ratio' => round($totalRatio, 2),
            'actifs' => round($totalActifs, 2),
            'passifs' => round($totalPassifs, 2),
            'status' => $status,
            'status_label' => $statusLabel,
            'color' => $statusColor,
            'entreprises' => $entrepriseRatios,
        ];
    }

    /**
     * Métier Avancé: Prévision de trésorerie sur 30 jours
     * Analyse les opérations récurrentes pour projeter le solde futur
     */
    private function calculateCashFlowForecast(array $tresoreries, \App\Repository\OperationRepository $operationRepository): array
    {
        $forecast = [];
        $totalCurrentBalance = 0;
        $alerts = [];
        
        foreach ($tresoreries as $tresorerie) {
            $currentBalance = (float) $tresorerie->getSolde();
            $totalCurrentBalance += $currentBalance;
            
            // Récupérer les opérations des 90 derniers jours
            $operations = $operationRepository->createQueryBuilder('o')
                ->where('o.tresorerie = :tresorerie')
                ->andWhere('o.dateOperation >= :startDate')
                ->setParameter('tresorerie', $tresorerie)
                ->setParameter('startDate', new \DateTime('-90 days'))
                ->orderBy('o.dateOperation', 'ASC')
                ->getQuery()
                ->getResult();
            
            if (count($operations) === 0) {
                continue;
            }
            
            // Calculer les moyennes quotidiennes
            $dailyRevenus = 0;
            $dailyDepenses = 0;
            $revenuCount = 0;
            $depenseCount = 0;
            
            foreach ($operations as $op) {
                if ($op->getType() === 'revenu') {
                    $dailyRevenus += (float) $op->getMontant();
                    $revenuCount++;
                } else {
                    $dailyDepenses += (float) $op->getMontant();
                    $depenseCount++;
                }
            }
            
            // Moyennes sur 90 jours
            $avgDailyRevenu = $revenuCount > 0 ? $dailyRevenus / 90 : 0;
            $avgDailyDepense = $depenseCount > 0 ? $dailyDepenses / 90 : 0;
            
            // Projections sur 30 jours
            $projectedBalance = $currentBalance;
            $projections = [];
            $negativeDate = null;
            
            for ($day = 1; $day <= 30; $day++) {
                $projectedBalance += $avgDailyRevenu;
                $projectedBalance -= $avgDailyDepense;
                $projections[] = [
                    'day' => $day,
                    'date' => (new \DateTime('+' . $day . ' days'))->format('d/m'),
                    'balance' => $projectedBalance,
                    'expected_revenu' => $avgDailyRevenu,
                    'expected_depense' => $avgDailyDepense,
                ];
                
                if ($projectedBalance < 0 && $negativeDate === null) {
                    $negativeDate = $day;
                }
            }
            
            $forecast[$tresorerie->getId()] = [
                'tresorerie' => $tresorerie,
                'current_balance' => $currentBalance,
                'avg_daily_revenu' => $avgDailyRevenu,
                'avg_daily_depense' => $avgDailyDepense,
                'projections' => $projections,
                'negative_date' => $negativeDate,
                'risk_level' => $this->calculateRiskLevel($currentBalance, $avgDailyDepense, $negativeDate),
            ];
            
            // Alertes critiques
            if ($negativeDate !== null && $negativeDate <= 7) {
                $alerts[] = [
                    'type' => 'critical',
                    'message' => '⚠️ CRITIQUE: Le compte "' . $tresorerie->getNomCompte() . '" sera en déficit dans ' . $negativeDate . ' jour(s)',
                    'tresorerie' => $tresorerie,
                ];
            } elseif ((float)$tresorerie->getSolde() < 100) {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => '⚡ ALERTE: Solde très bas (' . number_format($currentBalance, 2) . ' TND) sur "' . $tresorerie->getNomCompte() . '"',
                    'tresorerie' => $tresorerie,
                ];
            }
        }
        
        return [
            'total_balance' => $totalCurrentBalance,
            'accounts' => $forecast,
            'alerts' => $alerts,
            'average_daily_revenu' => array_sum(array_column($forecast, 'avg_daily_revenu')),
            'average_daily_depense' => array_sum(array_column($forecast, 'avg_daily_depense')),
        ];
    }
    
    /**
     * Calcul du niveau de risque
     */
    private function calculateRiskLevel(float $balance, float $avgDailyDepense, ?int $negativeDate): string
    {
        if ($negativeDate !== null && $negativeDate <= 7) {
            return 'critical';
        }
        
        $daysUntilZero = $avgDailyDepense > 0 ? $balance / $avgDailyDepense : 999;
        
        if ($daysUntilZero < 14) {
            return 'high';
        } elseif ($daysUntilZero < 30) {
            return 'medium';
        }
        
        return 'low';
    }

    #[Route('/new', name: 'app_tresorerie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, \App\Service\CurrencyConverterService $currencyConverterService): Response
    {
        $tresorerie = new Tresorerie();
        
        // Pre-fill entreprise if provided in URL
        $entrepriseId = $request->query->get('entreprise');
        if ($entrepriseId) {
            $entreprise = $entityManager->getRepository(\App\Entity\Entreprise::class)->find($entrepriseId);
            if ($entreprise) {
                $tresorerie->setEntreprise($entreprise);
            }
        }

        $form = $this->createForm(TresorerieType::class, $tresorerie, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if currency conversion is needed
            $devise = $form->get('devise')->getData();
            $solde = (float) $tresorerie->getSolde();
            $deviseCible = 'TND';
            
            if ($devise && $devise !== $deviseCible) {
                $convertedAmount = $currencyConverterService->convertir($solde, $devise, $deviseCible);
                $tresorerie->setSolde((string) $convertedAmount);
                $tresorerie->setDevise($deviseCible); // Save as TND in database
                $this->addFlash('info', 'Le solde initial a été automatiquement converti de ' . $devise . ' vers ' . $deviseCible . '.');
            }

            $tresorerie->setDerniereMaj(new \DateTime());
            if (!$tresorerie->getNumeroCompte()) {
                // Métier simple #2: si numéro absent, génération automatique.
                $tresorerie->setNumeroCompte($this->tresorerieAutomationService->generateNumeroCompte());
            }
            
            $entityManager->persist($tresorerie);

            // Créer une opération initiale si le solde est > 0
            $this->tresorerieAutomationService->createInitialOperation($tresorerie);

            $entityManager->flush();

            $this->addFlash('success', 'Trésorerie created successfully.');
            
            $params = [];
            if ($tresorerie->getEntreprise()) {
                $params['entreprise'] = $tresorerie->getEntreprise()->getId();
            }
            
            return $this->redirectToRoute('app_tresorerie_index', $params, Response::HTTP_SEE_OTHER);
        }

        return $this->render('tresorerie/new.html.twig', [
            'tresorerie' => $tresorerie,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    /**
     * Vérifie si l'utilisateur courant a le droit d'accéder à la trésorerie
     */
    private function checkTresorerieAccess(Tresorerie $tresorerie): void
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_ADMIN') && $tresorerie->getEntreprise()->getProprietaire() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette trésorerie.');
        }
    }

    #[Route('/{id}', name: 'app_tresorerie_show', methods: ['GET'])]
    public function show(Tresorerie $tresorerie, \App\Repository\OperationRepository $operationRepository): Response
    {
        $this->checkTresorerieAccess($tresorerie);
        return $this->render('tresorerie/show.html.twig', [
            'tresorerie' => $tresorerie,
            'operations' => $operationRepository->findBy(['tresorerie' => $tresorerie], ['dateOperation' => 'DESC']),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tresorerie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tresorerie $tresorerie, EntityManagerInterface $entityManager, \App\Service\CurrencyConverterService $currencyConverterService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PROPRIETAIRE');
        $this->checkTresorerieAccess($tresorerie);
        $oldSolde = (float) $tresorerie->getSolde();
        
        $form = $this->createForm(TresorerieType::class, $tresorerie, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if currency conversion is needed on the newly input balance
            $devise = $form->get('devise')->getData();
            $newSolde = (float) $tresorerie->getSolde();
            $deviseCible = 'TND';
            
            // Only convert if the currency is not TND, meaning they updated it in the form
            if ($devise && $devise !== $deviseCible) {
                $convertedAmount = $currencyConverterService->convertir($newSolde, $devise, $deviseCible);
                $tresorerie->setSolde((string) $convertedAmount);
                $tresorerie->setDevise($deviseCible); // Save as TND in database
                $this->addFlash('info', 'Le nouveau solde a été automatiquement converti de ' . $devise . ' vers ' . $deviseCible . '.');
            }

            $tresorerie->setDerniereMaj(new \DateTime());
            $entityManager->flush();

            $this->addFlash('success', 'Trésorerie updated successfully.');
            return $this->redirectToRoute('app_tresorerie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tresorerie/edit.html.twig', [
            'tresorerie' => $tresorerie,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{id}', name: 'app_tresorerie_delete', methods: ['POST'])]
    public function delete(Request $request, Tresorerie $tresorerie, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PROPRIETAIRE');
        $this->checkTresorerieAccess($tresorerie);
        if ($this->isCsrfTokenValid('delete'.$tresorerie->getId(), $request->get('_token'))) {
            $entityManager->remove($tresorerie);
            $entityManager->flush();
            $this->addFlash('success', 'Trésorerie deleted successfully.');
        }

        return $this->redirectToRoute('app_tresorerie_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/api/summary', name: 'api_tresorerie_summary', methods: ['GET'])]
    public function apiSummary(TresorerieRepository $tresorerieRepository): JsonResponse
    {
        $user = $this->getUser();
        $rows = $tresorerieRepository->createQueryBuilder('t')
            ->select('e.id AS entrepriseId, e.nom AS entrepriseNom, SUM(t.solde) AS totalSolde, COUNT(t.id) AS comptes')
            ->join('t.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->groupBy('e.id')
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->json([
            'success' => true,
            'data' => $rows,
        ]);
    }
}
