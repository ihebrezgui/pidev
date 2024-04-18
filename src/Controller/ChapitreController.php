<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChapitreController extends AbstractController
{
    #[Route('/chapitre', name: 'app_chapitre')]
    public function index(): Response
    {
        return $this->render('chapitre/index.html.twig', [
            'controller_name' => 'ChapitreController',
        ]);
    }
}
