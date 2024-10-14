<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        // $roles = $user->getRoles();

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            if ($form->get('roles')->getData() === 'ROLE_ENTREPRISE') {
                $user->setRoles(['ROLE_ENTREPRISE']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            if ($form->get('roles')->getData() === 'ROLE_ENTREPRISE') {
                $user->setRoles(['ROLE_ENTREPRISE']);

                $entreprise = new Entreprise();
                $entreprise->setNom($form->get('nom_entreprise')->getData());
                $entreprise->setAdresse($form->get('adresse_entreprise')->getData());
                $entreprise->setEmail($form->get('email_entreprise')->getData());
                $entreprise->setUser($user);

                $entityManager->persist($entreprise);
            }
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $security->login($user, UserAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
