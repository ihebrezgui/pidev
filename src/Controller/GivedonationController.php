<?php

namespace App\Controller;

use App\Entity\Givedonation;
use App\Form\GivedonationType;
use App\Repository\GiveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/givedonation')]
class GivedonationController extends AbstractController
{
    #[Route('/', name: 'app_givedonation_index', methods: ['GET'])]
    public function index(GiveRepository $giveRepository): Response
    {
        return $this->render('givedonation/index.html.twig', [
            'givedonations' => $giveRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_givedonation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $givedonation = new Givedonation();
        $form = $this->createForm(GivedonationType::class, $givedonation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($givedonation);
            $entityManager->flush();

            return $this->redirectToRoute('app_givedonation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('givedonation/new.html.twig', [
            'givedonation' => $givedonation,
            'form' => $form,
        ]);
    }

    #[Route('/{iddonateur}', name: 'app_givedonation_show', methods: ['GET'])]
    public function show(Givedonation $givedonation): Response
    {
        return $this->render('givedonation/show.html.twig', [
            'givedonation' => $givedonation,
        ]);
    }

    #[Route('/{iddonateur}/edit', name: 'app_givedonation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Givedonation $givedonation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GivedonationType::class, $givedonation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_givedonation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('givedonation/edit.html.twig', [
            'givedonation' => $givedonation,
            'form' => $form,
        ]);
    }

    #[Route('/{iddonateur}', name: 'app_givedonation_delete', methods: ['POST'])]
    public function delete(Request $request, Givedonation $givedonation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$givedonation->getIddonateur(), $request->request->get('_token'))) {
            $entityManager->remove($givedonation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_givedonation_index', [], Response::HTTP_SEE_OTHER);
    }

}
