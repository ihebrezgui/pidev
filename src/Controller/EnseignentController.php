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

#[Route('/enseignent')]
class EnseignentController extends AbstractController
{
   #[Route('/', name: 'app_enseignent_index', methods: ['GET'])]
public function index(Request $request, EnseignentRepository $enseignentRepository): Response
{
    $search = $request->query->get('search', ''); // Get the search term from the query string
    $enseignents = $enseignentRepository->findBySearchTerm($search);

    return $this->render('enseignent/index.html.twig', [
        'enseignents' => $enseignents,
    ]);
}

    #[Route('/new', name: 'app_enseignent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $enseignent = new Enseignent();
        $form = $this->createForm(EnseignentType::class, $enseignent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($enseignent);
            $entityManager->flush();

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

}
