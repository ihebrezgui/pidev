<?php

namespace App\Controller;

use App\Entity\Requestdonation;
use App\Form\RequestdonationType;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/requestBack')]
class requestback extends AbstractController
{
    #[Route('/', name: 'app_requestback_index', methods: ['GET'])]
    public function index(RequestRepository $requestRepository): Response
    {
        return $this->render('requestBack/index.html.twig', [
            'requestbacks' => $requestRepository->findAll(),
        ]);
    }


    #[Route('/{idrequest}', name: 'app_requestback_show', methods: ['GET'])]
    public function show(Requestdonation $requestdonation): Response
    {
        return $this->render('requestBack/show.html.twig', [
            'requestback' => $requestdonation,
        ]);
    }


}