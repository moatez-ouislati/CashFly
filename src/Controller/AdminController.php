<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\EntrepriseRepository;
use App\Repository\OperationRepository;
use App\Repository\TresorerieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function dashboard(
        UserRepository $userRepository,
        EntrepriseRepository $entrepriseRepository,
        OperationRepository $operationRepository,
        TresorerieRepository $tresorerieRepository
    ): Response {
        $operations = $operationRepository->findAll();
        $totalRevenue = 0;
        $totalExpenses = 0;

        foreach ($operations as $op) {
            if ($op->getType() === 'revenu') {
                $totalRevenue += (float)$op->getMontant();
            } else {
                $totalExpenses += (float)$op->getMontant();
            }
        }

        return $this->render('admin/dashboard.html.twig', [
            'users_count' => count($userRepository->findAll()),
            'entreprises_count' => count($entrepriseRepository->findAll()),
            'operations_count' => count($operations),
            'tresoreries_count' => count($tresorerieRepository->findAll()),
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'recent_users' => $userRepository->findBy([], ['date_creation' => 'DESC'], 5),
        ]);
    }

    #[Route('/users', name: 'app_admin_users')]
    public function listUsers(UserRepository $userRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/users/new', name: 'app_admin_user_new')]
    public function newUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            }
            
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès.');
            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/users/{id}/edit', name: 'app_admin_user_edit')]
    public function editUser(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            }

            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès.');
            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/users/{id}/delete', name: 'app_admin_user_delete', methods: ['POST'])]
    public function deleteUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/entreprises', name: 'app_admin_entreprises')]
    public function listEntreprises(EntrepriseRepository $entrepriseRepository): Response
    {
        return $this->render('admin/entreprises.html.twig', [
            'entreprises' => $entrepriseRepository->findAll(),
        ]);
    }

    #[Route('/tresoreries', name: 'app_admin_tresoreries')]
    public function listTresoreries(TresorerieRepository $tresorerieRepository): Response
    {
        return $this->render('admin/tresoreries.html.twig', [
            'tresoreries' => $tresorerieRepository->findAll(),
        ]);
    }

    #[Route('/operations', name: 'app_admin_operations')]
    public function listOperations(OperationRepository $operationRepository): Response
    {
        return $this->render('admin/operations.html.twig', [
            'operations' => $operationRepository->findAll(),
        ]);
    }

    #[Route('/export/tresoreries', name: 'app_admin_export_tresoreries')]
    public function exportTresoreries(TresorerieRepository $tresorerieRepository): Response
    {
        $tresoreries = $tresorerieRepository->findAll();
        $csvData = "ID;Nom Compte;Numero;Type;Solde;Devise;Entreprise;Proprietaire;Email\n";

        foreach ($tresoreries as $t) {
            $csvData .= sprintf(
                "%d;%s;%s;%s;%s;%s;%s;%s;%s\n",
                $t->getId(),
                $t->getNomCompte(),
                $t->getNumeroCompte(),
                $t->getTypeCompte(),
                $t->getSolde(),
                $t->getDevise(),
                $t->getEntreprise()->getNom(),
                $t->getEntreprise()->getProprietaire()->getPrenom() . ' ' . $t->getEntreprise()->getProprietaire()->getNom(),
                $t->getEntreprise()->getProprietaire()->getEmail()
            );
        }

        $response = new Response($csvData);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export_tresoreries_' . date('Ymd_His') . '.csv"');

        return $response;
    }

    #[Route('/export/operations', name: 'app_admin_export_operations')]
    public function exportOperations(OperationRepository $operationRepository): Response
    {
        $operations = $operationRepository->findAll();
        $csvData = "ID;Reference;Date;Type;Montant;Categorie;Compte;Entreprise;Proprietaire;Email\n";

        foreach ($operations as $o) {
            $csvData .= sprintf(
                "%d;%s;%s;%s;%s;%s;%s;%s;%s;%s\n",
                $o->getId(),
                $o->getReference(),
                $o->getDateOperation()->format('d/m/Y H:i'),
                $o->getType(),
                $o->getMontant(),
                $o->getCategorie(),
                $o->getTresorerie()->getNomCompte(),
                $o->getTresorerie()->getEntreprise()->getNom(),
                $o->getTresorerie()->getEntreprise()->getProprietaire()->getPrenom() . ' ' . $o->getTresorerie()->getEntreprise()->getProprietaire()->getNom(),
                $o->getTresorerie()->getEntreprise()->getProprietaire()->getEmail()
            );
        }

        $response = new Response($csvData);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export_operations_' . date('Ymd_His') . '.csv"');

        return $response;
    }
}
