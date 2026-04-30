<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Form\OperationType;
use App\Repository\OperationRepository;
use App\Service\CurrencyConverterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/operation')]
class OperationController extends AbstractController
{
    #[Route('/admin/list', name: 'app_operation_admin_index', methods: ['GET'])]
    public function adminIndex(Request $request, OperationRepository $operationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $search = $request->query->get('search');
        $type = $request->query->get('type');
        $date = $request->query->get('date');
        $categorie = $request->query->get('categorie');
        
        $qb = $operationRepository->createQueryBuilder('o')
            ->join('o.tresorerie', 't')
            ->join('t.entreprise', 'e')
            ->join('e.proprietaire', 'p')
            ->orderBy('o.dateOperation', 'DESC');
        
        if ($search) {
            $qb->andWhere('o.reference LIKE :search OR o.description LIKE :search OR o.categorie LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }
        
        if ($type) {
            $qb->andWhere('o.type = :type')->setParameter('type', $type);
        }
        
        if ($date) {
            $qb->andWhere('DATE(o.dateOperation) = :date')->setParameter('date', $date);
        }
        
        if ($categorie) {
            $qb->andWhere('o.categorie = :categorie')->setParameter('categorie', $categorie);
        }
        
        $operations = $qb->getQuery()->getResult();
        
        return $this->render('operation/admin_index.html.twig', [
            'operations' => $operations,
            'search' => $search,
            'type' => $type,
            'date' => $date,
            'categorie' => $categorie,
        ]);
    }

    #[Route('/admin/export', name: 'app_operation_admin_export', methods: ['GET'])]
    public function adminExport(Request $request, OperationRepository $operationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $search = $request->query->get('search');
        $type = $request->query->get('type');
        $date = $request->query->get('date');
        $categorie = $request->query->get('categorie');
        
        $qb = $operationRepository->createQueryBuilder('o')
            ->join('o.tresorerie', 't')
            ->join('t.entreprise', 'e')
            ->join('e.proprietaire', 'p')
            ->orderBy('o.dateOperation', 'DESC');
        
        if ($search) {
            $qb->andWhere('o.reference LIKE :search OR o.description LIKE :search OR o.categorie LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }
        
        if ($type) {
            $qb->andWhere('o.type = :type')->setParameter('type', $type);
        }
        
        if ($date) {
            $qb->andWhere('DATE(o.dateOperation) = :date')->setParameter('date', $date);
        }
        
        if ($categorie) {
            $qb->andWhere('o.categorie = :categorie')->setParameter('categorie', $categorie);
        }
        
        $operations = $qb->getQuery()->getResult();
        
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="utf-8">';
        $html .= '<style>';
        $html .= 'table { border-collapse: collapse; font-family: Arial, sans-serif; }';
        $html .= 'th { background-color: #2563eb; color: white; padding: 10px; border: 1px solid #cbd5e1; font-weight: bold; text-align: left; }';
        $html .= 'td { padding: 8px; border: 1px solid #cbd5e1; vertical-align: middle; }';
        $html .= '.title { background-color: #1e3a8a; color: white; font-size: 18px; font-weight: bold; text-align: center; height: 40px; }';
        $html .= '.meta { background-color: #f8fafc; font-weight: bold; color: #475569; }';
        $html .= '.revenu { color: #059669; font-weight: bold; background-color: #ecfdf5; }';
        $html .= '.depense { color: #dc2626; font-weight: bold; background-color: #fef2f2; }';
        $html .= '.amount { text-align: right; }';
        $html .= '</style></head><body>';
        $html .= '<table>';
        
        // --- Beautiful Header Section ---
        $html .= '<tr><td colspan="10" class="title">CASHFLY - RAPPORT DES OPÉRATIONS FINANCIÈRES</td></tr>';
        $html .= '<tr><td colspan="5" class="meta">Date d\'export : ' . date('d/m/Y à H:i') . '</td>';
        $html .= '<td colspan="5" class="meta">Total des opérations exportées : ' . count($operations) . '</td></tr>';
        
        $filtres = [];
        if ($search) $filtres[] = "Recherche: '$search'";
        if ($type) $filtres[] = "Type: '$type'";
        if ($date) $filtres[] = "Date: '$date'";
        if ($categorie) $filtres[] = "Catégorie: '$categorie'";
        
        if (!empty($filtres)) {
            $html .= '<tr><td colspan="10" class="meta">Filtres appliqués : ' . implode(' | ', $filtres) . '</td></tr>';
        }
        $html .= '<tr><td colspan="10"></td></tr>'; // Empty line
        
        // Data Headers
        $html .= '<tr>';
        $headers = ['ID', 'Référence', 'Type', 'Montant (TND)', 'Catégorie', 'Description', 'Date', 'Entreprise', 'Compte', 'Propriétaire'];
        foreach ($headers as $h) {
            $html .= "<th>$h</th>";
        }
        $html .= '</tr>';
        
        foreach ($operations as $op) {
            $typeClass = strtolower($op->getType()) === 'revenu' ? 'revenu' : 'depense';
            $html .= '<tr>';
            $html .= '<td>' . $op->getId() . '</td>';
            $html .= '<td>' . ($op->getReference() ?? '') . '</td>';
            $html .= '<td class="' . $typeClass . '">' . ucfirst($op->getType() ?? '') . '</td>';
            $html .= '<td class="amount ' . $typeClass . '">' . number_format((float)$op->getMontant(), 2, ',', ' ') . '</td>';
            $html .= '<td>' . ($op->getCategorie() ?? '') . '</td>';
            $html .= '<td>' . strip_tags($op->getDescription() ?? '') . '</td>';
            $html .= '<td>' . ($op->getDateOperation() ? $op->getDateOperation()->format('d/m/Y H:i') : '') . '</td>';
            $html .= '<td>' . ($op->getTresorerie() && $op->getTresorerie()->getEntreprise() ? $op->getTresorerie()->getEntreprise()->getNom() : '') . '</td>';
            $html .= '<td>' . ($op->getTresorerie() ? $op->getTresorerie()->getNomCompte() : '') . '</td>';
            $html .= '<td>' . ($op->getTresorerie() && $op->getTresorerie()->getEntreprise() && $op->getTresorerie()->getEntreprise()->getProprietaire() ? $op->getTresorerie()->getEntreprise()->getProprietaire()->getFullName() : '') . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table></body></html>';
        
        return new Response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="export_operations_cashfly_' . date('Y-m-d') . '.xls"'
        ]);
    }

    #[Route('/export', name: 'app_operation_export', methods: ['GET'])]
    public function export(Request $request, OperationRepository $operationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PROPRIETAIRE');
        $user = $this->getUser();
        
        $entrepriseId = $request->query->get('entreprise');
        $tresorerieId = $request->query->get('tresorerie');
        $date = $request->query->get('date');
        $type = $request->query->get('type');
        $search = $request->query->get('search');

        $qb = $operationRepository->createQueryBuilder('o')
            ->join('o.tresorerie', 't')
            ->join('t.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->orderBy('o.dateOperation', 'DESC');
        
        if ($search) {
            $qb->andWhere('o.reference LIKE :search OR o.description LIKE :search OR o.categorie LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($entrepriseId) {
            $qb->andWhere('t.entreprise = :entId')
               ->setParameter('entId', $entrepriseId);
        }

        if ($tresorerieId) {
            $qb->andWhere('o.tresorerie = :tresId')
               ->setParameter('tresId', $tresorerieId);
        }
        
        if ($type) {
            $qb->andWhere('o.type = :type')
               ->setParameter('type', $type);
        }
        
        if ($date) {
            $qb->andWhere('o.dateOperation LIKE :date')
               ->setParameter('date', $date . '%');
        }
        
        $operations = $qb->getQuery()->getResult();
        
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="utf-8">';
        $html .= '<style>';
        $html .= 'table { border-collapse: collapse; font-family: Arial, sans-serif; }';
        $html .= 'th { background-color: #2563eb; color: white; padding: 10px; border: 1px solid #cbd5e1; font-weight: bold; text-align: left; }';
        $html .= 'td { padding: 8px; border: 1px solid #cbd5e1; vertical-align: middle; }';
        $html .= '.title { background-color: #1e3a8a; color: white; font-size: 18px; font-weight: bold; text-align: center; height: 40px; }';
        $html .= '.meta { background-color: #f8fafc; font-weight: bold; color: #475569; }';
        $html .= '.revenu { color: #059669; font-weight: bold; background-color: #ecfdf5; }';
        $html .= '.depense { color: #dc2626; font-weight: bold; background-color: #fef2f2; }';
        $html .= '.amount { text-align: right; }';
        $html .= '</style></head><body>';
        $html .= '<table>';
        
        // --- Beautiful Header Section ---
        $html .= '<tr><td colspan="8" class="title">CASHFLY - MES OPÉRATIONS FINANCIÈRES</td></tr>';
        $html .= '<tr><td colspan="4" class="meta">Date d\'export : ' . date('d/m/Y à H:i') . '</td>';
        $html .= '<td colspan="4" class="meta">Total des opérations exportées : ' . count($operations) . '</td></tr>';
        
        $filtres = [];
        if ($search) $filtres[] = "Recherche: '$search'";
        if ($type) $filtres[] = "Type: '$type'";
        if ($date) $filtres[] = "Date: '$date'";
        if ($entrepriseId) $filtres[] = "Entreprise filtrée";
        if ($tresorerieId) $filtres[] = "Compte filtré";
        
        if (!empty($filtres)) {
            $html .= '<tr><td colspan="8" class="meta">Filtres appliqués : ' . implode(' | ', $filtres) . '</td></tr>';
        }
        $html .= '<tr><td colspan="8"></td></tr>'; // Empty line
        
        // Data Headers
        $html .= '<tr>';
        $headers = ['Référence', 'Type', 'Montant (TND)', 'Catégorie', 'Description', 'Date', 'Entreprise', 'Compte'];
        foreach ($headers as $h) {
            $html .= "<th>$h</th>";
        }
        $html .= '</tr>';
        
        foreach ($operations as $op) {
            $typeClass = strtolower($op->getType()) === 'revenu' ? 'revenu' : 'depense';
            $html .= '<tr>';
            $html .= '<td>' . ($op->getReference() ?? '') . '</td>';
            $html .= '<td class="' . $typeClass . '">' . ucfirst($op->getType() ?? '') . '</td>';
            $html .= '<td class="amount ' . $typeClass . '">' . number_format((float)$op->getMontant(), 2, ',', ' ') . '</td>';
            $html .= '<td>' . ($op->getCategorie() ?? '') . '</td>';
            $html .= '<td>' . strip_tags($op->getDescription() ?? '') . '</td>';
            $html .= '<td>' . ($op->getDateOperation() ? $op->getDateOperation()->format('d/m/Y H:i') : '') . '</td>';
            $html .= '<td>' . ($op->getTresorerie() && $op->getTresorerie()->getEntreprise() ? $op->getTresorerie()->getEntreprise()->getNom() : '') . '</td>';
            $html .= '<td>' . ($op->getTresorerie() ? $op->getTresorerie()->getNomCompte() : '') . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table></body></html>';
        
        return new Response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="mes_operations_cashfly_' . date('Y-m-d') . '.xls"'
        ]);
    }

    #[Route('/', name: 'app_operation_index', methods: ['GET'])]
    public function index(Request $request, OperationRepository $operationRepository, \App\Repository\EntrepriseRepository $entrepriseRepository, \App\Repository\TresorerieRepository $tresorerieRepository): Response
    {
        $user = $this->getUser();
        $entrepriseId = $request->query->get('entreprise');
        $tresorerieId = $request->query->get('tresorerie');
        $date = $request->query->get('date');
        $type = $request->query->get('type');
        $search = $request->query->get('search');

        $qb = $operationRepository->createQueryBuilder('o')
            ->join('o.tresorerie', 't')
            ->join('t.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->orderBy('o.dateOperation', 'DESC');
        
        if ($search) {
            $qb->andWhere('o.reference LIKE :search OR o.description LIKE :search OR o.categorie LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($entrepriseId) {
            $qb->andWhere('t.entreprise = :entId')
               ->setParameter('entId', $entrepriseId);
        }

        if ($tresorerieId) {
            $qb->andWhere('o.tresorerie = :tresId')
               ->setParameter('tresId', $tresorerieId);
        }
        
        if ($type) {
            $qb->andWhere('o.type = :type')
               ->setParameter('type', $type);
        }
        
        if ($date) {
            $qb->andWhere('o.dateOperation LIKE :date')
               ->setParameter('date', $date . '%');
        }
        
        $operations = $qb->getQuery()->getResult();
        
        // Métier Avancé: Analyse Mensuelle et Détection d'Anomalies
        $currentMonthStart = new \DateTime('first day of this month 00:00:00');
        $monthlyRevenu = 0.0;
        $monthlyDepense = 0.0;
        $anomalies = [];
        
        // Calcul des moyennes par catégorie pour la détection d'anomalies
        $categoryTotals = [];
        $categoryCounts = [];

        foreach ($operations as $op) {
            $cat = $op->getCategorie() ?: 'Autre';
            $amount = (float) $op->getMontant();
            
            if ($op->getDateOperation() >= $currentMonthStart) {
                if ($op->getType() === 'revenu') {
                    $monthlyRevenu += $amount;
                } else {
                    $monthlyDepense += $amount;
                }
            }
            
            if ($op->getType() === 'depense') {
                if (!isset($categoryTotals[$cat])) {
                    $categoryTotals[$cat] = 0;
                    $categoryCounts[$cat] = 0;
                }
                $categoryTotals[$cat] += $amount;
                $categoryCounts[$cat]++;
            }
        }
        
        // Détection: si une dépense ce mois-ci est > 200% de la moyenne historique de sa catégorie
        foreach ($operations as $op) {
            if ($op->getType() === 'depense' && $op->getDateOperation() >= $currentMonthStart) {
                $cat = $op->getCategorie() ?: 'Autre';
                $avg = $categoryCounts[$cat] > 1 ? ($categoryTotals[$cat] - $op->getMontant()) / ($categoryCounts[$cat] - 1) : 0;
                if ($avg > 0 && $op->getMontant() > ($avg * 2)) {
                    $anomalies[] = $op;
                }
            }
        }

        // Métier Avancé #2: Analyse Saisonnière des Opérations
        $seasonalAnalysis = $this->analyzeSeasonalPatterns($operations);

        // Métier Avancé #3: Alertes de Paiements Récurrents
        $recurringAlerts = $this->detectRecurringPaymentAlerts($operations, $tresorerieRepository, $user);

        $selectedTresorerie = null;
        if ($tresorerieId) {
            $selectedTresorerie = $tresorerieRepository->find($tresorerieId);
        }

        return $this->render('operation/index.html.twig', [
            'operations' => $operations,
            'entreprises' => $entrepriseRepository->findBy(['proprietaire' => $user]),
            'tresoreries' => $tresorerieRepository->createQueryBuilder('tr')
                ->join('tr.entreprise', 'ent')
                ->where('ent.proprietaire = :u')
                ->setParameter('u', $user)
                ->getQuery()
                ->getResult(),
            'selected_tresorerie' => $selectedTresorerie,
            'search' => $search,
            'type' => $type,
            'date' => $date,
            'entrepriseId' => $entrepriseId,
            'tresorerieId' => $tresorerieId,
            'monthlyRevenu' => $monthlyRevenu,
            'monthlyDepense' => $monthlyDepense,
            'anomalies' => $anomalies,
            'seasonalAnalysis' => $seasonalAnalysis,
            'recurringAlerts' => $recurringAlerts,
        ]);
    }

    /**
     * Métier Avancé: Analyse Saisonnière des Opérations
     * Détecte les patterns récurrents par mois et calcule la variance saisonnière
     */
    private function analyzeSeasonalPatterns(array $operations): array
    {
        $monthlyData = [];
        $categoryMonthly = [];

        foreach ($operations as $op) {
            $date = $op->getDateOperation();
            if (!$date) continue;

            $monthKey = $date->format('Y-m');
            $cat = $op->getCategorie() ?: 'Autre';

            if (!isset($monthlyData[$monthKey])) {
                $monthlyData[$monthKey] = ['revenu' => 0, 'depense' => 0, 'count' => 0];
            }

            $monthlyData[$monthKey]['count']++;
            if ($op->getType() === 'revenu') {
                $monthlyData[$monthKey]['revenu'] += (float) $op->getMontant();
            } else {
                $monthlyData[$monthKey]['depense'] += (float) $op->getMontant();
            }

            if (!isset($categoryMonthly[$cat])) {
                $categoryMonthly[$cat] = [];
            }
            if (!isset($categoryMonthly[$cat][$monthKey])) {
                $categoryMonthly[$cat][$monthKey] = 0;
            }
            $categoryMonthly[$cat][$monthKey] += (float) $op->getMontant();
        }

        ksort($monthlyData);

        $averages = ['revenu' => 0, 'depense' => 0];
        $monthCount = count($monthlyData);
        foreach ($monthlyData as $data) {
            $averages['revenu'] += $data['revenu'];
            $averages['depense'] += $data['depense'];
        }
        $averages['revenu'] = $monthCount > 0 ? $averages['revenu'] / $monthCount : 0;
        $averages['depense'] = $monthCount > 0 ? $averages['depense'] / $monthCount : 0;

        $seasonalVariance = [];
        foreach ($monthlyData as $month => $data) {
            $revenuVariance = $averages['revenu'] > 0
                ? (($data['revenu'] - $averages['revenu']) / $averages['revenu']) * 100
                : 0;
            $depenseVariance = $averages['depense'] > 0
                ? (($data['depense'] - $averages['depense']) / $averages['depense']) * 100
                : 0;

            $monthName = (new \DateTime($month . '-01'))->format('M Y');
            $seasonalVariance[] = [
                'month' => $monthName,
                'revenu' => $data['revenu'],
                'depense' => $data['depense'],
                'revenu_variance' => round($revenuVariance, 1),
                'depense_variance' => round($depenseVariance, 1),
                'is_strong' => abs($revenuVariance) > 20 || abs($depenseVariance) > 20,
            ];
        }

        return [
            'months' => $seasonalVariance,
            'averages' => $averages,
            'total_months' => $monthCount,
        ];
    }

    /**
     * Métier Avancé: Détection des Alertes de Paiements Récurrents
     * Alerte si un paiement récurrent (loyer, salaire) est manquant ou a changé significativement
     */
    private function detectRecurringPaymentAlerts(array $operations, \App\Repository\TresorerieRepository $tresorerieRepository, $user): array
    {
        $alerts = [];
        $recurringCategories = ['Salaire', 'Loyer', 'Téléphone', 'Électricité', 'Eau', 'Assurance'];

        $recentOps = array_filter($operations, function($op) {
            $date = $op->getDateOperation();
            return $date && $date >= new \DateTime('-90 days');
        });

        usort($recentOps, fn($a, $b) => $b->getDateOperation() <=> $a->getDateOperation());

        $categoryLastDate = [];
        $categoryLastAmount = [];
        $categoryAmounts = [];

        foreach ($recentOps as $op) {
            $cat = $op->getCategorie() ?: 'Autre';
            $type = $op->getType();

            if (in_array($cat, $recurringCategories) && $type === 'depense') {
                $date = $op->getDateOperation();
                if (!isset($categoryLastDate[$cat]) || $date > $categoryLastDate[$cat]) {
                    $categoryLastDate[$cat] = $date;
                    $categoryLastAmount[$cat] = (float) $op->getMontant();
                }

                if (!isset($categoryAmounts[$cat])) {
                    $categoryAmounts[$cat] = [];
                }
                $categoryAmounts[$cat][] = (float) $op->getMontant();
            }
        }

        $today = new \DateTime();

        foreach ($recurringCategories as $cat) {
            if (!isset($categoryLastDate[$cat])) continue;

            $daysSince = $today->diff($categoryLastDate[$cat])->days;

            if ($daysSince > 35) {
                $alerts[] = [
                    'type' => 'missing',
                    'category' => $cat,
                    'message' => "Paiement récurrent '$cat' manquant depuis $daysSince jours",
                    'last_date' => $categoryLastDate[$cat]->format('d/m/Y'),
                    'last_amount' => $categoryLastAmount[$cat],
                ];
            }

            if (isset($categoryAmounts[$cat]) && count($categoryAmounts[$cat]) >= 2) {
                $avg = array_sum($categoryAmounts[$cat]) / count($categoryAmounts[$cat]);
                $last = $categoryLastAmount[$cat];
                if ($avg > 0 && abs($last - $avg) / $avg > 0.10) {
                    $changePercent = round((($last - $avg) / $avg) * 100, 1);
                    $alerts[] = [
                        'type' => 'amount_changed',
                        'category' => $cat,
                        'message' => "Montant '$cat' a changé de " . ($changePercent > 0 ? '+' : '') . "$changePercent%",
                        'last_date' => $categoryLastDate[$cat]->format('d/m/Y'),
                        'last_amount' => $last,
                        'expected_amount' => round($avg, 2),
                        'change_percent' => $changePercent,
                    ];
                }
            }
        }

        return $alerts;
    }

    #[Route('/new', name: 'app_operation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, OperationRepository $operationRepository, CurrencyConverterService $currencyConverterService): Response
    {
        $operation = new Operation();
        $user = $this->getUser();
        
        $tresorerieId = $request->query->get('tresorerie');
        if ($tresorerieId) {
            $tresorerie = $entityManager->getRepository(\App\Entity\Tresorerie::class)->find($tresorerieId);
            if ($tresorerie) {
                $operation->setTresorerie($tresorerie);
            }
        }

        $form = $this->createForm(OperationType::class, $operation, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tresorerie = $operation->getTresorerie();
            $deviseSaisie = $form->get('deviseSaisie')->getData();
            $montantSaisi = (float) $operation->getMontant();
            
            // Toujours convertir vers TND (base du système)
            $deviseCible = 'TND';
            
            if ($deviseSaisie && $deviseSaisie !== $deviseCible) {
                $convertedAmount = $currencyConverterService->convertir($montantSaisi, $deviseSaisie, $deviseCible);
                $operation->setMontant((string) $convertedAmount);
                $this->addFlash('info', 'Le montant a été automatiquement converti de ' . $deviseSaisie . ' vers ' . $deviseCible . '.');
            }
            
            $amount = (float) $operation->getMontant();
            $currentSolde = (float) $tresorerie->getSolde();

            // Métier 1: Auto-catégorisation basée sur les mots-clés
            $description = mb_strtolower($operation->getDescription() ?? '');
            $suggestedCategory = $this->autoSuggestCategory($description, $operation->getType());
            if ($suggestedCategory && !$operation->getCategorie()) {
                $operation->setCategorie($suggestedCategory);
            }

            // Métier 2: Détection de doublons (même montant dans les 24h)
            $duplicate = $this->detectDuplicateOperation($operationRepository, $tresorerie, $amount, $operation->getType());
            if ($duplicate) {
                $this->addFlash('warning', 
                    '⚠️ Opération suspecte : Une transaction similaire (' . number_format($amount, 2) . ' TND) existe déjà aujourd\'hui. 
                    Référence: ' . $duplicate->getReference() . ' - ' . $duplicate->getDescription()
                );
            }

            // Métier 3: Grosse dépense = description obligatoire
            if ($operation->getType() === 'depense' && $amount >= 5000 && mb_strlen((string) $operation->getDescription()) < 10) {
                $this->addFlash('error', 'Veuillez ajouter une description (min 10 caractères) pour toute dépense >= 5000 TND.');
                return $this->render('operation/new.html.twig', [
                    'operation' => $operation,
                    'form' => $form->createView(),
                ]);
            }
            
            // Métier 4: Vérification du solde
            if ($operation->getType() === 'depense' && $currentSolde < $amount) {
                $this->addFlash('error', 'Opération refusée : Solde insuffisant sur le compte ' . $tresorerie->getNomCompte() . ' (' . number_format($currentSolde, 2) . ' TND disponible).');
                return $this->render('operation/new.html.twig', [
                    'operation' => $operation,
                    'form' => $form->createView(),
                ]);
            }

            if (!$operation->getReference()) {
                $operation->setReference('OP-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)));
            }

            // Update balance (All amounts are now guaranteed to be in TND)
            if ($operation->getType() === 'revenu') {
                $tresorerie->setSolde((string) ($currentSolde + $amount));
            } else {
                $tresorerie->setSolde((string) ($currentSolde - $amount));
            }
            $tresorerie->setDerniereMaj(new \DateTime());

            $entityManager->persist($operation);
            $entityManager->flush();

            if ((float)$tresorerie->getSolde() < 100) {
                $this->addFlash('warning', 'Attention : Le solde du compte ' . $tresorerie->getNomCompte() . ' est très bas (' . number_format($tresorerie->getSolde(), 2) . ' TND).');
            }

            $this->addFlash('success', 'Transaction enregistrée: ' . number_format($amount, 2) . ' TND');
            return $this->redirectToRoute('app_operation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('operation/new.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    /**
     * Auto-catégorisation basée sur les mots-clés de la description
     */
    private function autoSuggestCategory(string $description, string $type): ?string
    {
        $categories = [
            'Salaire' => ['salaire', 'paie', 'employé', 'ouvrier', 'staff', 'cachet'],
            'Fournisseur' => ['fournisseur', 'achat', 'marchandise', 'stock', 'materiau', 'équipement'],
            'Loyer' => ['loyer', 'location', 'loyer', 'bail'],
            'Électricité' => ['electric', 'électricité', 'steg', 'energie', 'facture'],
            'Eau' => ['eau', 'sonede', 'canalisation'],
            'Téléphone' => ['téléphone', 'télécom', 'orange', 'tunisie telecom', 'mobile'],
            'Transport' => ['transport', 'carburant', 'essence', 'gazole', 'voiture', 'taxi'],
            'Assurance' => ['assurance', 'cnss', 'cnam', 'mutuelle'],
            'Impôt' => ['impôt', 'taxe', 'fiscale', 'tvacnss'],
            'Publicité' => ['pub', 'publicité', 'marketing', 'facebook', 'google ads'],
            'Maintenance' => ['réparation', 'maintenance', 'dépannage', 'garantie'],
            'Virement Interne' => ['virement', 'interne', 'transfert'],
            'Achat Materiel' => ['ordinateur', 'pc', 'imprimante', 'bureau', 'chaise', 'matériel'],
            'Honoraire' => ['honoraire', 'avocat', 'comptable', 'conseil', 'consultant'],
            'Commission' => ['commission', 'frais bancaire', 'agios'],
            'Location Voiture' => ['location voiture', 'leasing', 'crédit'],
            'Restaurant' => ['restaurant', 'cantine', 'repas', 'alimentation'],
            'Cotisation' => ['cotisation', 'ordre', 'chambre', 'syndicat'],
        ];

        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($description, $keyword)) {
                    return $category;
                }
            }
        }

        return $type === 'revenu' ? 'Autre Revenu' : 'Autre Dépense';
    }

    /**
     * Détection de doublons - même montant dans les 24h sur le même compte
     */
    private function detectDuplicateOperation(OperationRepository $repository, $tresorerie, float $amount, string $type): ?Operation
    {
        $yesterday = new \DateTime('-24 hours');
        
        return $repository->createQueryBuilder('o')
            ->where('o.tresorerie = :tresorerie')
            ->andWhere('o.montant = :montant')
            ->andWhere('o.type = :type')
            ->andWhere('o.dateOperation >= :yesterday')
            ->setParameter('tresorerie', $tresorerie)
            ->setParameter('montant', $amount)
            ->setParameter('type', $type)
            ->setParameter('yesterday', $yesterday)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Vérifie si l'utilisateur courant a le droit d'accéder à l'opération
     */
    private function checkOperationAccess(Operation $operation): void
    {
        $user = $this->getUser();
        if (!$this->isGranted('ROLE_ADMIN') && $operation->getTresorerie()->getEntreprise()->getProprietaire() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette opération.');
        }
    }

    #[Route('/{id}', name: 'app_operation_show', methods: ['GET'])]
    public function show(Operation $operation): Response
    {
        $this->checkOperationAccess($operation);
        return $this->render('operation/show.html.twig', [
            'operation' => $operation,
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_operation_pdf', methods: ['GET'])]
    public function generatePdf(Operation $operation): Response
    {
        $this->checkOperationAccess($operation);
        return $this->render('operation/pdf.html.twig', [
            'operation' => $operation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_operation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Operation $operation, EntityManagerInterface $entityManager, \App\Service\CurrencyConverterService $currencyConverterService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PROPRIETAIRE');
        $this->checkOperationAccess($operation);
        $user = $this->getUser();
        
        $oldType = $operation->getType();
        $oldAmount = $operation->getMontant();
        $oldTresorerie = $operation->getTresorerie();

        $form = $this->createForm(OperationType::class, $operation, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Revert old balance
            $oldCurrentSolde = (float) $oldTresorerie->getSolde();
            $revertedSolde = ($oldType === 'revenu') 
                ? ($oldCurrentSolde - (float)$oldAmount) 
                : ($oldCurrentSolde + (float)$oldAmount);

            // Apply new balance check
            $newTresorerie = $operation->getTresorerie();
            $deviseSaisie = $form->get('deviseSaisie')->getData();
            $montantSaisi = (float) $operation->getMontant();
            
            // Toujours convertir vers TND (base du système)
            $deviseCible = 'TND';
            
            if ($deviseSaisie && $deviseSaisie !== $deviseCible) {
                $convertedAmount = $currencyConverterService->convertir($montantSaisi, $deviseSaisie, $deviseCible);
                $operation->setMontant((string) $convertedAmount);
                $this->addFlash('info', 'Le montant a été automatiquement converti de ' . $deviseSaisie . ' vers ' . $deviseCible . '.');
            }

            $newAmount = (float) $operation->getMontant();

            if ($operation->getType() === 'depense' && $newAmount >= 5000 && mb_strlen((string) $operation->getDescription()) < 10) {
                $this->addFlash('error', 'Veuillez ajouter une description (min 10 caractères) pour toute dépense >= 5000 TND.');
                return $this->render('operation/edit.html.twig', [
                    'operation' => $operation,
                    'form' => $form->createView(),
                ]);
            }
            
            // Si c'est le même compte, on vérifie sur le solde inversé
            // Si c'est un nouveau compte, on vérifie sur son solde actuel
            $checkSolde = ($newTresorerie->getId() === $oldTresorerie->getId()) ? $revertedSolde : (float)$newTresorerie->getSolde();

            if ($operation->getType() === 'depense' && $checkSolde < $newAmount) {
                $this->addFlash('error', 'Modification refusée : Solde insuffisant sur le compte ' . $newTresorerie->getNomCompte() . '.');
                return $this->render('operation/edit.html.twig', [
                    'operation' => $operation,
                    'form' => $form->createView(),
                ]);
            }

            // Génération automatique de référence si vide lors de l'édition
            if (!$operation->getReference()) {
                $operation->setReference('OP-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)));
            }

            // Update old account
            $oldTresorerie->setSolde((string) $revertedSolde);

            // Update new account
            $newCurrentSolde = (float) $newTresorerie->getSolde();
            if ($operation->getType() === 'revenu') {
                $newTresorerie->setSolde((string) ($newCurrentSolde + $newAmount));
            } else {
                $newTresorerie->setSolde((string) ($newCurrentSolde - $newAmount));
            }
            $newTresorerie->setDerniereMaj(new \DateTime());

            $entityManager->flush();

            // Métier 3: Alerte solde bas
            if ((float)$newTresorerie->getSolde() < 100) {
                $this->addFlash('warning', 'Attention : Le solde du compte ' . $newTresorerie->getNomCompte() . ' est très bas (' . $newTresorerie->getSolde() . ' TND).');
            }

            $this->addFlash('success', 'Transaction updated successfully.');
            return $this->redirectToRoute('app_operation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('operation/edit.html.twig', [
            'operation' => $operation,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{id}', name: 'app_operation_delete', methods: ['POST'])]
    public function delete(Request $request, Operation $operation, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PROPRIETAIRE');
        $this->checkOperationAccess($operation);
        if ($this->isCsrfTokenValid('delete'.$operation->getId(), $request->get('_token'))) {
            // Revert balance before deleting
            $tresorerie = $operation->getTresorerie();
            $currentSolde = (float) $tresorerie->getSolde();
            $amount = (float) $operation->getMontant();
            
            if ($operation->getType() === 'revenu') {
                $tresorerie->setSolde((string) ($currentSolde - $amount));
            } else {
                $tresorerie->setSolde((string) ($currentSolde + $amount));
            }
            $tresorerie->setDerniereMaj(new \DateTime());

            $entityManager->remove($operation);
            $entityManager->flush();
            $this->addFlash('success', 'Transaction deleted successfully.');
        }

        return $this->redirectToRoute('app_operation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/api/recent', name: 'api_operation_recent', methods: ['GET'])]
    public function apiRecent(OperationRepository $operationRepository): JsonResponse
    {
        $user = $this->getUser();
        $operations = $operationRepository->createQueryBuilder('o')
            ->select('o.id, o.reference, o.type, o.montant, o.categorie, o.dateOperation, t.nom_compte AS compte')
            ->join('o.tresorerie', 't')
            ->join('t.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->orderBy('o.dateOperation', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getArrayResult();

        return $this->json([
            'success' => true,
            'count' => count($operations),
            'data' => $operations,
        ]);
    }
}
