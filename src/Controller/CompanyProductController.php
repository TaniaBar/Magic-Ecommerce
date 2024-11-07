<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/entreprise/profil/produit', name: 'app_company_product_')]
class CompanyProductController extends AbstractController
{
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

        $entreprises = $user->getEntreprises();
        $entreprises->initialize();
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
}
