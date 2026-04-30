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

#[Route('/investisseur')]
#[IsGranted('ROLE_INVESTISSEUR')]
class InvestisseurController extends AbstractController
{
    #[Route('/dashboard', name: 'investisseur_dashboard')]
    public function dashboard(): Response
    {
        if ($redirect = $this->redirectToProfileCompletion()) {
            return $redirect;
        }

        return $this->render('investisseur/dashboard.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/explore', name: 'investisseur_explore')]
    public function explore(Request $request, EntityManagerInterface $em): Response
    {
        if ($redirect = $this->redirectToProfileCompletion()) {
            return $redirect;
        }

        $search = $request->query->get('q');
        $qb = $em->getRepository(User::class)->createQueryBuilder('u')
            ->where('u.roles = :role')
            ->setParameter('role', 'proprietaire')
            ->setMaxResults(20);

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
                'cin' => $u->getCin(),
                'faceImage' => $u->getFaceImage(),
            ], $users);

            return $this->json($data);
        }

        return $this->render('investisseur/explore.html.twig', ['users' => $users]);
    }

    #[Route('/profile', name: 'investisseur_profile')]
    public function profile(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $mustCompleteProfile = $user->needsInvestorProfileCompletion();

        $form = $this->createForm(ProfileFormType::class, $user, [
            'show_extra_fields' => true,
            'require_investor_completion' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isBlank($user->getYearsExperience()) || $this->isBlank($user->getBudget())) {
                $this->addFlash('error', "Les champs années d'expérience et budget sont obligatoires.");

                return $this->render('investisseur/profile.html.twig', [
                    'form' => $form,
                    'user' => $user,
                    'mustCompleteProfile' => $mustCompleteProfile,
                ]);
            }

            $imageFile = $form->get('faceImageFile')->getData();
            if ($imageFile) {
                $safeFilename = $slugger->slug(pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME));
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move($this->getParameter('face_images_directory'), $newFilename);
                    $user->setFaceImage($newFilename);
                } catch (FileException) {
                    $this->addFlash('warning', "Erreur lors de l'upload.");
                }
            }

            $em->flush();

            $imagePath = $this->getParameter('face_images_directory') . '/' . $user->getFaceImage();

            $email = (new Email())
                ->from('system@cashfly.tn')
                ->to('salma.talbi@esprit.tn')
                ->subject('Modification Profil avec Photo : Investisseur')
                ->html("
                    <h3>Alerte Modification de Profil</h3>
                    <p>L'investisseur <b>{$user->getFullName()}</b> a mis à jour ses données.</p>
                    <ul>
                        <li><b>CIN :</b> {$user->getCin()}</li>
                        <li><b>Email :</b> {$user->getEmail()}</li>
                    </ul>
                    <p>Tu trouveras sa photo en pièce jointe.</p>
                ");

            if ($user->getFaceImage() && file_exists($imagePath)) {
                $email->attachFromPath($imagePath);
            }

            $mailer->send($email);

            $this->addFlash('success', 'Profil mis à jour et Admin notifié.');

            if ($mustCompleteProfile) {
                return $this->redirectToRoute('investisseur_dashboard');
            }

            return $this->redirectToRoute('investisseur_profile');
        }

        return $this->render('investisseur/profile.html.twig', [
            'form' => $form,
            'user' => $user,
            'mustCompleteProfile' => $mustCompleteProfile,
        ]);
    }

    private function redirectToProfileCompletion(): ?Response
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if ($user instanceof User && $user->needsInvestorProfileCompletion()) {
            $this->addFlash('warning', "Veuillez compléter votre expérience et votre budget avant de continuer.");

            return $this->redirectToRoute('investisseur_profile', ['complete' => 1]);
        }

        return null;
    }

    private function isBlank(?string $value): bool
    {
        return $value === null || trim($value) === '';
    }
}
