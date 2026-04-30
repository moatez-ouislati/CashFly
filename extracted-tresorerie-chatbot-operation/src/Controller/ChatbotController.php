<?php

namespace App\Controller;

use App\Service\ChatbotService;
use App\Repository\TresorerieRepository;
use App\Repository\OperationRepository;
use App\Entity\Operation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/chatbot')]
#[IsGranted('ROLE_PROPRIETAIRE')]
class ChatbotController extends AbstractController
{
    #[Route('/', name: 'app_chatbot')]
    public function index(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('chatbot/index.html.twig');
    }

    #[Route('/message', name: 'app_chatbot_message', methods: ['POST'])]
    public function sendMessage(
        Request $request,
        ChatbotService $chatbotService,
        TresorerieRepository $tresorerieRepository,
        OperationRepository $operationRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $message = $data['message'] ?? '';

        if (empty($message)) {
            return $this->json([
                'success' => false,
                'message' => 'Message vide',
            ]);
        }

        $user = $this->getUser();
        $context = $this->buildFinancialContext($user, $tresorerieRepository);

        $response = $chatbotService->sendMessage($message, $context);
        
        // Handle timeout errors gracefully
        if (!$response['success'] && strpos($response['message'] ?? '', 'timeout') !== false) {
            $response = [
                'success' => true,
                'message' => "🤖 L'assistant met trop de temps à répondre. Veuillez réessayer ou reformuler votre demande.\n\n💡 Astuce: \"Ajoute une dépense de 100\" fonctionne rapidement!",
                'suggestions' => [],
                'is_automated' => true,
            ];
        }
        
        // Execute action if present
        if (isset($response['action']) && !empty($response['action'])) {
            $actionResult = $this->executeAction(
                $response['action'], 
                $context, 
                $tresorerieRepository, 
                $operationRepository,
                $entityManager
            );
            
            if ($actionResult['success']) {
                $response['message'] .= "\n\n✅ " . $actionResult['message'];
                $response['action_executed'] = true;
            } else {
                $response['message'] .= "\n\n⚠️ " . $actionResult['message'];
            }
        }

        if (!isset($response['success'])) {
            $response['success'] = true;
        }

        return $this->json($response);
    }

    private function executeAction(
        array $action, 
        array $context,
        TresorerieRepository $tresorerieRepository,
        OperationRepository $operationRepository,
        EntityManagerInterface $entityManager
    ): array {
        $actionType = $action['action'] ?? '';
        
        try {
            switch ($actionType) {
                case 'create_operation':
                    return $this->createOperation($action, $tresorerieRepository, $entityManager);
                    
                case 'transfer':
                    return $this->transferMoney($action, $tresorerieRepository, $operationRepository, $entityManager);
                    
                default:
                    return ['success' => false, 'message' => 'Action non reconnue'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
    }

    private function createOperation(
        array $action,
        TresorerieRepository $tresorerieRepository,
        EntityManagerInterface $entityManager
    ): array {
        $type = $action['type'] ?? 'depense';
        $montant = (float)($action['montant'] ?? 0);
        $description = $action['description'] ?? 'Opération via chat';
        $categorie = $action['categorie'] ?? 'Autre';
        $tresorerieId = $action['tresorerie_id'] ?? null;
        
        if ($montant <= 0) {
            return ['success' => false, 'message' => 'Montant invalide'];
        }
        
        // Find tresorerie
        $tresorerie = null;
        if ($tresorerieId) {
            $tresorerie = $tresorerieRepository->find($tresorerieId);
        }
        
        // Use first tresorerie if not specified
        if (!$tresorerie) {
            $tresoreries = $tresorerieRepository->createQueryBuilder('t')
                ->join('t.entreprise', 'e')
                ->where('e.proprietaire = :user')
                ->setParameter('user', $this->getUser())
                ->setMaxResults(1)
                ->getQuery()
                ->getResult();
            
            if (!empty($tresoreries)) {
                $tresorerie = $tresoreries[0];
            }
        }
        
        if (!$tresorerie) {
            return ['success' => false, 'message' => 'Aucun compte de trésorerie trouvé'];
        }
        
        // Check balance for depense
        $currentSolde = (float)$tresorerie->getSolde();
        if ($type === 'depense' && $currentSolde < $montant) {
            return ['success' => false, 'message' => 'Solde insuffisant (' . number_format($currentSolde, 2, ',', ' ') . ' disponible)'];
        }
        
        // Create operation
        $operation = new Operation();
        $operation->setTresorerie($tresorerie);
        $operation->setType($type);
        $operation->setMontant(number_format($montant, 2, '.', ''));
        $operation->setDescription($description);
        $operation->setCategorie($categorie);
        $operation->setReference('CHAT-' . date('Ymd-His') . '-' . substr(uniqid(), -4));
        $operation->setDateOperation(new \DateTime());
        
        // Update tresorerie balance
        if ($type === 'revenu') {
            $tresorerie->setSolde(number_format($currentSolde + $montant, 2, '.', ''));
        } else {
            $tresorerie->setSolde(number_format($currentSolde - $montant, 2, '.', ''));
        }
        $tresorerie->setDerniereMaj(new \DateTime());
        
        $entityManager->persist($operation);
        $entityManager->flush();
        
        return [
            'success' => true, 
            'message' => ucfirst($type) . ' de ' . number_format($montant, 2, ',', ' ') . ' ' . $tresorerie->getDevise() . ' ajoutée au compte "' . $tresorerie->getNomCompte() . '"'
        ];
    }

    private function transferMoney(
        array $action,
        TresorerieRepository $tresorerieRepository,
        OperationRepository $operationRepository,
        EntityManagerInterface $entityManager
    ): array {
        $fromId = $action['from_tresorerie_id'] ?? null;
        $toId = $action['to_tresorerie_id'] ?? null;
        $montant = (float)($action['montant'] ?? 0);
        
        if ($montant <= 0) {
            return ['success' => false, 'message' => 'Montant invalide'];
        }
        
        $from = $fromId ? $tresorerieRepository->find($fromId) : null;
        $to = $toId ? $tresorerieRepository->find($toId) : null;
        
        if (!$from || !$to) {
            return ['success' => false, 'message' => 'Comptes non trouvés'];
        }
        
        $soldeFrom = (float)$from->getSolde();
        if ($soldeFrom < $montant) {
            return ['success' => false, 'message' => 'Solde insuffisant sur ' . $from->getNomCompte()];
        }
        
        // Debit source
        $from->setSolde(number_format($soldeFrom - $montant, 2, '.', ''));
        $from->setDerniereMaj(new \DateTime());
        
        // Credit destination
        $soldeTo = (float)$to->getSolde();
        $to->setSolde(number_format($soldeTo + $montant, 2, '.', ''));
        $to->setDerniereMaj(new \DateTime());
        
        // Create operations for both
        $opDebit = new Operation();
        $opDebit->setTresorerie($from);
        $opDebit->setType('depense');
        $opDebit->setMontant(number_format($montant, 2, '.', ''));
        $opDebit->setDescription('Virement vers ' . $to->getNomCompte());
        $opDebit->setCategorie('Virement Interne');
        $opDebit->setReference('VIRT-' . date('Ymd-His'));
        $opDebit->setDateOperation(new \DateTime());
        
        $opCredit = new Operation();
        $opCredit->setTresorerie($to);
        $opCredit->setType('revenu');
        $opCredit->setMontant(number_format($montant, 2, '.', ''));
        $opCredit->setDescription('Virement depuis ' . $from->getNomCompte());
        $opCredit->setCategorie('Virement Interne');
        $opCredit->setReference('VIRT-' . date('Ymd-His') . '-R');
        $opCredit->setDateOperation(new \DateTime());
        
        $entityManager->persist($opDebit);
        $entityManager->persist($opCredit);
        $entityManager->flush();
        
        return [
            'success' => true, 
            'message' => 'Virement de ' . number_format($montant, 2, ',', ' ') . ' TND effectué de "' . $from->getNomCompte() . '" vers "' . $to->getNomCompte() . '"'
        ];
    }

    private function buildFinancialContext($user, TresorerieRepository $tresorerieRepository): array
    {
        $context = [];
        
        $tresoreries = $tresorerieRepository->createQueryBuilder('t')
            ->join('t.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        $soldeTotal = 0;
        $context['tresoreries'] = [];
        
        foreach ($tresoreries as $tresorerie) {
            $solde = (float)$tresorerie->getSolde();
            $soldeTotal += $solde;
            $context['tresoreries'][] = [
                'id' => $tresorerie->getId(),
                'nom' => $tresorerie->getNomCompte(),
                'solde' => $solde,
                'devise' => $tresorerie->getDevise(),
                'type' => $tresorerie->getTypeCompte(),
            ];
        }
        
        $context['solde_total'] = $soldeTotal;
        
        return $context;
    }
}
