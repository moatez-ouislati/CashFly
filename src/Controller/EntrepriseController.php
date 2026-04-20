<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use App\Service\NewsService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/entreprise')]
final class EntrepriseController extends AbstractController
{
    public function __construct(
        private NewsService $newsService
    ) {}

    #[Route(name: 'app_entreprise_index', methods: ['GET'])]
    public function index(EntrepriseRepository $entrepriseRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $session = $request->getSession();
        $newEntreprise = $session->get('new_entreprise');
        $session->remove('new_entreprise');

        $query = $entrepriseRepository->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $pagination,
            'newEntreprise' => $newEntreprise,
        ]);
    }

    #[Route('/admin/list', name: 'app_entreprise_admin_index', methods: ['GET'])]
    public function adminIndex(Request $request, EntrepriseRepository $entrepriseRepository, PaginatorInterface $paginator): Response
    {
        $search = $request->query->get('q');
        $secteur = $request->query->get('secteur');

        $qb = $entrepriseRepository->createQueryBuilder('e');
        if ($search) {
            $qb->andWhere('e.nom LIKE :search')->setParameter('search', '%' . $search . '%');
        }
        if ($secteur) {
            $qb->andWhere('e.secteur = :secteur')->setParameter('secteur', $secteur);
        }

        $pagination = $paginator->paginate(
            $qb->orderBy('e.id', 'DESC')->getQuery(),
            $request->query->getInt('page', 1),
            3
        );

        $secteurs = $entrepriseRepository->findAllSectors();
        $capitalStats = $entrepriseRepository->getCapitalStats();
        $sectorStats = $entrepriseRepository->getCapitalStatsBySector();
        $topEntreprises = $entrepriseRepository->getTopEntreprises();

        return $this->render('entreprise/admin_index.html.twig', [
            'entreprises' => $pagination,
            'secteurs' => $secteurs,
            'currentSearch' => $search,
            'currentSecteur' => $secteur,
            'capitalStats' => $capitalStats,
            'sectorStats' => $sectorStats,
            'topEntreprises' => $topEntreprises,
        ]);
    }


    #[Route('/new', name: 'app_entreprise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entreprise);
            $entityManager->flush();

            $entrepriseData = [
                'id' => $entreprise->getId(),
                'nom' => $entreprise->getNom(),
                'secteur' => $entreprise->getSecteur(),
                'formeJuridique' => $entreprise->getFormeJuridique(),
                'dateCreation' => $entreprise->getDateCreation()?->format('d/m/Y'),
                'capital' => number_format((float)$entreprise->getCapital(), 2, ',', ' ') . ' €',
                'adresse' => $entreprise->getAdresse(),
            ];

            $request->getSession()->set('new_entreprise', $entrepriseData);

            return $this->redirectToRoute('app_entreprise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entreprise/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entreprise_show', methods: ['GET'])]
    public function show(Entreprise $entreprise): Response
    {
        $news = $this->newsService->getNewsByCompany($entreprise->getNom() ?? '', 5);
        
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
            'news' => $news,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_entreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_entreprise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entreprise_delete', methods: ['POST'])]
    public function delete(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($entreprise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_entreprise_index', [], Response::HTTP_SEE_OTHER);
    }
}
