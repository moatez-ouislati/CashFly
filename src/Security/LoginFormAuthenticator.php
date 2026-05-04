<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    // TASLI7: Zidna el UserRepository hna bech na9raw el User kbal el login
    public function __construct(
        private RouterInterface $router,
        private UserRepository $userRepository
    ) {}

    public function authenticate(Request $request): Passport
{
    $email = $request->getPayload()->getString('email');
    $password = $request->getPayload()->getString('password');
    $userCaptcha = strtoupper($request->getPayload()->getString('_captcha'));
    $sessionCaptcha = $request->getSession()->get('custom_captcha');

    $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

    $user = $this->userRepository->findOneBy(['email' => $email]);

    if ($user) {
        $roles = $user->getRoles();
        // 1. Thabbet fel Captcha ken el user Propriétaire wala Investisseur
        if (in_array('ROLE_PROPRIETAIRE', $roles) || in_array('ROLE_INVESTISSEUR', $roles)) {
            if (!$userCaptcha || $userCaptcha !== $sessionCaptcha) {
                // Houni na3tiw Error Message khass bel Captcha barka
                throw new CustomUserMessageAuthenticationException('Le code de sécurité est incorrect.');
            }
        }
    }

    // 2. Ken el Captcha s7i7a (wala el user Admin), Symfony bech ythabbet fel Password
    return new Passport(
        new UserBadge($email),
        new PasswordCredentials($password),
        [
            new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
            new RememberMeBadge(),
        ]
    );
}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /** @var User $user */
        $user = $token->getUser();

        if (!$user->isActive()) {
            throw new CustomUserMessageAuthenticationException('Votre compte est désactivé. Contactez un administrateur.');
        }

        // Nadhaf el captcha mel session ba3d el login el s7i7
        $request->getSession()->remove('custom_captcha');

        return new RedirectResponse($this->getRedirectUrlForRole($user));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }
        return new RedirectResponse($this->router->generate(self::LOGIN_ROUTE));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate(self::LOGIN_ROUTE);
    }

    private function getRedirectUrlForRole(User $user): string
    {
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->router->generate('app_admin_face_verify');
        }

        if (in_array('ROLE_PROPRIETAIRE', $user->getRoles())) {
            return $this->router->generate('proprietaire_dashboard');
        }

        if ($user->needsInvestorProfileCompletion()) {
            return $this->router->generate('investisseur_profile', ['complete' => 1]);
        }

        return $this->router->generate('investisseur_dashboard');
    }
}
