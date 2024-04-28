<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\Formation;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\FormationRepository;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
/*
    #[Route('formation/generate/quiz/{id}', name: 'app_formation_generate_quiz', methods: ['GET'])]
    public function generateQuizForType(int $id,FormationRepository $formationRepository,QuizRepository $quizRepository): Response
    {
        $formationRepository = $this->getDoctrine()->getRepository(Formation::class);
        $formation = $formationRepository->find($id);

        if (!$formation) {
            $this->addFlash('warning', "No formation found with ID '{$id}'.");
            return $this->redirectToRoute('app_formation_index');
        }

        $quizzes = $quizRepository->findBy(
            ['idFormation' => $id],       // Criteria
            ['idFormation' => 'ASC'],       // Order by (change 'fieldName' to your actual field and 'ASC' to 'ASC' or 'DESC')
        );
        
        // Optionally, render a view to display these quizzes
        return $this->render('quiz/quiz.html.twig', [
            'quizzes' => $quizzes, // Assuming only one quiz is generated
            'type' => $formation->getTypeF()
        ]);
    }


#[Route('formation/api/quiz', name: 'app_api_generate_quiz', methods: ['GET'])]
public function getQuizzes(QuizRepository $quizRepository): JsonResponse
{
    $quizzes = $quizRepository->findAll();  // Or any other method to get specific quizzes
    $quizData = [];

    foreach ($quizzes as $quiz) {
        $quizData[] = [
            'question' => $quiz->getQuestions(),
            'optionA' => $quiz->getAnswer1(),
            'optionB' => $quiz->getAnswer2(),
            'optionC' => $quiz->getAnswer3(),
            'correctOption' => $quiz->getCorrectAnswer()
        ];
    }

    return new JsonResponse($quizData);
}
*/

#[Route('formation/quiz/{idFormation}', name: 'handle_quiz')]
    public function handleQuiz(int $idFormation, Request $request, FormationRepository $formationRepository, QuizRepository $quizRepository): Response
    {
        if ($request->isMethod('GET')) {
            $formation = $formationRepository->find($idFormation);
            if (!$formation) {
                $this->addFlash('warning', "No formation found with ID '{$idFormation}'.");
                return $this->redirectToRoute('app_formation_index');
            }

            $quizzes = $quizRepository->findBy(
                ['idFormation' => $idFormation],       // Criteria
                ['idFormation' => 'ASC'],       // Order by (change 'fieldName' to your actual field and 'ASC' to 'ASC' or 'DESC')
            );
             if (empty($quizzes)) {
                $this->addFlash('warning', 'No quizzes found for the selected formation.');
                return $this->redirectToRoute('app_formation_index');
            }

            $this->session->start();
            shuffle($quizzes);
            $this->session->set('quizzes', $quizzes);
            $this->session->set('index', 0);
            $this->session->set('score', 0);

            $index = $this->session->get('index');
            $question = $quizzes[$index];
            return $this->render('quiz/quiz.html.twig', [
                'question' => $question,
                'index' => $index,
            ]);
        }

        if ($request->isMethod('POST')) {
            $index = $this->session->get('index');
            $quizzes = $this->session->get('quizzes');

            if ($index >= count($quizzes)) {
                $score = $this->session->get('score');
                $this->session->clear();
                return $this->render('quiz/result.html.twig', [
                    'score' => $score,
                ]);
            }

            $question = $quizzes[$index];
            $userAnswer = $request->request->get('answer');
            $correctAnswer = $question->getCorrectAnswer();

            if ($userAnswer === $correctAnswer) {
                $score = $this->session->get('score');
                $score++;
                $this->session->set('score', $score);
            }

            $this->session->set('index', $index + 1);
            return $this->redirectToRoute('handle_quiz', ['idFormation' => $idFormation]);
        }
    }
}
