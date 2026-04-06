<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/proprietaire')]
#[IsGranted('ROLE_PROPRIETAIRE')]
class ProprietaireController extends AbstractController
{
    #[Route('/dashboard', name: 'proprietaire_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('proprietaire/dashboard.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/profile', name: 'proprietaire_profile')]
    public function profile(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('faceImageFile')->getData();
            if ($imageFile) {
                $safeFilename = $slugger->slug(pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME));
                $newFilename  = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move($this->getParameter('face_images_directory'), $newFilename);
                    $user->setFaceImage($newFilename);
                } catch (FileException) {
                    $this->addFlash('warning', "Erreur lors de l'upload de la photo.");
                }
            }
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès.');
            return $this->redirectToRoute('proprietaire_profile');
        }

        return $this->render('proprietaire/profile.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
#[Route('/clients', name: 'proprietaire_clients')]
public function clients(Request $request, EntityManagerInterface $em): Response
{
    $search = $request->query->get('q');

    $qb = $em->getRepository(User::class)->createQueryBuilder('u')
        ->where('u.roles LIKE :role')
        ->setParameter('role', '%ROLE_INVESTISSEUR%');

    if ($search) {
        $qb->andWhere('u.nom LIKE :q OR u.prenom LIKE :q')
           ->setParameter('q', '%' . $search . '%');
    }

    $users = $qb->getQuery()->getResult();

    if ($request->isXmlHttpRequest()) {
        $data = array_map(fn($u) => [
            'nom' => $u->getNom(),
            'prenom' => $u->getPrenom(),
            'fullName' => $u->getFullName(),
            'tel' => $u->getTel(),
            'budget' => $u->getBudget(),
            'yearsExperience' => $u->getYearsExperience(),
            'highestProfit' => $u->getHighestProfit(),
            'faceImage' => $u->getFaceImage(),
        ], $users);

        return $this->json($data);
    }

    return $this->render('proprietaire/clients.html.twig', [
        'users' => $users,
    ]);
}
}
