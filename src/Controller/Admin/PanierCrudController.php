<?php

namespace App\Controller\Admin;

use App\Entity\Panier;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class PanierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Panier::class;
    }

    
    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         AssociationField::new('user')
    //             ->setLabel('Utilisateur'),
    //         DateTimeField::new('cree_le')
    //             ->setLabel('Créé le')
    //     ];
    // }
    
}
