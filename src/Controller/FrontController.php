<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FrontController extends AbstractController
{
    #[Route('/user', name: 'app_front')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return $this->redirectToRoute('admin_dashboard');
        }
        if (in_array('ROLE_PROPRIETAIRE', $user->getRoles(), true)) {
            return $this->redirectToRoute('proprietaire_dashboard');
        }
        if (in_array('ROLE_INVESTISSEUR', $user->getRoles(), true)) {
            return $this->redirectToRoute('investisseur_dashboard');
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/admin', name: 'app_admin')]
    public function indexxx(): Response
    {
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/', name: 'app_signin')]
    public function signin(): Response
    {
        return $this->redirectToRoute('app_login');
    }
}