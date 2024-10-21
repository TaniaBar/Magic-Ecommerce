<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Produit;
use App\Form\ContactType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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

    #[Route('/{slug}/produits', name: 'produits')]
    public function entrepriseProduits($slug, ProduitRepository $produitRepository, EntityManagerInterface $em): Response
    {
        $entreprise = $em->getRepository(Entreprise::class)->findOneBy(['slug' => $slug]);

        if (!$entreprise) {
            throw $this->createNotFoundException('Entreprise introuvable');
        }
        // dd($entreprise);
        $entrepriseProduits = $produitRepository->filterByCompany($entreprise);
        // dd($entrepriseProduits);
        if(!$entrepriseProduits) {
            throw $this->createNotFoundException('Produits introuvables pour cette entreprise!');
        }
        

        return $this->render('entreprise/produits.html.twig', [
            'produits' => $entrepriseProduits,
            'entreprise' => $entreprise,
        ]);
    }

    #[Route('/{slug}/contacter', name: 'contact')]
    public function contacterEntreprise(string $slug, Request $request, MailerInterface $mailer, EntityManagerInterface $em): Response
    {
        $entreprise = $em->getRepository(Entreprise::class)->findOneBy(['slug' => $slug]);

        if(!$entreprise) {
            throw $this->createNotFoundException('Entreprise introuvable!');
        }
        
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
            ->from($data['email'])
            ->to($entreprise->getEmailEntreprise())
            ->subject('Nouveau message')
            ->text($data['message']);

            $mailer->send($email);

            $this->addFlash('success', 'Ton message à bien été envoyé!');

            return $this->redirectToRoute('app_entreprise_index');
        }

        return $this->render('entreprise/contact.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ]);

        

    }
}
