<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController

    {
        #[Route('/', name: 'app_utilisateur_index', methods: ['GET'])]
        public function index(UtilisateurRepository $utilisateurRepository, Request $request)
    {
        $searchQuery = $request->query->get('search');

        if ($searchQuery !== null) {
            $utilisateurs = $utilisateurRepository->findBySearchQuery($searchQuery);
        } else {
            // If no search query is provided, fetch all users
            $utilisateurs = $utilisateurRepository->findAll();
        }

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(UtilisateurType::class, $utilisateur);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    // Disable the password field
    $this->disablePasswordField($form);

    return $this->render('utilisateur/edit.html.twig', [
        'utilisateur' => $utilisateur,
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
    private function disablePasswordField(FormInterface $form): void
{
    $form->remove('password');
}
// Controller action to fetch users by age range and display in a chart
public function usersByAgeChart(UtilisateurRepository $utilisateurRepository)
{
    $utilisateurs = $utilisateurRepository->findAll(); 

    // Define age groups
    $ageGroups = [
        '11-20' => 0,
        '21-30' => 0,
        '31-40' => 0,
        '41-50' => 0,
        '51+' => 0,
    ];

    // Count users in each age group
    foreach ($utilisateurs as $user) {
        $age = $this->calculateAge($user->getDateNais());
        if ($age <= 20) {
            $ageGroups['11-20']++;
        } elseif ($age <= 30) {
            $ageGroups['21-30']++;
        } elseif ($age <= 40) {
            $ageGroups['31-40']++;
        } elseif ($age <= 50) {
            $ageGroups['41-50']++;
        } else {
            $ageGroups['51+']++;
        }
    }

    // Convert data to format required by the charting library
    $chartData = [
        'labels' => array_keys($ageGroups),
        'data' => array_values($ageGroups),
    ];

    // Render the chart view
    return $this->render('utilisateur/age_stat.html.twig', [
        'chartData' => $chartData,
    ]);
}
private function calculateAge(\DateTimeInterface $dateOfBirth)
{
    $now = new \DateTime();
    $interval = $now->diff($dateOfBirth);
    return $interval->y;
}
}
