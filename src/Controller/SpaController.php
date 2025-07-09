<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpaController extends AbstractController
{
    #[Route(
        '/{vueRouting}',
        name: 'spa',
        requirements: ['vueRouting' => '^(?!api|_wdt|_profiler).*'],
        defaults: ['vueRouting' => null]
    )]
    public function index(): Response
    {
        $filePath = $this->getParameter('kernel.project_dir') . '/public/spa.html';

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('SPA file not found.');
        }

        $content = file_get_contents($filePath);

        return new Response($content, 200, [
            'Content-Type' => 'text/html',
        ]);
    }
}
