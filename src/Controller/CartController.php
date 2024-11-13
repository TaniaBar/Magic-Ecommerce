<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/panier', name: 'app_cart_')]
class CartController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(ProduitRepository $produitRepo, PanierRepository $panierRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $produitsPanier = $panierRepo->findBy([
            'user' => $user
        ]);
        $data = [];
        $total = 0;

        foreach($produitsPanier as $produitPanier) {
            $produit = $produitPanier->getProduit();
            // dd($produit);

            if ($produit) {
                $prix = $produitPanier->getPrix();
                $data[] = [
                    'produit' => $produit,
                    'quantite' => $produitPanier->getQuantite(),
                    'prix' => round($prix, 2),
                    
                ];
                $total += $prix * $produitPanier->getQuantite();
            }
        }

        return $this->render('cart/index.html.twig', [
            'data' => $data,
            'total' => $total,
        ]);
    }

    // buttons add product
    #[Route('/ajout/{slug}', name: 'add')]
    public function add(string $slug, EntityManagerInterface $em, ProduitRepository $produitRepo): Response
    {
        $user = $this->getUser();
        $produit = $produitRepo->findOneBy([
            'slug' => $slug
        ]);
        // dd($produit);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé!');
        }

        // We see if the user’s cart already contains a product
        $produitPanier = $em->getRepository(Panier::class)->findOneBy([
            'user' => $user,
        ]);

        if ($produitPanier) {
            $produitPanier->setQuantite($produitPanier->getQuantite() + 1);
        } else {
            $produitPanier = new Panier();
            $produitPanier->setUser($user);
            $produitPanier->addProduit($produit);
            $produitPanier->setQuantite(1);
            $produitPanier->setCreeLe(new \DateTimeImmutable());
            $prix = $produit->getPrix();
            if ($produit->getRemise()) {
                $produitAvecRemise = $produit->getRemise() / 100;
                $prix -= $prix * $produitAvecRemise; 
            }

            $produitPanier->setPrix(round($prix, 2));
            $em->persist($produitPanier);
        }

        $em->flush();
        return $this->redirectToRoute('app_cart_index');
    }

    // button remove product
    #[Route('/enlever/{slug}', name: 'remove')]
    public function remove(string $slug, ProduitRepository $produitRepo, EntityManagerInterface $em, PanierRepository $panierRepo): Response
    {
        $user = $this->getUser();
        $produit = $produitRepo->findOneBy([
            'slug' => $slug
        ]);
        // dd($produit);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé!');
        }

        $produitPanier = $panierRepo->findOneBy([
            'user' => $user,
        ]);

        // if there are no products in the cart or if there is no specific product in the cart
        if (!$produitPanier || !$produitPanier->getProduit()->contains($produit)) {
            return $this->redirectToRoute('app_cart_index');
        }
        
        if ($produitPanier->getQuantite() > 1) {
            $produitPanier->setQuantite($produitPanier->getQuantite() - 1);
        } else {
            $produitPanier->removeProduit($produit);
        }
        $em->persist($produitPanier);
        $em->flush();

        return $this->redirectToRoute('app_cart_index');
    }

    // delete button 
    #[Route('/delete/{slug}', name: 'delete')]
    public function delete(string $slug, ProduitRepository $produitRepo, PanierRepository $panierRepo, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $produit = $produitRepo->findOneBy([
            'slug' => $slug
        ]);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé!');
        }

        $produitPanier = $panierRepo->findOneBy([
            'user' => $user,
        ]);

        if ($produitPanier && $produitPanier->getProduit()->contains($produit)) {
            $produitPanier->removeProduit($produit);
        }

        if ($produitPanier->getProduit()->isEmpty()) {
            $em->remove($produitPanier);
        } else {
            $em->persist($produitPanier);
        }
        $em->flush();


        return $this->redirectToRoute('app_cart_index');
    }
}
