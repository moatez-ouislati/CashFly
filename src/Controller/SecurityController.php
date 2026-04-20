<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Notifier\TexterInterface; 
use Symfony\Component\Notifier\Message\SmsMessage; 

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToDashboard();
        }

        // 1. San3et random string (Letters + Numbers)
        $code = substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ23456789"), 0, 5);
        
        // 2. Khabiha fel session
        $request->getSession()->set('custom_captcha', $code);

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error,
            'captcha_code' => $code,
        ]);
    }

    // --- EL ROUTE EL JDIDA ELLI T-THABBET FEL PASSWORD ---
    #[Route('/verify-credentials', name: 'app_verify_credentials', methods: ['POST'])]
    public function verifyCredentials(Request $request, UserRepository $userRepo, UserPasswordHasherInterface $hasher): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $userRepo->findOneBy(['email' => $email]);

        // 1. Thabbet fel User w Password
        if (!$user || !$hasher->isPasswordValid($user, $password)) {
            return $this->json([
                'success' => false, 
                'message' => 'Email ou mot de passe incorrect.'
            ]);
        }

        // 2. Ken s7a7, chouf el Role
        $isAdmin = in_array('ROLE_ADMIN', $user->getRoles());

        return $this->json([
            'success' => true,
            'needsCaptcha' => !$isAdmin // Admin dima false khater 3andou face verify
        ]);
    }

    #[Route('/admin/face-verify', name: 'app_admin_face_verify')]
    public function faceVerify(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('face_verify.html.twig');
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        SluggerInterface $slugger,
        TexterInterface $texter 
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToDashboard();
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->get('plainPassword')->getData()));
            
            $selectedRole = $form->get('roles')->getData();
            $user->setRoles([$selectedRole]);

            $imageFile = $form->get('faceImageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move($this->getParameter('face_images_directory'), $newFilename);
                    $user->setFaceImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', "Upload failed.");
                }
            }

            $em->persist($user);
            $em->flush();

            $messageContent = sprintf(
                "CashFly: Nouveau User!\nEmail: %s\nRole: %s\nCIN: %s\nTel: %s",
                $user->getEmail(),
                $selectedRole,
                $user->getCin(), 
                $user->getTel()  
            );

            $sms = new SmsMessage('+21652978824', $messageContent);
            try { $texter->send($sms); } catch (\Exception $e) {}

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): never { throw new \LogicException(); }

    private function redirectToDashboard(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin_face_verify');
        }
        if ($this->isGranted('ROLE_PROPRIETAIRE')) {
            return $this->redirectToRoute('proprietaire_dashboard');
        }
        return $this->redirectToRoute('investisseur_dashboard');
    }
}