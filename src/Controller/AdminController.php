<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(Request $request, UserRepository $repo): Response
    {
        $search = $request->query->get('q');
        $role   = $request->query->get('role');

        $qb = $repo->createQueryBuilder('u');

        if ($search) {
            $qb->andWhere('u.nom LIKE :q OR u.prenom LIKE :q OR u.email LIKE :q')
               ->setParameter('q', '%' . $search . '%');
        }

        if ($role) {
            $qb->andWhere('u.roles LIKE :role')
               ->setParameter('role', '%' . $role . '%');
        }

        $users = $qb->orderBy('u.id', 'DESC')->getQuery()->getResult();

        $stats = [
            'total' => count($users),
            'investisseurs' => count(array_filter($users, fn($u) => in_array('ROLE_INVESTISSEUR', $u->getRoles()))),
            'proprietaires' => count(array_filter($users, fn($u) => in_array('ROLE_PROPRIETAIRE', $u->getRoles()))),
            'admins' => count(array_filter($users, fn($u) => in_array('ROLE_ADMIN', $u->getRoles()))),
        ];

        // AJAX
        if ($request->isXmlHttpRequest()) {
            $data = array_map(fn(User $u) => [
                'id' => $u->getId(),
                'name' => $u->getFullName(),
                'email' => $u->getEmail(),
                'tel' => $u->getTel(),
                'roles' => $u->getRoles(),
                'active' => $u->isActive(),
            ], $users);

            return $this->json($data);
        }

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'stats' => $stats
        ]);
    }

    #[Route('/profile', name: 'admin_profile')]
    public function profile(Request $request, EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour');
            return $this->redirectToRoute('admin_profile');
        }

        return $this->render('admin/profile.html.twig', ['form' => $form]);
    }
#[Route('/user/{id}/toggle', name: 'admin_toggle_user', methods: ['POST'])]
public function toggleUser(User $user, EntityManagerInterface $em): JsonResponse
{
    if ($user === $this->getUser()) {
        return $this->json(['error' => 'You cannot disable yourself'], 403);
    }

    $user->setActive(!$user->isActive());
    $em->flush();

    return $this->json(['active' => $user->isActive()]);
}

    #[Route('/user/{id}/delete', name: 'admin_delete_user', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        if ($user === $this->getUser()) {
            $this->addFlash('error', 'Impossible de supprimer votre propre compte.');
            return $this->redirectToRoute('admin_dashboard');
        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/user/{id}/edit', name: 'admin_edit_user')]
    public function editUser(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/export/pdf', name: 'admin_export_pdf')]
    public function exportPdf(UserRepository $repo): Response
    {
        $users = $repo->findAll();
        // ... (existing code for exportPdf if any, but since the file cut off here, I need to read the end properly first)


        $html = $this->renderView('admin/pdf.html.twig', [
            'users' => $users
        ]);

        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // ✅ correct response (no raw text)
        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="users.pdf"',
            ]
        );
    }

    #[Route('/export/operations', name: 'admin_export_operations')]
    public function exportOperations(\App\Repository\OperationRepository $repo): Response
    {
        $operations = $repo->findAll();
        
        $output = fopen('php://temp', 'w');
        // Add BOM for UTF-8 Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['Référence', 'Date', 'Type', 'Montant (TND)', 'Catégorie', 'Description', 'Trésorerie', 'Entreprise'], ';');
        
        foreach ($operations as $op) {
            fputcsv($output, [
                $op->getReference(),
                $op->getDateOperation() ? $op->getDateOperation()->format('Y-m-d H:i') : '',
                ucfirst($op->getType() ?? ''),
                number_format((float)$op->getMontant(), 2, ',', ' '),
                $op->getCategorie(),
                str_replace(["\n", "\r", "\t"], ' ', strip_tags($op->getDescription() ?? '')),
                $op->getTresorerie() ? $op->getTresorerie()->getNomCompte() : '',
                $op->getTresorerie() && $op->getTresorerie()->getEntreprise() ? $op->getTresorerie()->getEntreprise()->getNom() : ''
            ], ';');
        }
        
        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);
        
        return new Response($content, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="export_operations_cashfly_' . date('Y-m-d') . '.csv"'
        ]);
    }

    #[Route('/tresoreries', name: 'admin_tresoreries_index')]
    public function tresoreriesIndex(Request $request, \App\Repository\TresorerieRepository $tresorerieRepo, \App\Repository\OperationRepository $operationRepo): Response
    {
        $search = $request->query->get('search');
        $typeCompte = $request->query->get('typeCompte');
        
        $qb = $tresorerieRepo->createQueryBuilder('t')
            ->join('t.entreprise', 'e')
            ->join('e.proprietaire', 'p')
            ->orderBy('t.solde', 'DESC');
            
        if ($search) {
            $qb->andWhere('t.nomCompte LIKE :search OR t.numeroCompte LIKE :search OR e.nom LIKE :search OR p.nom LIKE :search OR p.prenom LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }
        
        if ($typeCompte) {
            $qb->andWhere('t.typeCompte = :typeCompte')
               ->setParameter('typeCompte', $typeCompte);
        }

        $tresoreries = $qb->getQuery()->getResult();

        $totalSolde = array_reduce($tresoreries, function($carry, $t) {
            return $carry + (float) $t->getSolde();
        }, 0);

        $tresorerieData = [];
        foreach ($tresoreries as $t) {
            $operations = $operationRepo->createQueryBuilder('o')
                ->where('o.tresorerie = :t')
                ->andWhere('o.dateOperation >= :startDate')
                ->setParameter('t', $t)
                ->setParameter('startDate', new \DateTime('-30 days'))
                ->getQuery()
                ->getResult();

            $revenus = 0;
            $depenses = 0;
            foreach ($operations as $op) {
                if ($op->getType() === 'revenu') {
                    $revenus += (float) $op->getMontant();
                } else {
                    $depenses += (float) $op->getMontant();
                }
            }

            $tresorerieData[] = [
                'tresorerie' => $t,
                'revenus_30j' => $revenus,
                'depenses_30j' => $depenses,
            ];
        }

        return $this->render('admin/tresoreries.html.twig', [
            'tresoreries' => $tresorerieData,
            'total_solde' => $totalSolde,
            'search' => $search
        ]);
    }

    #[Route('/export/tresoreries', name: 'admin_export_tresoreries')]
    public function exportTresoreries(Request $request, \App\Repository\TresorerieRepository $repo): Response
    {
        $search = $request->query->get('search');
        $typeCompte = $request->query->get('typeCompte');
        
        $qb = $repo->createQueryBuilder('t')
            ->join('t.entreprise', 'e')
            ->join('e.proprietaire', 'p')
            ->orderBy('t.solde', 'DESC');
            
        if ($search) {
            $qb->andWhere('t.nomCompte LIKE :search OR t.numeroCompte LIKE :search OR e.nom LIKE :search OR p.nom LIKE :search OR p.prenom LIKE :search')
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
        $html .= '<tr><td colspan="7" class="title">CASHFLY - RAPPORT DES TRÉSORERIES</td></tr>';
        $html .= '<tr><td colspan="4" class="meta">Date d\'export : ' . date('d/m/Y à H:i') . '</td>';
        $html .= '<td colspan="3" class="meta">Total des comptes exportés : ' . count($tresoreries) . '</td></tr>';
        if ($search) {
            $html .= '<tr><td colspan="7" class="meta">Filtre appliqué (Recherche) : ' . htmlspecialchars($search) . '</td></tr>';
        }
        if ($typeCompte) {
            $html .= '<tr><td colspan="7" class="meta">Filtre appliqué (Type) : ' . htmlspecialchars(ucfirst($typeCompte)) . '</td></tr>';
        }
        $html .= '<tr><td colspan="7"></td></tr>'; // Empty line
        
        // Data Headers
        $html .= '<tr>';
        $headers = ['Numéro Compte', 'Nom Compte', 'Type', 'Solde (TND)', 'Dernière Maj', 'Entreprise', 'Propriétaire'];
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
            $html .= '<td>' . ($t->getEntreprise() && $t->getEntreprise()->getProprietaire() ? $t->getEntreprise()->getProprietaire()->getFullName() : '') . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table></body></html>';
        
        return new Response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="export_tresoreries_cashfly_' . date('Y-m-d') . '.xls"'
        ]);
    }
}