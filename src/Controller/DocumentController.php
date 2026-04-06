<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Entreprise;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/document')]
class DocumentController extends AbstractController
{
    #[Route('/', name: 'app_document_index', methods: ['GET'])]
    public function index(Request $request, DocumentRepository $documentRepository, EntrepriseRepository $entrepriseRepository): Response
    {
        if ($this->isGranted('ROLE_INVESTISSEUR')) {
            throw $this->createAccessDeniedException('Les investisseurs n\'ont pas accès à la gestion des documents.');
        }

        $user = $this->getUser();
        $search = $request->query->get('search');
        $type = $request->query->get('type');
        $entrepriseId = $request->query->get('entreprise');

        $qb = $documentRepository->createQueryBuilder('d')
            ->join('d.entreprise', 'e')
            ->where('e.proprietaire = :user')
            ->setParameter('user', $user)
            ->orderBy('d.dateUpload', 'DESC');

        if ($search) {
            $qb->andWhere('d.nom LIKE :search OR d.description LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($type) {
            $qb->andWhere('d.type = :type')
               ->setParameter('type', $type);
        }

        if ($entrepriseId) {
            $qb->andWhere('d.entreprise = :entId')
               ->setParameter('entId', $entrepriseId);
        }

        $documents = $qb->getQuery()->getResult();

        return $this->render('document/index.html.twig', [
            'documents' => $documents,
            'entreprises' => $entrepriseRepository->findBy(['proprietaire' => $user]),
            'search' => $search,
            'type' => $type,
            'entrepriseId' => $entrepriseId,
        ]);
    }

    #[Route('/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if (!$this->isGranted('ROLE_PROPRIETAIRE') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Seuls les propriétaires peuvent ajouter des documents.');
        }

        $document = new Document();
        
        // Pré-remplir l'entreprise si fournie
        $entrepriseId = $request->query->get('entreprise');
        if ($entrepriseId) {
            $entreprise = $entityManager->getRepository(Entreprise::class)->find($entrepriseId);
            if ($entreprise) {
                $document->setEntreprise($entreprise);
            }
        }

        $form = $this->createForm(DocumentType::class, $document, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document->setUtilisateur($this->getUser());
            
            $file = $form->get('fichier')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('documents_directory'),
                        $newFilename
                    );
                    $document->setCheminFichier($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                }
            }

            $entityManager->persist($document);
            $entityManager->flush();

            $this->addFlash('success', 'Document ajouté avec succès.');
            return $this->redirectToRoute('app_document_index');
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{id}', name: 'app_document_show', methods: ['GET'])]
    public function show(Document $document): Response
    {
        if ($document->getEntreprise()->getProprietaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Document $document, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if ($document->getEntreprise()->getProprietaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(DocumentType::class, $document, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('fichier')->getData();
            if ($file) {
                // Supprimer l'ancien fichier si besoin
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('documents_directory'),
                        $newFilename
                    );
                    $document->setCheminFichier($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload du fichier.');
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Document mis à jour avec succès.');
            return $this->redirectToRoute('app_document_index');
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document, EntityManagerInterface $entityManager): Response
    {
        if ($document->getEntreprise()->getProprietaire() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager->remove($document);
            $entityManager->flush();
            $this->addFlash('success', 'Document supprimé.');
        }

        return $this->redirectToRoute('app_document_index');
    }
}
