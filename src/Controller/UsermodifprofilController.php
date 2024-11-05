<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserModifProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user/profile/edit', name: 'app_profile_edit_')]
class UsermodifprofilController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // dd('Controller is executed');

        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        // dd('User :', $user); 

        if(!$user instanceof User) {
            throw new \LogicException('User not found');
        }

        $form = $this->createForm(UserModifProfilType::class, $user, [
            'validation_groups' => ['Default'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès');

            return $this->redirectToRoute('app_user_profile_index');
        }

        return $this->render('usermodifprofil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
