<?php

namespace App\Controller;
use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommandeType;
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
    public function supprimerCommande(int $idc): Response // Utilisation de l'ID de panier au lieu de l'objet Panier//
    {
        $entityManager = $this->getDoctrine()->getManager();
        $panier = $entityManager->getRepository(Commande::class)->find($idc);

        if (!$panier) {
            throw $this->createNotFoundException('Le panier avec l\'id ' . $idc . ' n\'existe pas.');
        }

        $entityManager->remove($panier);
        $entityManager->flush(); // Commit des modifications

        $this->addFlash('noticedelete', 'Le commande a été supprimé avec succès.');

        return $this->redirectToRoute('app_commande');
    }
}
