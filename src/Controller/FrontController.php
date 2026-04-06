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
        return $this->render('userDashboard.html.twig');
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