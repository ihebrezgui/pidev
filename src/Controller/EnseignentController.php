<?php

namespace App\Controller;

use App\Entity\Enseignent;
use App\Form\EnseignentType;
use App\Repository\EnseignentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\PdfGenerator;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

#[Route('/enseignent')]
class EnseignentController extends AbstractController
{
   #[Route('/', name: 'app_enseignent_index', methods: ['GET'])]
   public function index(Request $request, EnseignentRepository $enseignentRepository, PaginatorInterface $paginator): Response
   {
       $search = $request->query->get('search', ''); // Get the search term from the query string
       $query = $enseignentRepository->findBySearchTermQuery($search); // Use the method that returns the query for the search term
       
       // Paginate the search results
       $enseignents = $paginator->paginate(
           $query, // Query to paginate
           $request->query->getInt('page', 1), // Current page number, default is 1
           3 // Items per page
       );
       
       return $this->render('enseignent/index.html.twig', [
           'enseignents' => $enseignents,
       ]);
   }
   

    #[Route('/new', name: 'app_enseignent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , PdfGenerator $pdfGenerator, MailerInterface $mailer): Response
    {
        $enseignent = new Enseignent();
        $form = $this->createForm(EnseignentType::class, $enseignent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($enseignent);
            $entityManager->flush();

              // Generate PDF content
        $html = $this->renderView('enseignent/pdf_template.html.twig', ['enseignent' => $enseignent]);
        $pdfContent = $pdfGenerator->generatePdf($html);

        
// Add a flash message
$this->addFlash('success', 'Enseignent added successfully!');


            return $this->redirectToRoute('app_enseignent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enseignent/new.html.twig', [
            'enseignent' => $enseignent,
            'form' => $form,
        ]);
    }

    #[Route('/{ide}', name: 'app_enseignent_show', methods: ['GET'])]
    public function show(Enseignent $enseignent): Response
    {
        
        return $this->render('enseignent/show.html.twig', [
            'enseignent' => $enseignent,
        ]);
    }

    #[Route('/{ide}/edit', name: 'app_enseignent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enseignent $enseignent, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EnseignentType::class, $enseignent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_enseignent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enseignent/edit.html.twig', [
            'enseignent' => $enseignent,
            'form' => $form,
        ]);
    }

    #[Route('/{ide}', name: 'app_enseignent_delete', methods: ['POST'])]
    public function delete(Request $request, Enseignent $enseignent, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$enseignent->getIde(), $request->request->get('_token'))) {
            $entityManager->remove($enseignent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_enseignent_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/age-statistics', name: 'app_enseignent_age_statistics')]
    public function showAgeStatistics(EnseignentRepository $enseignentRepository): Response
{
    $ageCounts = $enseignentRepository->countByAge();
    $averageAge = $enseignentRepository->averageAge();

    return $this->render('enseignent/age_statistics.html.twig', [
        'ageCounts' => $ageCounts,
        'averageAge' => $averageAge,
    ]);
}

#[Route('/enseignentfront', name: 'app_enseignent_front', methods: ['GET', 'POST'])]
public function new1(Request $request, EntityManagerInterface $entityManager): Response
{
    $enseignent = new Enseignent();
    $form = $this->createForm(EnseignentType::class, $enseignent);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($enseignent);
        $entityManager->flush();

        $this->sendEmail("candidature Notif","nouvelle candidature est Ajouter");
        return $this->redirectToRoute('app_enseignent_front', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('enseignent/enseignentfront.html.twig', [
        'enseignent' => $enseignent,
        'form' => $form,
    ]);
}

#[Route('/Enseignent/{ide}/pdf', name: 'app_enseignent_pdf', methods: ['GET'])]
public function printPdf(Enseignent $enseignent): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Generate the HTML content
        $html = $this->renderView('enseignent/print.html.twig', ['enseignent' => $enseignent]);
       
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');
        
        // Render the HTML as PDF
        $dompdf->render();
       
        // Generate a filename for the PDF
        $filename = sprintf('enseignent-%s.pdf', date('Y-m-d_H-i-s'));

        // Get the PDF content
        $pdfContent = $dompdf->output();

        // Send email with the PDF attachment
       
        // Output the generated PDF to the browser (force download)
        return new Response($pdfContent, Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    public function subscribe(Request $request, LoggerInterface $logger)
    {
        $subscription = $request->request->get('subscription');
    
        if (!$subscription) {
            throw new BadRequestHttpException('Missing subscription data.');
        }


}
function sendEmail($subject, $message)
    {
        $transport = new EsmtpTransport('smtp.gmail.com', 587);
        $transport->setUsername("mehergames29@gmail.com");
        $transport->setPassword("lbegsfgqzexxhrbc");
    
        $mailer = new Mailer($transport);
    
        $email = (new Email())
            ->from("pidev@gmail.com")
            ->to("mohamedaziz.chaabi@esprit.tn")
            ->subject($subject)
            ->text($message);
            
        $mailer->send($email);
    }
    #[Route('/affiche1', name: "affiche1")]
    public function index0(): Response
    {
        return $this->render('test1.html.twig');
    }
}
