<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use App\Service\NewsService;
use App\Service\TresorerieAutomationService;
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
        private NewsService $newsService,
        private TresorerieAutomationService $tresorerieAutomationService
    ) {}

    #[Route(name: 'app_entreprise_index', methods: ['GET'])]
    public function index(EntrepriseRepository $entrepriseRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        
        $session = $request->getSession();
        $newEntreprise = $session->get('new_entreprise');
        $session->remove('new_entreprise');

        $search = $request->query->get('q');

        $qb = $entrepriseRepository->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC');

        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_INVESTISSEUR')) {
            $this->denyAccessUnlessGranted('ROLE_PROPRIETAIRE');
            $qb->where('e.proprietaire = :user')
               ->setParameter('user', $user);
        }
            
        if ($search) {
            $qb->andWhere('e.nom LIKE :q OR e.secteur LIKE :q')
               ->setParameter('q', '%' . $search . '%');
        }

        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $pagination,
            'newEntreprise' => $newEntreprise,
            'search' => $search,
        ]);
    }

    #[Route('/admin/list', name: 'app_entreprise_admin_index', methods: ['GET'])]
    public function adminIndex(Request $request, EntrepriseRepository $entrepriseRepository, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
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
        $this->denyAccessUnlessGranted('ROLE_PROPRIETAIRE');
        $entreprise = new Entreprise();
        $entreprise->setProprietaire($this->getUser());
        
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = (string) $entreprise->getAdresse();
            if (!empty($address) && mb_strlen($address) >= 5) {
                $geocode = $this->geocodeAddress($address);
                if ($geocode !== null) {
                    $entreprise->setLatitude($geocode['lat']);
                    $entreprise->setLongitude($geocode['lon']);
                }
            }

            $entityManager->persist($entreprise);
            $entityManager->flush();

            // Auto-create a Tresorerie for this new Entreprise based on its capital
            $this->tresorerieAutomationService->createFromEntrepriseCapital($entreprise);
            $entityManager->flush();

            $entrepriseData = [
                'id' => $entreprise->getId(),
                'nom' => $entreprise->getNom(),
                'secteur' => $entreprise->getSecteur(),
                'formeJuridique' => $entreprise->getFormeJuridique(),
                'dateCreation' => $entreprise->getDateCreation()?->format('d/m/Y'),
                'capital' => number_format((float)$entreprise->getCapital(), 2, ',', ' ') . ' TND',
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

    private function checkEntrepriseAccess(Entreprise $entreprise, bool $isViewOnly = false): void
    {
        $user = $this->getUser();
        if ($this->isGranted('ROLE_ADMIN')) {
            return;
        }
        if ($isViewOnly && $this->isGranted('ROLE_INVESTISSEUR')) {
            return;
        }
        if ($entreprise->getProprietaire() !== $user) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette entreprise.');
        }
    }

    #[Route('/{id}', name: 'app_entreprise_show', methods: ['GET'])]
    public function show(Entreprise $entreprise): Response
    {
        $this->checkEntrepriseAccess($entreprise, true);
        $news = $this->newsService->getNewsByCompany($entreprise->getNom() ?? '', 5);
        
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise,
            'news' => $news,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_entreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        $this->checkEntrepriseAccess($entreprise);
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = (string) $entreprise->getAdresse();
            if (!empty($address) && mb_strlen($address) >= 5) {
                $geocode = $this->geocodeAddress($address);
                if ($geocode !== null) {
                    $entreprise->setLatitude($geocode['lat']);
                    $entreprise->setLongitude($geocode['lon']);
                }
            }

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
        $this->checkEntrepriseAccess($entreprise);
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($entreprise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_entreprise_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Vérifie qu'une adresse existe via Nominatim et retourne les coordonnées.
     *
     * @return array{lat: float, lon: float}|null
     */
    private function geocodeAddress(string $address): ?array
    {
        $address = trim($address);
        if (mb_strlen($address) < 8) {
            return null;
        }

        $url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' . rawurlencode($address);

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: CashFly/1.0\r\n",
                'timeout' => 4,
            ],
        ]);

        $json = @file_get_contents($url, false, $context);
        if ($json === false) {
            return null;
        }

        $payload = json_decode($json, true);
        if (!is_array($payload) || empty($payload[0]['lat']) || empty($payload[0]['lon'])) {
            return null;
        }

        return [
            'lat' => (float) $payload[0]['lat'],
            'lon' => (float) $payload[0]['lon'],
        ];
    }
}
