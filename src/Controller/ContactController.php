<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/contact', name: 'app_contact_')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        // I call the ContactType form
        $form = $this->createForm(ContactType::class);
        // when I process a form, I can pass the Request object to handleRequest()
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // dd($data);

            // I collecte the data entered by the user
            $address = $data['email'];
            $content = $data['content'];

            $email = (new Email())
            ->from($address)
            ->to('admin@admin.com')
            ->subject('Demande de contact')
            ->text($content);

            // I send the email with the data to Mailtrap
            $mailer->send($email);

            // I show a flash message 
            $this->addFlash('success', 'Votre message à été envoyé avec succès!');

            // Redirection to contact page 
            return $this->redirectToRoute('app_contact');

        }
        
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'formulaire' => $form,
        ]);
    }
}
