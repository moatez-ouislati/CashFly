<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageProxyController extends AbstractController
{
    #[Route('/cashfly/images/events/{filename}', name: 'cashfly_events_image', requirements: ['filename' => '.+'])]
    public function serveEventImage(string $filename): Response
    {
        $baseDir = $this->getParameter('cashfly_events_dir');
        
        // Prevent directory traversal
        $filename = basename($filename);
        
        $filePath = $baseDir . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($filePath)) {
            throw new NotFoundHttpException('Event image not found.');
        }

        return new BinaryFileResponse($filePath);
    }

    #[Route('/cashfly/logo', name: 'cashfly_logo')]
    public function serveLogo(): Response
    {
        $filePath = 'C:\xampp\htdocs\img\Logo4.png';
        if (!file_exists($filePath)) {
            throw new NotFoundHttpException('Logo not found.');
        }
        return new BinaryFileResponse($filePath);
    }
}
