<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyModifProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email_entreprise', EmailType::class, [
                'label' => 'E-mail entreprise',
                'attr' => [
                    'placeholder' => 'Entrez le mail de l\'entreprise',
                ],
            ])
            ->add('nom_entreprise', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le nom de l\'entreprise',
                ],
            ])
            ->add('adresse_entreprise', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez  l\'adresse de l\'entreprise',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data-class' => Entreprise::class
        ]);
    }
}
