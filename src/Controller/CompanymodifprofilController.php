<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\User;
use App\Form\CompanyModifProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/entreprise/profil/edit', name: 'app_company_profile_edit_')]
class CompanymodifprofilController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        $user = $this->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('L\'utilisateur n\'est pas valide.');
        }

        $entreprises = $user->getEntreprises();
        // dd('User :', $user); 

        if ($entreprises->isEmpty()) {
            $this->addFlash('warning', 'Aucune entreprise associée à cet utilisateur.');
            return $this->redirectToRoute('app_home'); // O una rotta adeguata
        }

        // Se c'è solo un'azienda, prendi quella (altrimenti potrebbe essere necessario gestire più aziende)
        $entreprise = $entreprises->first();

        $form = $this->createForm(CompanyModifProfilType::class, $entreprise, [
            'validation_groups' => ['Default'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success','Votre profil a été mis à jour avec succès');

            return $this->redirectToRoute('app_company_profile_index');
        }

        return $this->render('companymodifprofil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
