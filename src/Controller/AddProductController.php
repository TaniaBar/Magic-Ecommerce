<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\User;
use App\Form\AddProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/entreprise/profil/ajout-produit', name: 'app_add_product_')]
class AddProductController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    #[Route('/', name: 'index')]
    public function index(Request $request, Security $security, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        $user = $this->security->getUser();
        // dd($user);

        if (!$user instanceof User) {
            throw new \LogicException('L\'utilisateur n\'est pas valide.');
        }

        $produit = new Produit();
        $produit->setEntreprise($user->getEntreprises()->first());

        $form = $this->createForm(AddProductType::class, $produit, [
            'user' => $user
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nom = $produit->getNom() ?? '';
            if (!empty($nom)) {
                $slug = $slugger->slug($nom)->lower();
                $produit->setSlug($slug);
            }

            $em->persist($produit);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté!');
            return $this->redirectToRoute('app_company_product_index');
        }

        return $this->render('add_product/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
