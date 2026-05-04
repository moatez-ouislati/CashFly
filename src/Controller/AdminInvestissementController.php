<?php

namespace App\Controller;

use App\Repository\InvestissementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Investissement;

#[Route('/admin/investissements')]
#[IsGranted('ROLE_ADMIN')]
class AdminInvestissementController extends AbstractController
{
    #[Route('/', name: 'admin_investissement_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $investissements = $em->getRepository(Investissement::class)->findBy([], ['dateInvestissement' => 'DESC']);

        return $this->render('admin/investissements/index.html.twig', [
            'investissements' => $investissements,
        ]);
    }
}
