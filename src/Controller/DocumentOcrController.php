<?php

namespace App\Controller;

use App\Entity\Document;
use App\Service\OcrService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/document')]
class DocumentOcrController extends AbstractController
{
    public function __construct(
        private OcrService $ocrService,
        private EntityManagerInterface $entityManager,
    ) {}

    #[Route('/{id}/ocr', name: 'document_ocr', methods: ['POST'])]
    public function extractText(Document $document, Request $request): Response
    {
        $cheminFichier = $document->getCheminFichier();

        if (!$cheminFichier) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => false,
                    'error' => 'Aucun fichier attaché au document.'
                ], 400);
            }
            $this->addFlash('error', 'Aucun fichier attaché au document.');
            return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
        }

        $files = explode(',', $cheminFichier);
        $projectDir = $this->getParameter('kernel.project_dir');
        $extractedTexts = [];
        $hasErrors = false;

        foreach ($files as $file) {
            $file = trim($file);
            if (empty($file)) continue;

            $filePath = $projectDir . '/public' . $file;

            if (!$this->ocrService->isImageFile($filePath) && !$this->ocrService->isPdfFile($filePath)) {
                continue;
            }

            if (!file_exists($filePath)) {
                $hasErrors = true;
                continue;
            }

            $result = $this->ocrService->extractTextFromFile($filePath);

            if ($result['success']) {
                $extractedTexts[] = $result['text'];
            } else {
                $hasErrors = true;
            }
        }

        if (empty($extractedTexts)) {
            $errorMsg = 'Impossible d\'extraire le texte. Vérifiez que le fichier est une image ou un PDF.';
            
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => false,
                    'error' => $errorMsg
                ], 400);
            }
            
            $this->addFlash('error', $errorMsg);
            return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
        }

        $fullText = implode("\n\n---\n\n", $extractedTexts);
        $document->setTexteOcr($fullText);
        $this->entityManager->flush();

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'success' => true,
                'text' => $fullText,
                'message' => 'Texte extrait avec succès !'
            ]);
        }

        $this->addFlash('success', 'Texte extrait avec succès ! (' . count($extractedTexts) . ' fichier(s) traité(s))');
        return $this->redirectToRoute('app_document_show', ['id' => $document->getId()]);
    }

    #[Route('/{id}/ocr-preview', name: 'document_ocr_preview', methods: ['GET'])]
    public function preview(Document $document): Response
    {
        $texteOcr = $document->getTexteOcr();

        if (!$texteOcr) {
            return new Response('<p class="text-muted">Aucun texte OCR extrait. Cliquez sur "Extraire le texte" pour lancer l\'OCR.</p>');
        }

        return new Response('<pre class="whitespace-pre-wrap text-sm">' . htmlspecialchars($texteOcr) . '</pre>');
    }
}
