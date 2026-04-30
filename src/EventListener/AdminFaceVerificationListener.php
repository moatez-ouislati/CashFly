<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEventListener(event: 'kernel.request')]
class AdminFaceVerificationListener
{
    public function __construct(
        private RouterInterface $router,
        private Security $security
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $route = $request->attributes->get('_route');

        // Ignore if the route is not starting with 'admin_' or 'app_admin_'
        if (!str_starts_with($request->getPathInfo(), '/admin')) {
            return;
        }

        // Allow access to the face verification routes themselves
        if (in_array($route, ['app_admin_face_verify', 'app_admin_face_verify_success'])) {
            return;
        }

        // Check if the user has ROLE_ADMIN
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $session = $request->getSession();
            if (!$session->get('admin_face_verified', false)) {
                $event->setResponse(new RedirectResponse($this->router->generate('app_admin_face_verify')));
            }
        }
    }
}
