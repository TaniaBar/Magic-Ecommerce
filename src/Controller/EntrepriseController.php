<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/entreprise', name: 'app_entreprise_')]
class EntrepriseController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $entreprises = $em->getRepository(Entreprise::class)->findAll();
        // dd($entreprises);

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    #[Route('/{slug}/produits', name: 'produits')]
    public function entrepriseProduits($slug, ProduitRepository $produitRepository, EntityManagerInterface $em): Response
    {
        $entreprise = $em->getRepository(Entreprise::class)->findOneBy(['slug' => $slug]);

        if (!$entreprise) {
            throw $this->createNotFoundException('Entreprise introuvable');
        }
        // dd($entreprise);
        $entrepriseProduits = $produitRepository->filterByCompany($entreprise);
        // dd($entrepriseProduits);
        if(!$entrepriseProduits) {
            throw $this->createNotFoundException('Produits introuvables pour cette entreprise!');
        }
        

        return $this->render('entreprise/produits.html.twig', [
            'produits' => $entrepriseProduits,
            'entreprise' => $entreprise,
        ]);
    }
}
