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
use Twilio\Rest\Client;

#[Route('/requestdonation')]
class RequestdonationController extends AbstractController
{

    #[Route('/', name: 'app_requestdonation_index', methods: ['GET'])]
    public function index(RequestRepository $requestRepository): Response
    {
        return $this->render('requestdonation/index.html.twig', [
            'requestdonations' => $requestRepository->findAll(),
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

            return $this->redirectToRoute('app_requestdonation_index', [], Response::HTTP_SEE_OTHER);
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

        $accountSid = 'ACa5fd56712d5981cda406f5a426404bf9';
        $authToken = '6627d083b893f14cb1844fd172da2853';

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
}
