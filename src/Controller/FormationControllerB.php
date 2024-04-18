<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/formationB')]
class FormationControllerB extends AbstractController
{
    #[Route('/', name: 'app_formation_index_back', methods: ['GET'])]
    public function index(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findAll();
        if (empty($formations)) {
            $this->addFlash('warning', 'No formations found.');
        }
        return $this->render('formationBack/baseB.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $formation = new Formation();
    $form = $this->createForm(FormationType::class, $formation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($formation);
        $entityManager->flush();

        $this->addFlash('success', 'New formation added successfully.');
        return $this->redirectToRoute('app_formation_index_back'); }

    return $this->renderForm('formationBack/new.html.twig', [
        'formation' => $formation,
        'form' => $form,
    ]);
}


    #[Route('/{idFormation}', name: 'app_formation_show', methods: ['GET'])]
    public function show(Formation $formation): Response
    {
        return $this->render('formationBack/show.html.twig', [
            'formation' => $formation,
        ]);
    }
    #[Route('/{idFormation}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, EntityManagerInterface $entityManager, int $idFormation): Response
{
    $formation = $entityManager->getRepository(Formation::class)->find($idFormation);

    if (!$formation) {
        throw $this->createNotFoundException('Formation with id ' . $idFormation . ' not found');
    }

    $form = $this->createForm(FormationType::class, $formation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        $this->addFlash('success', 'Formation updated successfully');
        return $this->redirectToRoute('app_formation_index_back', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('formationBack/edit.html.twig', [
        'formation' => $formation,
        'form' => $form,
    ]);
   }
    #[Route('/{idFormation}', name: 'app_formation_delete', methods: ['POST'])]
    public function delete(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getIdFormation(), $request->request->get('_token'))) {
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_formation_index_back', [], Response::HTTP_SEE_OTHER);
    }
}
