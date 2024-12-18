<?php

namespace App\Controller\Admin;

use App\Entity\Entreprise;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EntrepriseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Entreprise::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom_entreprise'),
            TextField::new('slug'),
            TextField::new('adresse_entreprise'),
            EmailField::new('email_entreprise'),
            TextField::new('img_entreprise')
                ->setLabel('Image Entreprise'),
            AssociationField::new('user')
                ->setLabel('Utilisateur'),
            DateTimeField::new('cree_le')
                ->setLabel('Créé le') 
        ];
    }
    
}
