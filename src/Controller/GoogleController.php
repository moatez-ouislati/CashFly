<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    /**
     * هذي الـ Route اللي تهز المستخدم لصفحة جوجل
     */
    #[Route('/connect/google', name: 'connect_google_start')]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // نطلبوا من جوجل يفتحلنا صفحة الدخول ويجيب "الإيميل" و "البروفايل"
        return $clientRegistry->getClient('google')->redirect(['email', 'profile']);
    }

    /**
     * هذي الـ Route اللي يرجعلها جوجل بعد ما المستخدم يختار الإيميل
     */
    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request)
    {
        // هوني سيمفوني والـ GoogleAuthenticator يتكفلوا بالباقي
        // الملف هذا يقعد فارغ
    }
}