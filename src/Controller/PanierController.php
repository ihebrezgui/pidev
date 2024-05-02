<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Panier;
use App\Repository\PanierRepository;
use App\Form\PanierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;





class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
   
    public function affichagePanier(): Response
    {
        $em = $this->getDoctrine()->getManager()->getRepository(Panier::class);

        $panier = $em->findAll();
        return $this->render('panier/index.html.twig', ['panier' => $panier]);
    }

    #[Route('/ajoutPanier', name: 'ajouter_panier')]
    public function ajoutPanier(Request $request): Response
    {

        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $em->persist($panier);
            $em->flush();
            $this->addFlash(
                'notice', 'produit a été bien ajoutée au panier !'
            );
            return $this->redirectToRoute('app_panier');

        }

        return $this->render('panier/ajoutPanier.html.twig',
            ['f' => $form->createView()]
        );
    }
    #[Route('/modifierPanier/{idp}', name: 'modifier_panier')]
    public function modifierPanier(Request $request, $idp): Response
    {
        $prod = $this->getDoctrine()->getManager()->getRepository(Panier::class)->find($idp);


        $form = $this->createForm(PanierType::class, $prod);


        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);//ajout
            $em->flush();// commit
            $this->addFlash(
                'notice', 'Panier a été bien modifiée ! '
            );
            return $this->redirectToRoute('app_panier');

        }

        return $this->render('panier/modifierPanier.html.twig',
            ['f' => $form->createView()]
        );
    }

    #[Route('/supprimerPanier/{idp}', name: 'supprimerPanier')]
    public function supprimerPanier(int $idp): Response // Utilisation de l'ID de panier au lieu de l'objet Panier//
    {
        $entityManager = $this->getDoctrine()->getManager();
        $panier = $entityManager->getRepository(Panier::class)->find($idp);

        if (!$panier) {
            throw $this->createNotFoundException('Le panier avec l\'id ' . $idp . ' n\'existe pas.');
        }

        $entityManager->remove($panier);
        $entityManager->flush(); // Commit des modifications

        $this->addFlash('noticedelete', 'Le produit a été supprimé avec succès.');

        return $this->redirectToRoute('app_panier');
    }
    #[Route('/liste-produits', name: 'liste_produits')]
    public function listeProduits(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produits = $entityManager->getRepository(Formation::class)->findAll();

        return $this->render('panier/produits.html.twig', [
            'produits' => $produits,
        ]);
       
    }
    #[Route('/ajouter-produit', name: 'ajouter_produit')]
    public function ajouterAuPanier(Request $request): Response
    {

        
        // Récupérer les données du formulaire
        $quantite = $request->request->get('quantite');
        $typeF = $request->request->get('typeF');
        $prix = $request->request->get('prix');
        $idFormation = $request->request->get('idFormation');
        

        // Créer une nouvelle entrée dans le panier
        
        $panier = new Panier();
        $panier->setNom($typeF); // Mettre le typeF de la formation dans le nom du panier
        $panier->setQuantite($quantite); // Utiliser la quantité de la formation
        $panier->setPrix($prix);
        $panier->setProdId($idFormation); // Utiliser le prix de la formation
        // Laisser idp vide pour l'instant car il sera généré automatiquement par Doctrine

        // Enregistrer le panier dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($panier);
        $entityManager->flush();
        
        // Rediriger vers la page des produits avec un message de succès
        $this->addFlash(
            'notice', 'produit a été bien ajoutée au panier ! '
        );

        return $this->redirectToRoute('app_panier');
        
       
    }  
   
   
    
    #[Route('/rechercherPanier', name: 'rechercher_panier')]
    public function rechercherPanier(Request $request, PanierRepository $panierRepository)
    {
        $searchTerm = $request->query->get('q');
        $panier = $panierRepository->searchByNom($searchTerm);
    
        // Vérifier si le résultat de la recherche est vide
        if (empty($panier)) {
            $this->addFlash('noticesearch', 'Aucun produit trouvé qui commence par "' . $searchTerm . '".');
            // Charger tous les produits pour l'affichage
            $panier = $panierRepository->findAll();
        } else {
            $this->addFlash('noticesearch1', 'Résultats pour  "' . $searchTerm . '".');
        }
    
        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'searchTerm' => $searchTerm,
        ]);
    }
    
    #[Route('/trier-par-prix', name: 'tri_par_prix')]
    public function trierParPrix(PanierRepository $panierRepository): Response
    {
        $panier = $panierRepository->findByPrice(); // Tri par prix
    
        return $this->render('panier/index.html.twig', [
            'panier' => $panier
        ]);
    }
    
    // Méthode pour trier par nom
    #[Route('/trier-par-nom', name: 'tri_par_nom')]
    public function trierParNom( PanierRepository $panierRepository): Response
    {
        $panier = $panierRepository->findByName(); // Tri par nom
    
        return $this->render('panier/index.html.twig', [
            'panier' => $panier
        ]);
    }

    #[Route('/statistique_quantite_produits', name: 'statistique_quantite_produits')]
    public function statistiqueQuantiteProduits(): JsonResponse
{
    // Récupérer tous les articles du panier
    $panierItems = $this->getDoctrine()->getRepository(Panier::class)->findAll();

    // Initialiser un tableau pour stocker la quantité totale de chaque produit
    $quantiteParProduit = [];

    // Parcourir les articles du panier pour accumuler les quantités par produit
    foreach ($panierItems as $item) {
        $produitId = $item->getProdId();
        $quantite = $item->getQuantite();

        // Vérifier si le produit ID existe dans le tableau $quantiteParProduit
        if (isset($quantiteParProduit[$produitId])) {
            // Si oui, ajouter la quantité actuelle à la quantité existante
            $quantiteParProduit[$produitId] += $quantite;
        } else {
            // Sinon, initialiser la quantité pour ce produit
            $quantiteParProduit[$produitId] = $quantite;
        }
    }

    // Préparer les données finales à afficher
    $data = [];
    foreach ($quantiteParProduit as $idFormation => $quantiteTotale) {
        // Récupérer l'objet Produit à partir de son identifiant
        $produit = $this->getDoctrine()->getRepository(Formation::class)->find($idFormation);

        if ($produit) {
            $typeF = $produit-> getTypef();

            // Ajouter le nom du produit et sa quantité totale au tableau de données
            $data[] = [
                'nom' => $typeF, // Utilisation de 'nomp' pour correspondre à l'usage dans le template JavaScript
                'quantite' => $quantiteTotale,
            ];
        }
    }

    // Retourner les données au format JSON
    return new JsonResponse($data);
}

}