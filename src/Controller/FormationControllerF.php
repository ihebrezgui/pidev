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
    public function index(FormationRepository $formationRepository, Request $request): Response
    {
        $formations = $formationRepository->findAll();
        $search = $request->query->get('search', ''); // Default to empty string if not set
    
        
        $formations = $search ? $formationRepository->search($search) : $formationRepository->findAll();
        
        if (empty($formations)) {
            $this->addFlash('warning', 'No formations found.');
        }
        return $this->render('formationFront/base.html.twig', [
            'formations' => $formations,
        ]);
    }
    #[Route('/{idFormation}', name: 'app_formation_show', methods: ['GET'])]
public function show(FormationRepository $formationRepository, int $idFormation): Response
{
    // Fetch the formation using the repository
    $formation = $formationRepository->find($idFormation);

    // Check if the formation exists
    if (!$formation) {
        throw $this->createNotFoundException('No formation found for id ' . $idFormation);
    }

    // Render the template and pass the formation and its courses
    return $this->render('formationFront/show.html.twig', [
        'formation' => $formation,
        'courses' => $formation->getCourses()
    ]);
}

    
    
}
