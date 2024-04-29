<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Imagick;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
class QuizController extends AbstractController

{
    private EntityManagerInterface $entityManager;
    private $session;
    
    public function __construct(EntityManagerInterface $entityManager,SessionInterface $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }
    #[Route('/formation/quiz/{idFormation}', name: 'handle_quiz')]
    public function handleQuiz(int $idFormation, Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger, SessionInterface $session, FormationRepository $formationRepository, QuizRepository $quizRepository): Response
    {
        $formation = $formationRepository->find($idFormation);
        if (!$formation) {
            $this->addFlash('warning', "No formation found with ID '{$idFormation}'.");
            return $this->redirectToRoute('app_formation_index');
        }
    
        $quizzes = $quizRepository->findBy(['idFormation' => $idFormation]);
        if (empty($quizzes)) {
            $this->addFlash('warning', 'No quizzes found for the selected formation.');
            return $this->redirectToRoute('app_formation_index');
        }
    
        $quizData = array_map(function ($quiz) {
            return [
                'question' => $quiz->getQuestions(),
                'answer1' => $quiz->getAnswer1(),
                'answer2' => $quiz->getAnswer2(),
                'answer3' => $quiz->getAnswer3(),
                'correctAnswer' => $quiz->getCorrectAnswer(),
            ];
        }, $quizzes);
    
        $session->start();
        $index = $session->get('index', 0);
        $score = $session->get('score', 0);
        $displayedQuestions = $session->get('displayed_questions', []);
    
        if ($request->isMethod('POST')) {
            $userAnswer = $request->request->get('answer');
            $correctAnswer = $quizData[$index]['correctAnswer'] ?? null;
    
            if ($userAnswer === $correctAnswer) {
                $score++;
                $session->set('score', $score);
            }
    
            $index++;
            $session->set('index', $index);
            $displayedQuestions[] = $index;
            $session->set('displayed_questions', $displayedQuestions);
        }
    
        if ($index >= count($quizData)) {
            $logger->info('Final score: ' . $score);
            $certificatePath = null;
            if ($score > 3) {
                $studentName = "John Doe"; // Consider pulling this dynamically, e.g., from user profile
                //$certificatePath = $this->generateCertificate($studentName);
                //$certificatePath1 = $this->generateCertificate1($studentName);
            }
            $session->clear();
            return $this->render('quiz/result.html.twig', [
                'score' => $score
                /*'certificatePath' => $certificatePath,
                'certificatePath1' => $certificatePath1*/
            ]);
        }
    
        $nextQuestion = $quizData[$index] ?? null;
        return $this->render('quiz/quiz.html.twig', [
            'question' => $nextQuestion,
            'index' => $index,
            'idFormation' => $idFormation,
            'score' => $score
        ]);
    }
    /*
    private function generateCertificate1(string $studentName): string
    {
        $imagick = new Imagick('/formations_imgs/Certificate.png');
        $draw = new ImagickDraw();
        $draw->setFont('/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf');
        $draw->setFontSize(20);
        $draw->setFillColor('black');
        $draw->setGravity(Imagick::GRAVITY_CENTER);
        $imagick->annotateImage($draw, 0, 0, 0, $studentName);
        $certificateFilename = sprintf('/PaceLearning/%s_certificate.png', str_replace(' ', '_', $studentName));
        $imagick->writeImage($certificateFilename);
        $imagick->clear();
        return $certificateFilename;
    }
    
    private function generateCertificate(string $studentName): string
{
    // Load the certificate image
    $certificate = imagecreatefrompng('/formations_imgs/certificate.png');
    
    // Set text color
    $textColor = imagecolorallocate($certificate, 0, 0, 0); // Black
    
    // Set font path
    $fontPath = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
    
    // Set font size
    $fontSize = 20;
    
    // Calculate text position (centered)
    $textWidth = imagettfbbox($fontSize, 0, $fontPath, $studentName);
    $textWidth = $textWidth[2] - $textWidth[0];
    $x = (imagesx($certificate) - $textWidth) / 2;
    $y = imagesy($certificate) / 2;
    
    // Add text to the certificate
    imagettftext($certificate, $fontSize, 0, $x, $y, $textColor, $fontPath, $studentName);
    
    // Save the generated certificate to a file
    $certificateFilename = sprintf('/PaceLearning/%s_certificate.png', str_replace(' ', '_', $studentName));
    imagepng($certificate, $certificateFilename);
    
    // Free up memory
    imagedestroy($certificate);
    
    return $certificateFilename;
}
    */



}
