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
}
