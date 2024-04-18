<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/formation')]
class FormationControllerF extends AbstractController
{
    #[Route('/', name: 'app_formation_index', methods: ['GET'])]
    public function index(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findAll();
        if (empty($formations)) {
            $this->addFlash('warning', 'No formations found.');
        }
        return $this->render('formationFront/base.html.twig', [
            'formations' => $formations,
        ]);
    }
    #[Route('/{idFormation}', name: 'app_formation_show', methods: ['GET'])]
    public function show(Formation $formation): Response
    {
        return $this->render('formationFront/show.html.twig', [
            'formation' => $formation,
        ]);
    }
}
