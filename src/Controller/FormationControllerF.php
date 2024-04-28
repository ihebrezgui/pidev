<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Service\QuizService;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OpenAIService;


#[Route('/formation')]
class FormationControllerF extends AbstractController
{   
    private $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }
    #[Route('/formation/{id}/quiz', name: 'formation_generate_quiz', methods: ['GET'])]
    public function generateQuiz(int $id): Response
    {
        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($id);
        if (!$formation) {
            throw $this->createNotFoundException('The formation does not exist');
        }

        $quiz = $this->openAIService->generateQuiz($formation->getTypeF());

        return $this->render('formationFront/quiz.html.twig', [
            'quiz' => $quiz,
            'formation' => $formation
        ]);
    }
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
    #[Route('/{id}', name: 'app_formation_show', methods: ['GET'])]
    public function show(FormationRepository $formationRepository, int $id): Response
    {
            $formation = $formationRepository->find($id);
            if ($formation) {
                $courses = $formation->getCourses();
                if (!$courses->isInitialized()) {
                    $courses->initialize();  // Explicitly initialize the collection
                }
            } else {
                throw $this->createNotFoundException('No formation found for id ' . $id);
            }
            

        if (!$formation) {

            throw $this->createNotFoundException('No formation found for id ' . $id);
        }

        return $this->render('formationFront/show.html.twig', [
            'formation' => $formation,
            'courses' => $courses,
        ]);
    }
}
