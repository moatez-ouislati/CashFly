<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Repository\OperationRepository;
use App\Repository\InvestissementRepository;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/analytics', name: 'app_admin_analytics')]
    public function analytics(
        UtilisateurRepository $utilisateurRepository,
        OperationRepository $operationRepository,
        InvestissementRepository $investissementRepository,
        EntrepriseRepository $entrepriseRepository
    ): Response {
        $adminUser = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        
        $totalUsers = count($utilisateurRepository->findAll());
        $activeUsers = count($utilisateurRepository->findActiveUsers());
        $totalOperations = count($operationRepository->findAll());
        $totalRevenus = $operationRepository->getTotalRevenus();
        $totalDepenses = $operationRepository->getTotalDepenses();
        $totalInvesti = $investissementRepository->getTotalMontantInvesti();
        $totalEntreprises = count($entrepriseRepository->findAll());
        
        $usersByRole = $utilisateurRepository->countByRole();
        
        return $this->render('admin/analytics.html.twig', [
            'stats' => [
                'totalUsers' => $totalUsers,
                'activeUsers' => $activeUsers,
                'totalOperations' => $totalOperations,
                'totalRevenus' => $totalRevenus,
                'totalDepenses' => $totalDepenses,
                'totalInvesti' => $totalInvesti,
                'totalEntreprises' => $totalEntreprises,
            ],
            'usersByRole' => $usersByRole,
            'user' => $adminUser,
        ]);
    }

    #[Route('/users', name: 'app_admin_users')]
    public function users(UtilisateurRepository $utilisateurRepository): Response
    {
        $users = $utilisateurRepository->findBy([], ['dateCreation' => 'DESC']);
        $adminUser = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        
        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'user' => $adminUser,
        ]);
    }

    #[Route('/users/new', name: 'app_admin_users_new', methods: ['GET', 'POST'])]
    public function newUser(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
        UtilisateurRepository $utilisateurRepository
    ): Response {
        $adminUser = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        
        if ($request->isMethod('POST')) {
            $user = new Utilisateur();
            $user->setNom($request->request->get('nom'));
            $user->setPrenom($request->request->get('prenom'));
            $user->setEmail($request->request->get('email'));
            $user->setCin($request->request->get('cin'));
            $user->setTel($request->request->get('tel'));
            $user->setRole($request->request->get('role', 'investisseur'));
            
            $password = $request->request->get('password');
            if ($password) {
                $user->setMotDePasse($userPasswordHasher->hashPassword($user, $password));
            }
            
            $user->setActive(1);
            $user->setDateCreation(new \DateTime());
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'Utilisateur cree avec succes');
            return $this->redirectToRoute('app_admin_users');
        }
        
        return $this->render('admin/user_form.html.twig', [
            'user' => null,
            'action' => 'create',
            'adminUser' => $adminUser,
        ]);
    }

    #[Route('/users/{id}/edit', name: 'app_admin_users_edit', methods: ['GET', 'POST'])]
    public function editUser(
        Utilisateur $user,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
        UtilisateurRepository $utilisateurRepository
    ): Response {
        $adminUser = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        
        if ($request->isMethod('POST')) {
            $user->setNom($request->request->get('nom'));
            $user->setPrenom($request->request->get('prenom'));
            $user->setEmail($request->request->get('email'));
            $user->setCin($request->request->get('cin'));
            $user->setTel($request->request->get('tel'));
            $user->setRole($request->request->get('role'));
            $user->setActive($request->request->get('active', 1));
            
            $password = $request->request->get('password');
            if ($password) {
                $user->setMotDePasse($userPasswordHasher->hashPassword($user, $password));
            }
            
            $entityManager->flush();
            
            $this->addFlash('success', 'Utilisateur modifie avec succes');
            return $this->redirectToRoute('app_admin_users');
        }
        
        return $this->render('admin/user_form.html.twig', [
            'user' => $user,
            'action' => 'edit',
            'adminUser' => $adminUser,
        ]);
    }

    #[Route('/users/{id}/toggle-active', name: 'app_admin_users_toggle', methods: ['POST'])]
    public function toggleUser(Utilisateur $user, EntityManagerInterface $entityManager): Response
    {
        $user->setActive($user->getActive() === 1 ? 0 : 1);
        $entityManager->flush();
        
        $this->addFlash('success', 'Statut de l\'utilisateur mis a jour');
        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/users/{id}/delete', name: 'app_admin_users_delete', methods: ['POST'])]
    public function deleteUser(Utilisateur $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();
        
        $this->addFlash('success', 'Utilisateur supprime');
        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/stats', name: 'app_admin_stats')]
    public function stats(
        UtilisateurRepository $utilisateurRepository,
        InvestissementRepository $investissementRepository
    ): Response {
        $adminUser = $utilisateurRepository->findOneBy(['email' => 'moffhamed.erguez@gmail.com']);
        
        $usersByRole = $utilisateurRepository->countByRole();
        
        $investisseurs = 0;
        $proprietaires = 0;
        $administrateurs = 0;
        
        foreach ($usersByRole as $role) {
            switch ($role['role']) {
                case 'investisseur':
                    $investisseurs = (int) $role['count'];
                    break;
                case 'proprietaire':
                    $proprietaires = (int) $role['count'];
                    break;
                case 'administrateur':
                    $administrateurs = (int) $role['count'];
                    break;
            }
        }
        
        $totalInvesti = $investissementRepository->getTotalMontantInvesti();
        
        return $this->render('admin/stats.html.twig', [
            'investisseurs' => $investisseurs,
            'proprietaires' => $proprietaires,
            'administrateurs' => $administrateurs,
            'totalInvesti' => $totalInvesti,
            'user' => $adminUser,
        ]);
    }
}
