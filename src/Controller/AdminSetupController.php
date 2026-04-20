<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminSetupController extends AbstractController
{
    #[Route('/setup-admin', name: 'setup_admin')]
    public function setup(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {
        // 🔒 SECRET ACCESS (change this)
        $accessUser = $request->query->get('u');
        $accessPass = $request->query->get('p');

        if ($accessUser !== 'root' || $accessPass !== 'cashfly_secure_2026') {
            return new Response('Access denied', 403);
        }

        // if already admin → block
        $existingAdmin = $em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_ADMIN%')
            ->getQuery()
            ->getOneOrNullResult();

        if ($existingAdmin) {
            return new Response('Admin already exists');
        }

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $user = new User();
            $user->setEmail($email);
            $user->setNom('Admin');
            $user->setPrenom('Root');
            $user->setTel('00000000');
            $user->setCin('00000000');
            $user->setRoles(['ROLE_ADMIN']);
            $user->setActive(true);

            $user->setPassword(
                $hasher->hashPassword($user, $password)
            );

            $em->persist($user);
            $em->flush();

            return new Response('✅ Admin created. Go login.');
        }

        return $this->render('admin/setup.html.twig');
    }
}