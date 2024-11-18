<?php

namespace App\Controller\Admin;

use App\Entity\PanierProduit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PanierProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PanierProduit::class;
    }

    
    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         AssociationField::new('panier_id')
    //             ->setLabel(''),
    //         AssociationField::new('produit_id'),
    //         TextField::new('quantite'),
    //         MoneyField::new('prix')
    //     ];
    // }
    
}
