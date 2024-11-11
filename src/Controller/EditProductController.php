<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\User;
use App\Form\EditProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/entreprise/profil/modifier-produit', name: 'app_edit_product_')]
class EditProductController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/{slug}', name: 'index')]
    public function index(string $slug, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        $user= $this->security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('L\'utilisateur n\'est pas valide.');
        }

        $produit = $em->getRepository(Produit::class)->findOneBy([
            'slug' => $slug,
            'entreprise' => $user->getEntreprises()->first(),
        ]);

        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $form = $this->createForm(EditProductType::class, $produit, [
            'validation_groups' => ['Default'],
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

            $this->addFlash('success', 'Produit modifié avec succès');
            return $this->redirectToRoute('app_company_product_index');
        }

        return $this->render('edit_product/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
