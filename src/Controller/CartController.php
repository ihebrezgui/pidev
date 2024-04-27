<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Formation;
use App\Entity\Panier;
use App\Service\SmsGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(Request $request): Response
    {
        $cart = $request->getSession()->get('cart', []);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/cart_add/{id}', name: 'cart_add')]
    public function addToCart($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = $this->getDoctrine()->getRepository(Formation::class)->find($id);
    
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
    
        $quantity = $request->request->get('quantite', 1);
    
        $cart = $request->getSession()->get('cart', []);
        $totalCart = $request->getSession()->get('totalCart', 0);
    
        if (isset($cart[$id])) {
            // Si le produit existe déjà dans le panier
            $cart[$id]['quantity'] += $quantity; // Incrémenter la quantité
            $cart[$id]['total'] = $cart[$id]['quantity'] * $product->getPrix(); // Mettre à jour le total
        } else {
            // Si c'est un nouveau produit ajouté au panier
            $cart[$id] = [
                'id' => $product->getIdFormation(),
                'name' => $product->getTypeF(),
                'price' => $product->getPrix(),
                'quantity' => $quantity,
                'total' => $quantity * $product->getPrix(),
            ];
        }
    
        // Mettre à jour le total du panier
        $totalCart += $quantity * $product->getPrix();
    
        // Mettre à jour la session
        $request->getSession()->set('cart', $cart);
        $request->getSession()->set('totalCart', $totalCart);

        foreach ($cart as  $item) {
            $panier = new Panier();
            $panier->setProdId($item['id']);
            $panier->setNom($item['name']);
            $panier->setPrix($item['price']);
            $panier->setQuantite($item['quantity']);

            $entityManager->persist($panier);
        }

        $entityManager->flush();

        $this->addFlash(
            'notice', 'Produit a été bien ajouté! '
        );
        return $this->redirectToRoute('app_cart');
       
    }

    #[Route('/cart_remove/{id}', name: 'cart_remove')]
    public function remove(int $id, Request $request): Response
    {
        $product = $this->getDoctrine()->getRepository(Formation::class)->find($id);
    
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
    

        $cart = $request->getSession()->get('cart', []);
    
        if (isset($cart[$id])) {
            unset($cart[$id]); // Supprimer directement le produit du panier
    
            $totalCart = 0;
            foreach ($cart as $item) {
                $totalCart += $item['total'];
            }
    
            $request->getSession()->set('cart', $cart);
            $request->getSession()->set('totalCart', $totalCart);
        }
        $this->addFlash('noticedelete', 'Le produit a été supprimé avec succès.');
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/create_order', name: 'create_order')]
    public function createOrder(Request $request, EntityManagerInterface $entityManager,SmsGenerator $smsGenerator): Response
    {
        $cart = $request->getSession()->get('cart', []);
         // Vérifier si le panier est vide
    if (empty($cart)) {
        $this->addFlash('noticedelete', 'Votre panier est vide. Ajoutez des produits avant de passer une commande.');
        return $this->redirectToRoute('app_cart');}
        
        // Sérialiser le panier en JSON si c'est un tableau
        $panier = $cart ? json_encode($cart) : null;

       
        
        // Récupérer les informations de l'utilisateur depuis le formulaire (nom, prénom, email)
        $nom =('iheb');
        $prenom = ('rezgui');
        $email =('iheb@gmail.com');
        // $panier= $cart;
        $tel =21069300; 
        $number='+21621069300';
        $addr =('tunise');
        $text=  ('Votre commande a étè bien passer !!');
        $name= 'PaceLearning';

        
        
        $commande = new Commande();
        $commande->setNom($nom);
        $commande->setPrenom($prenom);
        $commande->setMail($email);
        $commande->setPanier($panier);
        $commande->setAddress($addr);
        $commande->setTel($tel);

        $entityManager->persist($commande);
        $entityManager->flush();


         // Définir twilio_to_number avec la valeur de $number
           //  putenv("twilio_to_number=$number");

            //$number_test = $_ENV['twilio_to_number']; // Numéro vérifié par Twilio. Un seul numéro autorisé pour la version de test.


                //Appel du service
        $smsGenerator->sendSms($number ,$name,$text);  

        $request->getSession()->set('cart', []);
        $request->getSession()->set('totalCart', 0);

        return $this->redirectToRoute('app_cart');
    }
}

