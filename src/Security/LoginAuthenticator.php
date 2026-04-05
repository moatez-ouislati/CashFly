<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use App\Repository\UtilisateurRepository;

class LoginAuthenticator extends AbstractAuthenticator
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

        return new Passport(
            new UserBadge($email, function ($identifier) {
                $user = $this->utilisateurRepository->findOneByEmail($identifier);
                
                if (!$user) {
                    throw new CustomUserMessageAuthenticationException('User not found.');
                }

                if ($user->getActive() !== 1) {
                    throw new CustomUserMessageAuthenticationException('Account is inactive.');
                }

                return new UserLocator($user, $password);
            }),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?JsonResponse
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
    {
        return null;
    }
}
