<?php

namespace App\Tests\Unit\Controller;

use App\Controller\ImageProxyController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageProxyControllerTest extends TestCase
{
    public function testServeEventImageThrowsNotFound(): void
    {
        $controller = new ImageProxyController();
        
        $parameterBag = new ParameterBag([
            'cashfly_events_dir' => '/fake/dir/path'
        ]);
        
        $container = new Container();
        $container->set('parameter_bag', $parameterBag);
        $controller->setContainer($container);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Event image not found.');

        $controller->serveEventImage('test.jpg');
    }

    public function testServeLogoReturnsResponseIfFileExists(): void
    {
        $controller = new ImageProxyController();
        
        $filePath = 'C:\xampp\htdocs\img\Logo4.png';
        if (file_exists($filePath)) {
            $response = $controller->serveLogo();
            $this->assertInstanceOf(\Symfony\Component\HttpFoundation\BinaryFileResponse::class, $response);
        } else {
            $this->expectException(NotFoundHttpException::class);
            $this->expectExceptionMessage('Logo not found.');
            $controller->serveLogo();
        }
    }
}
