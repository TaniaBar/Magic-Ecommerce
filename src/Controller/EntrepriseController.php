<?php

namespace App\Controller;

use App\Entity\Entreprise;
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
}
