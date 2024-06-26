<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Home extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('front/home.html.twig');
    }
    #[Route('/admin', name: 'app_index_admin')]
    public function indexAdmin(UtilisateurRepository $userRepository): Response
    {
        

        return $this->render('back/utilisateur/index.html.twig',[]);
    }

   
}
