<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/commande', name: 'app_order_')]
class OrderController extends AbstractController
{
    #[Route('/ajout-commande', name: 'add')]
    public function addOrder(EntityManagerInterface $em, PanierRepository $panierRepo): Response
    {
        $user = $this->getUser();

        // we find the cart that belongs to the user
        $panier = $panierRepo->findOneBy(['user' => $user]);

        if (!$panier) {
            $this->addFlash('warning', 'Votre panier est vide!');
            return $this->redirectToRoute('app_cart_index');
        }

        // we create a new order
        $commande = new Commande();
        $commande->setCreeLe(new \DateTimeImmutable());
        $commande->setUser($user);
        $commande->setReference('ORD-' . Uuid::v4());
        $commande->setStatut('COMMANDÉ');

        $totalPrix = 0;

        foreach ($panier->getPanierProduits() as $panierProduit) {
            // Get the associated product from PanierProduit
            $produit = $panierProduit->getProduit();
            
            // we create an order detail
            $commandeDetail = new CommandeDetail();
            $commandeDetail->setCommande($commande);
            $commandeDetail->setProduit($panierProduit->getProduit());
            $commandeDetail->setQuantite($panierProduit->getQuantite());
            $commandeDetail->setPrix($panierProduit->getPrix());

            // Set the company related to the product in the order details
            $entreprise = $produit->getEntreprise();
            if ($entreprise) {
                $commandeDetail->setEntreprise($entreprise);
            }

            $totalPrix += $panierProduit->getPrix() * $panierProduit->getQuantite();

            $em->persist($commandeDetail);
        }
        $commande->setTotalPrix($totalPrix);
        $em->persist($commande);

        // delete products in the cart
        foreach($panier->getPanierProduits() as $panierProduit) {
            $em->remove($panierProduit);
        }
        // delete the cart
        $em->remove($panier);
        $em->flush();

        
        return $this->render('order/index.html.twig', [
            'commande' => $commande,
            'details' => $commande->getCommandeDetails(),
            'total' => $totalPrix,
        ]);
    }
}
