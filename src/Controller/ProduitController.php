<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produit', name: 'app_produit_')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $produits = $em->getRepository(Produit::class)->findAllByPriceAsc();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/categorie/{categorie}', name: 'find_category')]
    public function findCategory(CategorieRepository $categorieRepository, ProduitRepository $produitRepository, string $categorie): Response
    {
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
