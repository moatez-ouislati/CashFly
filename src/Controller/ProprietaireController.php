<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
    public function profile(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, MailerInterface $mailer): Response
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
                    $this->addFlash('warning', "Erreur lors de l'upload.");
                }
            }

            $em->flush();

            // --- إرسال إيميل للأدمن ---
            // ... après le $em->flush()

// 1. On récupère le chemin complet de l'image
$imagePath = $this->getParameter('face_images_directory') . '/' . $user->getFaceImage();

// 2. On prépare l'email
$email = (new Email())
    ->from('system@cashfly.tn')
    ->to('salma.talbi@esprit.tn')
    ->subject('Modification Profil avec Photo : Propriétaire') // Changement du titre ici
    ->html("
        <h3>Alerte Modification de Profil (Propriétaire)</h3>
        <p>Le propriétaire <b>{$user->getFullName()}</b> a mis à jour ses données.</p>
        <ul>
            <li><b>CIN :</b> {$user->getCin()}</li>
            <li><b>Email :</b> {$user->getEmail()}</li>
        </ul>
        <p>La photo de profil est jointe à cet email.</p>
    ");

// 3. On attache le fichier si l'image existe
if ($user->getFaceImage() && file_exists($imagePath)) {
    $email->attachFromPath($imagePath);
}

// 4. On envoie
$mailer->send($email);

            $this->addFlash('success', 'Profil mis à jour et Admin notifié.');
            return $this->redirectToRoute('proprietaire_profile');
        }

        return $this->render('proprietaire/profile.html.twig', ['form' => $form, 'user' => $user]);
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
                'id' => $u->getId(),
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

        return $this->render('proprietaire/clients.html.twig', ['users' => $users]);
    }
}