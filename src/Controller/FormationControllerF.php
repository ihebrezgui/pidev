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
    
    #[Route('/catalogue', name: 'app_formation_catalogue', methods: ['GET'])]
public function generatePdf(): Response
{// Create new PDF document// Create new PDF document
$pdf = new TCPDF('P', 'mm', 'A3', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Pace Learning');
$pdf->SetTitle('Pace Learning Catalogue');

// Get the absolute path to the public directory
$publicDirectory = $this->getParameter('kernel.project_dir') . '/public';

// Check if logo exists before setting header data
$logoPath = $publicDirectory . '/formations_imgs/LOGO.png';
if (file_exists($logoPath)) {
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->SetHeaderData($logoPath, 30, 'Pace Learning Catalogue', "by Pace Learning\nwww.pacelearning.com");
    $pdf->setFooterData(array(0,64,0), array(0,64,128));
} else {
    $pdf->SetHeaderData('', 0, 'Pace Learning Catalogue', "Logo missing - please verify path\nwww.pacelearning.com");
}

// Set margins
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 25);

// Fetch formations from the repository
$formations = $this->getDoctrine()->getRepository(Formation::class)->findAll();

// Background image settings
$backgroundPath = $publicDirectory . '/formations_imgs/back.png';

// Process each formation
foreach ($formations as $index => $formation) {
    // New page for each formation
    $pdf->AddPage();

    // Background image
    if (file_exists($backgroundPath)) {
        $pdf->Image($backgroundPath, 0, 0, 210, 297, 'PNG', '', '', false, 300, '', false, false, 0, false, false, true);
    } else {
        $pdf->SetFillColor(245, 245, 245);
        $pdf->Rect(0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), 'F');
    }

    // Add formation image
    $imgFilename = $formation['img'] ?? '';
    if ($imgFilename && file_exists($publicDirectory . '/formations_imgs/' . $imgFilename)) {
        $pdf->Image($publicDirectory . '/formations_imgs/' . $imgFilename, 15, 60, 40, 30, 'PNG');
    }

    // Draw a frame for each item
    $x = 60;  // Left margin for the text
    $y = 60;  // Top margin for the text
    $width = 130;  // Content width
    $height = 30;  // Content height

    // Frame settings
    $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(111, 66, 193)));
    $pdf->SetFillColor(255, 255, 255); // White background for text
    $pdf->Rect($x, $y, $width, $height, 'D'); // Only border, no fill

    // Set formation details
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Text($x + 5, $y + 5, $formation['name'] ?? 'N/A');
    $pdf->Text($x + 5, $y + 20, 'Duration: ' . ($formation['duration'] ?? 'N/A'));
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
