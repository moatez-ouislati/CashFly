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
}