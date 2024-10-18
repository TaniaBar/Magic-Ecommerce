<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $produitAvecRemise = $em->getRepository(Produit::class)->applyDiscount();

        return $this->render('home/index.html.twig', [
            'produits' => $produitAvecRemise,
        ]);
    }
}
