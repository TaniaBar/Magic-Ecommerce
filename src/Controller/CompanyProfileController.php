<?php

namespace App\Controller;

use App\Entity\Entreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/entreprise/profil', name: 'app_company_profile_')]
class CompanyProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isGranted('ROLE_ENTREPRISE')) {
            $entreprise = $em->getRepository(Entreprise::class)->findOneBy(['user' => $user]);

            if (!$entreprise) {
                $this->addFlash('warning', 'Aucune entreprise associée à cet utilisateur');
            }
    
            return $this->render('company_profile/index.html.twig', [
                'user' => $user,
                'entreprise' => $entreprise,
            ]);
        }

        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_user_profile_index');
        }

        $this->addFlash('error', 'Accès non autorisé');
        return $this->redirectToRoute('app_home');
    }
}
