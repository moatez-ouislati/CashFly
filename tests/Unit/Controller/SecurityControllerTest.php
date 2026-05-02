<?php

namespace App\Tests\Unit\Controller;

use App\Controller\SecurityController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\Utilisateur;
use Twig\Environment;

class SecurityControllerTest extends TestCase
{
    public function testLoginReturnsResponse(): void
    {
        $controller = new SecurityController();
        
        $authUtils = $this->createMock(AuthenticationUtils::class);
        $authUtils->expects($this->once())->method('getLastAuthenticationError')->willReturn(null);
        $authUtils->expects($this->once())->method('getLastUsername')->willReturn('testuser');

        $twig = $this->createMock(Environment::class);
        $twig->expects($this->once())
            ->method('render')
            ->with('security/login.html.twig', ['last_username' => 'testuser', 'error' => null])
            ->willReturn('<html>login</html>');

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->once())->method('getToken')->willReturn(null);

        $container = new Container();
        $container->set('twig', $twig);
        $container->set('security.token_storage', $tokenStorage);
        $controller->setContainer($container);

        $response = $controller->login($authUtils);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('<html>login</html>', $response->getContent());
    }

    public function testIndexRedirectsBasedOnRole(): void
    {
        $controller = new SecurityController();
        
        $user = $this->createMock(Utilisateur::class);
        $user->expects($this->once())->method('getRoles')->willReturn(['ROLE_PROPRIETAIRE']);

        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->any())->method('getUser')->willReturn($user);

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->expects($this->any())->method('getToken')->willReturn($token);

        $router = $this->createMock(RouterInterface::class);
        $router->expects($this->once())
            ->method('generate')
            ->with('jpo_dashboard_proprietaire')
            ->willReturn('/dashboard/proprietaire');

        $container = new Container();
        $container->set('security.token_storage', $tokenStorage);
        $container->set('router', $router);
        $controller->setContainer($container);

        $response = $controller->index();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/dashboard/proprietaire', $response->getTargetUrl());
    }
}
