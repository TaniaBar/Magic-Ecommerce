<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\User;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/entreprise/gestion-des-commandes', name: 'app_order_management_')]
class OrderManagementController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'index')]
    public function index(CommandeRepository $commandeRepo, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        $user = $this->security->getUser();
        // dd($user);
        if (!$user instanceof User) {
            throw new \LogicException('L\'utilisateur n\'est pas valide.');
        }
        $entreprise = $user->getEntreprises()->first();

        if (!$entreprise) {
            throw new \LogicException('Entreprise non trouvé');
        }

        $commandes = $commandeRepo->findByEntreprise($entreprise);

        return $this->render('order_management/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }
}
