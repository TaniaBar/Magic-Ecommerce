<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user/profile', name: 'app_user_profile_')]
class UserProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $commandes_recentes = $em->getRepository(Commande::class)->findBy(['user' => $user]);

        $reference = null;
        $cree_le = null;

        if(!empty($commandes_recentes)) {
            $premiereCommande = $commandes_recentes[0];
            $reference = $premiereCommande->getReference();
            $cree_le = $premiereCommande->getCreeLe();
            // dd($premiereCommande);
        }

        return $this->render('user_profile/index.html.twig', [
            'user' => $user,
            'commandes_recentes' => $commandes_recentes,
            'reference' => $reference,
            'cree_le' => $cree_le,
        ]);
    }
}
