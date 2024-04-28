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

use Symfony\Component\Form\Extension\Core\Type\FileType;

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
        $priceData = []; // Initialize an array to store formation prices
        foreach ($formations as $formation) {
            // Check if $formation is an object and use the appropriate method or array access
            $type = is_array($formation) ? $formation['typeF'] : $formation->getTypeF();
            $type = strtolower(trim($type));
            
            if (!isset($typeCounts[$type])) {
                $typeCounts[$type] = 1;
            } else {
                $typeCounts[$type]++;
            }
        
            // Assuming there's a method getPrice() to get the price of the formation
            $price = is_array($formation) ? $formation['prix'] : $formation->getPrix();
            $priceData[$type][] = $price; // Store the price under the respective type
        }
        
        // Calculate average prices for each type
        $typeAvgPrices = [];
        foreach ($priceData as $type => $prices) {
            $typeAvgPrices[$type] = count($prices) > 0 ? array_sum($prices) / count($prices) : 0;
        }
        
        $chartData = [
            'labels' => array_keys($typeCounts),
            'data' => array_values($typeCounts),
        ];
        
        $priceChartData = [
            'labels' => array_keys($typeAvgPrices),
            'data' => array_values($typeAvgPrices),
        ];
        
        return $this->render('formationBack/baseB.html.twig', [
            'formations' => $formations,
            'typeCounts'=> $typeCounts,
            'chartData' => $chartData,
            'priceChartData' => $priceChartData, 
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
        $image = $form->get('img')->getData();
    
        if ($image) {
            // Generate a unique filename for the image using a custom sanitization method
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->sanitizeFilename($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();
    
            try {
                // Move the file to the directory where brochures are stored
                $image->move(
                    $this->getParameter('PaceLearning'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle exception if something happens during file upload
                $this->addFlash('error', 'Failed to upload image: ' . $e->getMessage());
                return $this->render('formationBack/new.html.twig', [
                    'formation' => $formation,
                    'form' => $form->createView(),
                ]);
            }
    
            // Updates the 'img' property to store the filename instead of the file's contents
            $formation->setImg($newFilename);
        }
    
        $entityManager->persist($formation);
        $entityManager->flush();
    
        $this->addFlash('success', 'New formation added successfully.');
        return $this->redirectToRoute('app_formation_index_back');
    }

    // Return the form view if the form is not submitted or not valid
    return $this->render('formationBack/new.html.twig', [
        'formation' => $formation,
        'form' => $form->createView(),
    ]);
}
private function sanitizeFilename(string $filename): string
{
    return strtolower(preg_replace('/[^A-Za-z0-9_\-]/', '-', $filename));
}

#[Route('/{idFormation}', name: 'app_formationB_show', methods: ['GET','POST'])]
public function show(FormationRepository $formationRepository, int $idFormation): Response
{
        $formation = $formationRepository->find($idFormation);
        if ($formation) {
            $courses = $formation->getCourses();
            if (!$courses->isInitialized()) {
                $courses->initialize();  // Explicitly initialize the collection
            }
        } else {
            throw $this->createNotFoundException('No formation found for id ' . $idFormation);
        }
        

    if (!$formation) {

        throw $this->createNotFoundException('No formation found for id ' . $id);
    }

    return $this->render('formationBack/show.html.twig', [
        'formation' => $formation,
        'courses' => $courses,
    ]);
}
    #[Route('/{idFormation}/edit', name: 'app_formationB_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $newImage = $form->get('img')->getData();  // Ensure your form has 'img' field mapped correctly
            
            if ($newImage) {
                // Generate a unique filename for the image using a custom sanitization method
                $originalFilename = pathinfo($newImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->sanitizeFilename($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $newImage->guessExtension();
        
                try {
                    // Move the file to the directory where brochures are stored
                    $newImage->move(
                        $this->getParameter('PaceLearning'),
                        $newFilename
                    );
    
                    // Update the 'img' property to store the new filename
                    $formation->setImg($newFilename);
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                    $this->addFlash('error', 'Failed to upload new image: ' . $e->getMessage());
                    return $this->render('formationBack/edit.html.twig', [
                        'formation' => $formation,
                        'form' => $form->createView(),
                    ]);
                }
            }
        
            $entityManager->flush();  // Save changes to the database
            $this->addFlash('success', 'Formation updated successfully.');
            return $this->redirectToRoute('app_formation_index_back');
        }
    
        // Render the edit form if not submitted or not valid
        return $this->render('formationBack/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
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

}
