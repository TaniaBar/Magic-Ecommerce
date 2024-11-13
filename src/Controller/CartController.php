<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier', name: 'app_cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProduitRepository $produitRepo, PanierRepository $panierRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();

        $produitsPanier = $panierRepo->findBy(['user' => $user]);
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

    #[Route('/ajout/{slug}', name: 'add')]
    public function add(string $slug, EntityManagerInterface $em, ProduitRepository $produitRepo): Response
    {
        $user = $this->getUser();
        $produit = $produitRepo->findOneBy(['slug' => $slug]);
        // dd($produit);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé!');
        }

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
}
