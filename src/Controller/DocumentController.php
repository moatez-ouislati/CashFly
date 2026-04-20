<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/document')]
final class DocumentController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {}

    #[Route(name: 'app_document_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
        ]);
    }

    #[Route('/admin/listDocument', name: 'app_document_admin_index', methods: ['GET'])]
    public function adminIndex(Request $request, DocumentRepository $documentRepository): Response
    {
        $search = $request->query->get('q');
        $type = $request->query->get('type');

        $documents = $documentRepository->searchAndFilterAdmin($search, $type);
        $types = $documentRepository->findAllTypes();

        return $this->render('document/admin_index.html.twig', [
            'documents' => $documents,
            'types' => $types,
            'currentSearch' => $search,
            'currentType' => $type,
        ]);
    }

    #[Route('/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichiers = $form->get('fichiers')->getData();
            
            $uploadedFiles = [];
            if ($fichiers) {
                foreach ($fichiers as $file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $this->slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                    try {
                        $uploadDir = $this->getParameter('kernel.project_dir').'/public/uploads/documents/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        $file->move($uploadDir, $newFilename);
                        $uploadedFiles[] = '/uploads/documents/'.$newFilename;
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                    }
                }
            }

            if (!empty($uploadedFiles)) {
                $document->setCheminFichier(implode(',', $uploadedFiles));
            }

            $document->setIdUtilisateur($this->getUser()?->getId());
            $document->setStatut('brouillon');

            $entityManager->persist($document);
            $entityManager->flush();

            $this->addFlash('success', count($uploadedFiles) > 0 
                ? 'Document créé avec '.count($uploadedFiles).' fichier(s) uploadé(s).'
                : 'Document créé avec succès.');

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_show', methods: ['GET'])]
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichiers = $form->get('fichiers')->getData();
            
            if ($fichiers) {
                $uploadedFiles = [];
                foreach ($fichiers as $file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $this->slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                    try {
                        $uploadDir = $this->getParameter('kernel.project_dir').'/public/uploads/documents/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        $file->move($uploadDir, $newFilename);
                        $uploadedFiles[] = '/uploads/documents/'.$newFilename;
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                    }
                }

                if (!empty($uploadedFiles)) {
                    $existingFiles = $document->getCheminFichier() ? explode(',', $document->getCheminFichier()) : [];
                    $allFiles = array_merge($existingFiles, $uploadedFiles);
                    $document->setCheminFichier(implode(',', $allFiles));
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Document modifié avec succès.');

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->getPayload()->getString('_token'))) {
            $cheminFichier = $document->getCheminFichier();
            if ($cheminFichier) {
                $files = explode(',', $cheminFichier);
                foreach ($files as $file) {
                    $filePath = $this->getParameter('kernel.project_dir').'/public'.$file;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }
            
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }
}
