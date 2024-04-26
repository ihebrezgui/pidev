<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Symfony\Component\Mime\Email;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $typeCounts = [];

    foreach ($formations as $formation) {
        $type = strtolower(trim($formation['typeF'])); 
        if (!isset($typeCounts[$type])) {
            $typeCounts[$type] = 1;
        } else {
            $typeCounts[$type]++;
        }
    }    
        return $this->render('formationBack/baseB.html.twig', [
            'formations' => $formations,
            'typeCounts' => $typeCounts,
        ]);
    }
    #[Route('/stat', name: 'app_formation_stat', methods: ['GET'])]
    public function formationsByTypeChart(FormationRepository $formationRepository)
{
    $formations = $formationRepository->findAll();

    $typeGroups = [];

    foreach ($formations as $formation) {
        $type = $formation['typeF']; // Access the typeF directly from the array
        if (!isset($typeGroups[$type])) {
            $typeGroups[$type] = 0;
        }
        $typeGroups[$type]++;
    }    
    $chartData = [
        'labels' => array_keys($typeGroups),
        'data' => array_values($typeGroups),
    ];

    return $this->render('formationBack/formation_stat.html.twig', [
        'chartData' => $chartData,
    ]);
}
#[Route('/type/{type}', name: 'app_formation_by_type', methods: ['GET'])]
public function showByType(string $type, FormationRepository $formationRepository): Response
{
    $formations = $formationRepository->findBy(['typeF' => $type]);

    return $this->render('formationBack/show_by_type.html.twig', [
        'formations' => $formations,
        'type' => $type,
    ]);
}

    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $formation = new Formation();
    $form = $this->createForm(FormationType::class, $formation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('imageFile')->getData();

        // Ensure there is actually a file
        if ($file) {
            $formation->setImageFile($file);  // Set the file in your entity if needed
        }
        dump($formation);
        $entityManager->persist($formation);
        $entityManager->flush();
        // $this->sendEmail($mailer, $formation);
         $this->addFlash('success', 'New formation added successfully.');
         return $this->redirectToRoute('app_formation_index_back'); }
    return $this->render('formationBack/new.html.twig', [
        'formation' => $formation,
        'form' => $form->createView(),
    ]);
}
/*
public function new(Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
{
    $formation = new Formation();
    $form = $this->createForm(FormationType::class, $formation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        if ($file) {
            $formation->setImageFile($file);
        }
        if ($file->getError()) {
            $this->addFlash('error', 'Error uploading file: ' . $file->getErrorMessage());
        }        
        if (null === $formation->getImageFile()) {
            // Handle the error, e.g., set a flash message or log an error
            $this->addFlash('error', 'Image file is required.');
                $file = $form->get('imageFile')->getData();
                dump($file);  // Check what Symfony sees in terms of the uploaded file
            }
        $entityManager->persist($formation);
        $entityManager->flush();
       // $this->sendEmail($mailer, $formation);
        $this->addFlash('success', 'New formation added successfully.');
        return $this->redirectToRoute('app_formation_index_back'); }

    return $this->renderForm('formationBack/new.html.twig', [
        'formation' => $formation,
        'form' => $form,
    ]);
}*/


    #[Route('/{idFormation}', name: 'app_formationB_show', methods: ['GET'])]
    public function show(Formation $formation): Response
    {
        return $this->render('formationBack/show.html.twig', [
            'formation' => $formation,
        ]);
    }
    #[Route('/{idFormation}/edit', name: 'app_formationB_edit', methods: ['GET', 'POST'])]
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
   #[Route('/{idFormation}', name: 'app_formationB_delete', methods: ['POST'])]
    public function delete(Request $request, formation $formation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getIdformation(), $request->request->get('_token'))) {
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_formation_index_back', [], Response::HTTP_SEE_OTHER);
    }
   
    /*private function sendEmail(MailerInterface $mailer, Formation $formation)
    {
        $email = (new Email())
            ->from('chahed.loumi@esprit.com')
            ->to('chahedloumi6@gmail.com') // This should be dynamically set if needed
            ->subject('New Formation Added')
            ->html($this->renderView('emails/new.html.twig', ['formation' => $formation]));

        $mailer->send($email);
    }*/
}
