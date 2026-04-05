<?php

namespace App\Security;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CredentialsInterface;

class LoginFormAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private UtilisateurRepository $utilisateurRepository
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('_username');
        $password = $request->request->get('_password');

        if (empty($email) || empty($password)) {
            throw new CustomUserMessageAuthenticationException('Email and password are required.');
        }

        $user = $this->utilisateurRepository->findByEmail($email);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Invalid email or password.');
        }

        if ($user->getActive() !== 1) {
            throw new CustomUserMessageAuthenticationException('Account is inactive.');
        }

        if (!$user->verifyPassword($password)) {
            throw new CustomUserMessageAuthenticationException('Invalid email or password.');
        }

        return new Passport(
            new UserBadge($email,             function ($identifier) {
                return $this->utilisateurRepository->findByEmail($identifier);
            }),
            new PlainTextCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse('/dashboard');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return null;
    }
}
