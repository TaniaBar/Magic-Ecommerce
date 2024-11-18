<?php

namespace App\Controller\Admin;

use App\Entity\CommandeDetail;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommandeDetailCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CommandeDetail::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('quantite')
                ->setLabel('Quantité'),
            MoneyField::new('prix')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),
            AssociationField::new('commande'),
            AssociationField::new('produit'),
            AssociationField::new('entreprise'),
        ];
    }
    
}
