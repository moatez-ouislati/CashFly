<?php

namespace App\Controller;

use App\Entity\Document;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/document')]
class DocumentWorkflowController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    #[Route('/{id}/workflow', name: 'document_workflow', methods: ['GET', 'POST'])]
    public function workflow(Request $request, Document $document): Response
    {
        $actions = $document->getAvailableActions();

        if ($request->isMethod('POST')) {
            $newStatut = $request->request->get('statut');
            $commentaire = $request->request->get('commentaire', '');

            if (!in_array($newStatut, $actions)) {
                $this->addFlash('error', 'Action non autorisée pour ce document.');
                return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
            }

            $oldStatut = $document->getStatut();
            $document->setStatut($newStatut);
            $document->setCommentaire($commentaire);

            if (in_array($newStatut, [Document::STATUT_APPROUVE, Document::STATUT_REJETE])) {
                $document->setIdValidateur($this->getUser()?->getId());
                $document->setNomValidateur($this->getUser()?->getUserIdentifier());
                $document->setDateValidation(new \DateTime());
            }

            $document->addToHistorique(
                $this->getActionLabel($newStatut),
                $commentaire ?: null,
                $this->getUser()?->getUserIdentifier()
            );

            $this->entityManager->flush();

            $this->addFlash('success', $this->getSuccessMessage($newStatut));
            return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
        }

        return $this->render('document/workflow.html.twig', [
            'document' => $document,
            'actions' => $actions,
        ]);
    }

    #[Route('/{id}/submit', name: 'document_submit', methods: ['POST'])]
    public function submit(Request $request, Document $document): Response
    {
        if (!$document->canTransitionTo(Document::STATUT_SOUMIS)) {
            $this->addFlash('error', 'Ce document ne peut pas être soumis.');
            return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
        }

        $document->setStatut(Document::STATUT_SOUMIS);
        $document->addToHistorique(
            'Soumis pour validation',
            null,
            $this->getUser()?->getUserIdentifier()
        );

        $this->entityManager->flush();
        $this->addFlash('success', 'Document soumis pour validation avec succès.');

        return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
    }

    #[Route('/{id}/approve', name: 'document_approve', methods: ['POST'])]
    public function approve(Request $request, Document $document): Response
    {
        if (!$document->canTransitionTo(Document::STATUT_APPROUVE)) {
            $this->addFlash('error', 'Ce document ne peut pas être approuvé.');
            return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
        }

        if (!$this->isCsrfTokenValid('approve_' . $document->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $commentaire = $request->request->get('commentaire', '');

        $document->setStatut(Document::STATUT_APPROUVE);
        $document->setCommentaire($commentaire);
        $document->setIdValidateur($this->getUser()?->getId());
        $document->setNomValidateur($this->getUser()?->getUserIdentifier());
        $document->setDateValidation(new \DateTime());
        $document->addToHistorique(
            'Approuvé',
            $commentaire ?: null,
            $this->getUser()?->getUserIdentifier()
        );

        $this->entityManager->flush();
        $this->addFlash('success', 'Document approuvé avec succès.');

        return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
    }

    #[Route('/{id}/reject', name: 'document_reject', methods: ['POST'])]
    public function reject(Request $request, Document $document): Response
    {
        if (!$document->canTransitionTo(Document::STATUT_REJETE)) {
            $this->addFlash('error', 'Ce document ne peut pas être rejeté.');
            return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
        }

        if (!$this->isCsrfTokenValid('reject_' . $document->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $commentaire = $request->request->get('commentaire');
        if (!$commentaire) {
            $this->addFlash('error', 'Un motif de rejet est obligatoire.');
            return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
        }

        $document->setStatut(Document::STATUT_REJETE);
        $document->setCommentaire($commentaire);
        $document->setIdValidateur($this->getUser()?->getId());
        $document->setNomValidateur($this->getUser()?->getUserIdentifier());
        $document->setDateValidation(new \DateTime());
        $document->addToHistorique(
            'Rejeté',
            $commentaire,
            $this->getUser()?->getUserIdentifier()
        );

        $this->entityManager->flush();
        $this->addFlash('success', 'Document rejeté.');

        return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
    }

    private function getActionLabel(string $statut): string
    {
        return match($statut) {
            Document::STATUT_BROUILLON => 'Remis en brouillon',
            Document::STATUT_SOUMIS => 'Soumis pour validation',
            Document::STATUT_EN_REVISION => 'Mis en révision',
            Document::STATUT_APPROUVE => 'Approuvé',
            Document::STATUT_REJETE => 'Rejeté',
            default => 'Statut modifié',
        };
    }

    private function getSuccessMessage(string $statut): string
    {
        return match($statut) {
            Document::STATUT_APPROUVE => 'Document approuvé avec succès.',
            Document::STATUT_REJETE => 'Document rejeté.',
            Document::STATUT_SOUMIS => 'Document soumis pour validation.',
            Document::STATUT_EN_REVISION => 'Document mis en révision.',
            default => 'Statut du document mis à jour.',
        };
    }
}
