<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\CoursRepository;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use TCPDF;

#[Route('/formation')]
class FormationControllerF extends AbstractController
{       #[Route('/', name: 'app_formation_index', methods: ['GET'])]
    public function index(FormationRepository $formationRepository, Request $request): Response
    {
        // Fetch all formations
        $formations = $formationRepository->findAll();
        $search = $request->query->get('search', '');
        $quizzes = []; // Initialize the array to hold quiz statuses

        // Populate quiz information
        foreach ($formations as $formation) {
            if ($formation instanceof Formation) {
                $quizzes[$formation->getIdFormation()] = $formation->hasQuiz();
            }
        }

        // Check if there's a search term and re-fetch formations if necessary
        if ($search) {
            $formations = $formationRepository->search($search);
            // Clear and repopulate quizzes info for the newly fetched formations
            $quizzes = [];
            foreach ($formations as $formation) {
                if ($formation instanceof Formation) {
                    $quizzes[$formation->getIdFormation()] = $formation->hasQuiz();
                }
            }
        }
        
        if (empty($formations)) {
            $this->addFlash('warning', 'No formations found.');
        }

        // Render the response with formations and their quiz statuses
        return $this->render('formationFront/base.html.twig', [
            'formations' => $formations,
            'quizzes' => $quizzes,
        ]);
    }
    #[Route('/catalogue', name: 'app_formation_catalogue', methods: ['GET'])]
    public function generatePdf(): Response
    {
        // Create new PDF document with custom dimensions
        $pdf = new TCPDF('P', 'mm', 'A3', true, 'UTF-8', false);
    
        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Pace Learning');
        $pdf->SetTitle('Pace Learning Catalogue');
    
        // Get the absolute path to the public directory
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';
    
        // Set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
    
        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);
    
        // Fetch formations from the repository
        $formations = $this->getDoctrine()->getRepository(Formation::class)->findAll();
    
        // Variables for vertical listing
        $x = 30;
        $y = 30; // Start from top-left corner on the second page
        $y_increment = 60; // Vertical space for each formation
    
        // Front page with a special background and logo
        $pdf->AddPage();
        $frontBackgroundPath = $publicDirectory . '/formations_imgs/backfront.png';
        if (file_exists($frontBackgroundPath)) {
            $pdf->Image($frontBackgroundPath, 0, 0, 900, 1191, 'PNG', '', '', false, 300, '', false, false, 0, false, false, true);
        }
       
        // Start listing formations from the second page
        $pdf->AddPage();
    
        // Set background for subsequent pages
        $commonBackgroundPath = $publicDirectory . '/formations_imgs/backback.png';
        if (file_exists($commonBackgroundPath)) {
            $pdf->Image($commonBackgroundPath, 0, 0, 297, 420, 'PNG', '', '', false, 300, '', false, false, 0, false, false, true);
        }
    
        foreach ($formations as $formation) {
            $logoPath = $publicDirectory . '/formations_imgs/LOGO.png';
            if (file_exists($logoPath)) {
                $pdf->Image($logoPath, 10, 10, 20, 20, 'PNG');
            }
            $imgFilename = isset($formation['img']) ? $formation['img'] : '';
            if ($imgFilename && file_exists($publicDirectory . '/formations_imgs/' . $imgFilename)) {
                // Determine image format
                $imagePath = $publicDirectory . '/formations_imgs/' . $imgFilename;
                $imageType = exif_imagetype($imagePath);
                $imageFormat = $imageType === IMAGETYPE_JPEG ? 'JPEG' : 'PNG';
    
                // Draw formation image and details
                $pdf->Image($imagePath, $x+5, $y+5, 40, 30, $imageFormat);
                $pdf->SetFillColor(255, 255, 255); // White background for text
                $pdf->Rect($x + 50, $y, 150, 40, 'DF'); // Frame with background
    
                // Formation details
                $pdf->SetFont('helvetica', 'B', 14);
                $pdf->Text($x + 60, $y + 10, 'Type: ' . ($formation['typeF'] ?? 'N/A'));
                $pdf->SetFont('helvetica', '', 12);
                $pdf->Text($x + 60, $y + 25, 'Duration: ' . ($formation['duree'] ?? 'N/A'));
                $pdf->Text($x + 60, $y + 35, 'Prix: ' . ($formation['prix'] ?? 'N/A'));
    
                $y += $y_increment; // Move to the next slot
    
                if ($y + $y_increment > $pdf->getPageHeight() - 40) { // Check if space is available for next formation
                    $pdf->AddPage();
                    $y = 15; // Reset y coordinate for new page
    
                    // Set background for subsequent pages
                    if (file_exists($commonBackgroundPath)) {
                        $pdf->Image($commonBackgroundPath, 0, 0, 297, 420, 'PNG', '', '', false, 300, '', false, false, 0, false, false, true);
                    }
                }
            }
        }
    
        // Close and output PDF document
        $pdf->Output('formation_catalogue.pdf', 'I');
    
        return new Response('', 200, ['Content-Type' => 'application/pdf']);
    }
    
    
    #[Route('/{id}', name: 'app_formation_show', methods: ['GET'], requirements: ['id' => '\d+'])]
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
    // src/Controller/PdfController.php

#[Route('/pdf/{id}', name: 'serve_pdf')]
public function servePdf(int $id, CoursRepository $repository): Response
{
    $course = $repository->find($id);
    if (!$course) {
        throw $this->createNotFoundException('The course does not exist');
    }

    $pdfPath = $this->getParameter('pdf_directory') . '/' . $course->getCours(); // Ensure your path is secure
    if (!file_exists($pdfPath)) {
        throw $this->createNotFoundException('The file does not exist');
    }

    return new BinaryFileResponse($pdfPath);
}

    

}
