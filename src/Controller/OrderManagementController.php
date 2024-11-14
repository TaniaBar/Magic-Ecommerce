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

    #[Route('/mis-a-jour-statut/{id}', name: 'update_status', methods: ['POST'])]
    public function updateStatus(int $id, Request $request, CommandeRepository $commandeRepo, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        // Recupera l'ordine in base all'ID
        $commande = $commandeRepo->find($id);

        if (!$commande) {
            throw $this->createNotFoundException('Commande non trouvée.');
        }

        // Aggiorna lo stato dell'ordine in base al valore inviato dal modulo
        $newStatus = $request->request->get('statut');
        if (in_array($newStatus, ['COMMANDÉ', 'EN_PREPARATION', 'ENVOYÉ', 'REMBOURSÉ'])) {
            $commande->setStatut($newStatus);
            $em->flush();
        }

        return $this->redirectToRoute('app_order_management_index');
    }
}
