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

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            
            $role = $form->get('roles')->getData();
            $user->setRoles([$role]);

            // Controlla se l'utente ha selezionato il ruolo di impresa
        if ($role === 'ROLE_ENTREPRISE') {
            // Crea un nuovo oggetto Entreprise e imposta i dati
            $entreprise = new Entreprise();
            $entreprise->setNomEntreprise($form->get('nom_entreprise')->getData());
            $entreprise->setAdresseEntreprise($form->get('adresse_entreprise')->getData());
            $entreprise->setEmailEntreprise($form->get('email_entreprise')->getData());

            // Persisti l'oggetto Entreprise
            $entityManager->persist($entreprise);
            // Associa l'impresa all'utente, se necessario (a seconda della tua logica)
            $user->addEntreprise($entreprise); // Assicurati di avere un metodo setEntreprise nell'oggetto User
        }

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $security->login($user, UserAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
