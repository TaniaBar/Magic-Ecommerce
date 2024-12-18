<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/produit', name: 'app_produit_')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        // we search for products in ascending price order
        $produits = $em->getRepository(Produit::class)->findAllByPriceAsc();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(string $slug, EntityManagerInterface $em): Response
    {
        $produit = $em->getRepository(Produit::class)->findOneBy(['slug' => $slug]);
        // dd($produit);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        return $this->render('produit/details.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/categorie/{categorie}', name: 'find_category')]
    public function findCategory(CategorieRepository $categorieRepository, ProduitRepository $produitRepository, string $categorie): Response
    {
        // we search for products by category
        $categoryEntity = $categorieRepository->findOneBy(['nom' => $categorie]);

        if (!$categoryEntity) {
            throw $this->createNotFoundException('Catégorie de produit introuvable');
        }

        $produits = $produitRepository->findBy(['categorie' => $categoryEntity]);

        return $this->render('produit/categorie.html.twig', [
            'produits' => $produits,
            'categorie'=> ucfirst($categorie),
        ]);
    }

}
