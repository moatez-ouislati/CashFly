<?php

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/complete', name: 'app_profile_complete', methods: ['GET', 'POST'])]
    public function complete(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Seuls les investisseurs ont besoin de compléter ces champs
        if ($user->getDbRole() !== 'investisseur') {
            return $this->redirectToRoute('app_dashboard');
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été complété avec succès.');
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('profile/complete.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
