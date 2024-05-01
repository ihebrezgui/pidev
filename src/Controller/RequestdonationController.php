<?php

namespace App\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Requestdonation;
use App\Form\RequestdonationType;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Knp\Component\Pager\PaginatorInterface;




#[Route('/requestdonation')]
class RequestdonationController extends AbstractController
{

    #[Route('/', name: 'app_requestdonation_index', methods: ['GET'])]
    public function index(RequestRepository $requestRepository, PaginatorInterface $paginator, Request $request, ChartBuilderInterface $chartBuilder): Response
    {
        $query = $requestRepository->createQueryBuilder('r')->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Current page number
            8 // Number of items per page
        );

        return $this->render('requestdonation/index.html.twig', [
            'requestdonations' => $requestRepository->findAll(),

            'pagination' => $pagination,
        ]);
    }



    #[Route('/new', name: 'app_requestdonation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $requestdonation = new Requestdonation();
        $form = $this->createForm(RequestdonationType::class, $requestdonation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($requestdonation);
            $entityManager->flush();

            $this->sendSMS(); // Call the sendSMS function

            // Generate the PDF and return it as a response
            return $this->generatePdfResponse($requestdonation);
        }

        return $this->renderForm('requestdonation/new.html.twig', [
            'requestdonation' => $requestdonation,
            'form' => $form,
        ]);
    }



    #[Route('/{idrequest}', name: 'app_requestdonation_show', methods: ['GET'])]
    public function show(Requestdonation $requestdonation): Response
    {
        return $this->render('requestdonation/show.html.twig', [
            'requestdonation' => $requestdonation,
        ]);
    }

    #[Route('/{idrequest}/edit', name: 'app_requestdonation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Requestdonation $requestdonation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RequestdonationType::class, $requestdonation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_requestdonation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('requestdonation/edit.html.twig', [
            'requestdonation' => $requestdonation,
            'form' => $form,
        ]);
    }

    #[Route('/{idrequest}', name: 'app_requestdonation_delete', methods: ['POST'])]
    public function delete(Request $request, Requestdonation $requestdonation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$requestdonation->getIdrequest(), $request->request->get('_token'))) {
            $entityManager->remove($requestdonation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_requestdonation_index', [], Response::HTTP_SEE_OTHER);
    }

    public function sendSMS(): Response
    {
        $message = "Votre demande a été effectuée avec succées";
        $recipient = "+21629762616"; // Replace with recipient phone number

        $accountSid = '';
        $authToken = '';

        try {
            $twilio = new Client($accountSid, $authToken);
            $sms = $twilio->messages->create(
                $recipient,
                [
                    'from' => '+13342491588',
                    'body' => $message,
                ]
            );

            if ($sms->status === 'sent') {
                $this->addFlash('success', 'SMS sent successfully!');
            } else {
                $this->addFlash('error', 'Error sending SMS: ' . $sms->status);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'An error occurred: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_requestdonation_index');
    }
  private function generateChart(RequestRepository $requestRepository, ChartBuilderInterface $chartBuilder): Chart
    {
        $formation_souhaitee = $requestRepository->getFormationStatistics();

        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $chart->setData([
            'labels' => array_column($formation_souhaitee, 'formation'),
            'datasets' => [
                [
                    'data' => array_column($formation_souhaitee, 'count'),
                    'backgroundColor' => ['blue', 'green', 'red', 'yellow', 'orange'], // Customize the colors as needed
                ],
            ],
        ]);

        return $chart;
    }

    public function generatePdf(Requestdonation $requestdonation): Response
    { // Create a Dompdf instance
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Render the PDF using the request information
        $html = $this->renderView('pdf/request_information.html.twig', [
            'requestdonation' => $requestdonation,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Return the PDF as a response
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="request_information.pdf"',
            ]
        );}


    private function generatePdfResponse(Requestdonation $requestdonation): Response
    {
        // Create a Dompdf instance
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Render the PDF using the request information
        $html = $this->renderView('requestdonation/request_information.html.twig', [
            'requestdonation' => $requestdonation,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Return the PDF as a response
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="request_information.pdf"',
            ]
        );
    }
    #[Route('/search', name: 'search_requestdonation', methods: ['POST'])]
    public function search(Requestdonation $request, RequestRepository $requestRepository): JsonResponse
    {
        // Retrieve the search term from the request data
        $requestData = json_decode($request->getContent(), true);
        $searchTerm = $requestData['search'] ?? null;

        // Perform the search in the repository
        if ($searchTerm !== null) {
            $results = $requestRepository->findBySearchTerm($searchTerm);
        } else {
            // Handle the case where no search term is provided
            $results = [];
        }

        // Return the results as JSON response
        return $this->json($results);
}}
