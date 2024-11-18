<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/entreprise/profil/produit', name: 'app_company_product_')]
class CompanyProductController extends AbstractController
{
    // Initializes the security service to manage the current user and access controls
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');
        
        $user = $this->security->getUser();
        // dd($user);
        if (!$user instanceof User) {
            throw new \LogicException('L\'utilisateur n\'est pas valide.');
        }

        $entreprises = $user->getEntreprises();
        // dd($entreprises);

        if ($entreprises->isEmpty()) {
            throw $this->createNotFoundException('Aucune entreprise trouvée pour cet utilisateur');
        }

        $entreprise = $entreprises->first();

        $produits = $em->getRepository(Produit::class)->filterByCompany($entreprise);
        // dd($produits);

        return $this->render('company_product/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    // button delete product in company area
    #[Route('/supprimer/{slug}', name: 'delete')]
    public function delete(string $slug, ProduitRepository $produitRepo, EntityManagerInterface $em): Response
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('L\'utilisateur n\'est pas valide.');
        }

        $produit = $produitRepo->findOneBy([
            'slug' => $slug,
            'entreprise' => $user->getEntreprises()->first(),
        ]);

        if (!$produit) {
            $this->addFlash('error', 'Produit non trouvé');
        }

        $em->remove($produit);
        $em->flush();
        
        $this->addFlash('success', 'Produit supprimé avec succès');

        return $this->redirectToRoute('app_company_product_index');
    }
}
