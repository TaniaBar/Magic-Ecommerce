<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserModifProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'E-mail',
            'attr' => [
                'placeholder' => 'Entrez votre e-mail',
            ],
        ])
            ->add('prenom', TextType::class, [    
            'attr' => [
                'placeholder' => 'Entrez votre prénom',
            ],
        ])
        ->add('nom', TextType::class, [
            'attr' => [
                'placeholder' => 'Entrez votre nom',
            ],
        ])
        ->add('adresse', TextType::class, [
            'attr' => [
                'placeholder' => 'Entrez votre adresse',
            ],
        ])
        ->add('code_postal', TextType::class, [
            'attr' => [
                'placeholder' => 'Entrez votre code postal',
            ],
        ])
        ->add('ville', TextType::class, [
            'attr' => [
                'placeholder' => 'Entrez votre ville',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data-class' => User::class,
        ]);
    }
}
