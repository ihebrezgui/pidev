<?php

namespace App\Controller;
use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
   
    public function affichageCommande(): Response
    {
        $em = $this->getDoctrine()->getManager()->getRepository(Commande::class);

        $commande = $em->findAll();
        return $this->render('commande/index.html.twig', ['commande' => $commande]);
    }

    #[Route('/ajoutCommande', name: 'ajouter_commande')]
    public function ajoutCommande(Request $request): Response
    {

        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
            $this->addFlash(
                'notice', 'commande a été bien ajoutée !'
            );
            return $this->redirectToRoute('app_commande');

        }

        return $this->render('commande/ajoutCommande.html.twig',
            ['f' => $form->createView()]
        );
    }
    #[Route('/modifierCommande/{idc}', name: 'modifier_commande')]
    public function modifierCommande(Request $request, $idc): Response
    {
        $prod = $this->getDoctrine()->getManager()->getRepository(Commande::class)->find($idc);


        $form = $this->createForm(CommandeType::class, $prod);


        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);//ajout
            $em->flush();// commit
            $this->addFlash(
                'notice', 'commande a été bien modifiée ! '
            );
            return $this->redirectToRoute('app_commande');

        }
        return $this->render('commande/modifierCommande.html.twig',
            ['f' => $form->createView()]
        );
    }

    #[Route('/supprimerCommande/{idc}', name: 'supprimerCommande')]
    public function supprimerCommande(int $idc): Response 
    {
        $entityManager = $this->getDoctrine()->getManager();
        $commande = $entityManager->getRepository(Commande::class)->find($idc);

        if (!$commande) {
            throw $this->createNotFoundException('Le commande avec l\'id ' . $idc . ' n\'existe pas.');
        }

        $entityManager->remove($commande);
        $entityManager->flush(); // Commit des modifications

        $this->addFlash('noticedelete', 'Le commande a été supprimé avec succès.');

        return $this->redirectToRoute('app_commande');
    }

    #[Route('/rechercherCommande', name: 'rechercher_commande')]
    public function rechercherCommande(Request $request, CommandeRepository $commandeRepository)
    {
        $searchTerm = $request->query->get('q');
        $commande = $commandeRepository->searchByNom($searchTerm);
    
        // Vérifier si le résultat de la recherche est vide
        if (empty($commande)) {
            $this->addFlash('noticesearch', 'Aucun utilisateur trouvé qui commence par "' . $searchTerm . '".');
            // Charger tous les produits pour l'affichage
            $commande = $commandeRepository->findAll();
        } else {
            $this->addFlash('noticesearch1', 'Résultats pour  "' . $searchTerm . '".');
        }
    
        return $this->render('commande/index.html.twig', [
            'commande' => $commande,
            'searchTerm' => $searchTerm,
        ]);
    }
    

    // Méthode pour trier par nom
    #[Route('/trier-par-nom', name: 'tri_par_nom')]
    public function trierParNom( CommandeRepository $CommandeRepository): Response
    {
        $commande = $CommandeRepository->findByName(); // Tri par nom
    
        return $this->render('commande/index.html.twig', [
            'commande' => $commande
        ]);
    }
    
}
