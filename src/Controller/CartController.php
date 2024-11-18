<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierProduit;
use App\Repository\PanierProduitRepository;
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
    public function index(PanierRepository $panierRepo): Response
    {
        // Only those with the role "ROLE_USER" can access to this page
        $this->denyAccessUnlessGranted('ROLE_USER');

        // we retrieve the logged-in user
        $user = $this->getUser();

        // We are looking for user cart
        $panier = $panierRepo->findOneBy(['user' => $user]);

        $data = [];
        $total = 0;

        //  if cart exist
        if ($panier) {
            foreach($panier->getPanierProduits() as $produitPanier) {
                $produit = $produitPanier->getProduit();

                if ($produit) {
                    $prix = $produitPanier->getPrix();
                    $quantite = $produitPanier->getQuantite();
                    $data[] = [
                        'produit' => $produit,
                        'quantite' => $quantite,
                        'prix' => round($prix, 2),
                    ];
                    $total += $prix * $quantite;
                }
             }
        }

        return $this->render('cart/index.html.twig', [
            'data' => $data,
            'total' => $total,
        ]);
    }

    // buttons add product
    #[Route('/ajout/{slug}', name: 'add')]
    public function add(string $slug, EntityManagerInterface $em, ProduitRepository $produitRepo, PanierRepository $panierRepo, PanierProduitRepository $panierProduitRepo): Response
    {
        // Check if the user is authenticated
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            // if not Redirects the user to the login page
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        // we search the product in the repository based on slug
        $produit = $produitRepo->findOneBy(['slug' => $slug ]);
        // dd($produit);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé!');
        }

        // We search for the user’s cart, or create one if it does not exist
        $panier = $panierRepo->findOneBy(['user' => $user]);

        if (!$panier) {
            $panier = new Panier();
            $panier->setUser($user);
            $panier->setCreeLe(new \DateTimeImmutable());
            $em->persist($panier);
        }

        // We are looking for if the product is already present in PanierProduit
        $produitPanier = $panierProduitRepo->findOneBy([
            'panier' => $panier, 
            'produit' => $produit
        ]);

        // if yes, we increase the quantity +1
        if ($produitPanier) {
            $produitPanier->setQuantite($produitPanier->getQuantite() + 1);
            // If not, we create the element
        } else {
            $produitPanier = new PanierProduit();
            $produitPanier->setPanier($panier);
            $produitPanier->setProduit($produit);
            $produitPanier->setQuantite(1);
            
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
        $produit = $produitRepo->findOneBy(['slug' => $slug]);
        // dd($produit);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé!');
        }

        $panier = $panierRepo->findOneBy(['user' => $user]);

        if (!$panier) {
            return $this->redirectToRoute('app_cart_index');
        }

        $panierProduit = $em->getRepository(PanierProduit::class)->findOneBy([
            'panier' => $panier,
            'produit' => $produit
        ]);

        if (!$panierProduit) {
            return $this->redirectToRoute('app_cart_index');
        }

        // if the quantity is greater than one, we make -1
        if ($panierProduit->getQuantite() > 1) {
            $panierProduit->setQuantite($panierProduit->getQuantite() - 1);
            // if the quantity is 1, we remove the product from the cart
        } else {
            $panier->removePanierProduit($panierProduit);
            $em->remove($panierProduit);
        }

        $em->flush();

        return $this->redirectToRoute('app_cart_index');
    }

    // delete button 
    #[Route('/delete/{slug}', name: 'delete')]
    public function delete(string $slug, ProduitRepository $produitRepo, PanierRepository $panierRepo, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $produit = $produitRepo->findOneBy(['slug' => $slug]);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé!');
        }

        $panier = $panierRepo->findOneBy(['user' => $user]);

        if (!$panier) {
            return $this->redirectToRoute('app_cart_index');
        }

        $panierProduit = $em->getRepository(PanierProduit::class)->findOneBy([
            'panier' => $panier,
            'produit' => $produit
        ]);

        if (!$panierProduit) {
            return $this->redirectToRoute('app_cart_index');
        }

        // we remove the product from the cart
        $panier->removePanierProduit($panierProduit);
        $em->remove($panierProduit);

        // if the cart is empty, we delete the cart
        if ($panier->getPanierProduits()->isEmpty()) {
            $em->remove($panier);
        } else {
            $em->persist($panier);
        }

        $em->flush();

        return $this->redirectToRoute('app_cart_index');
    }
}
