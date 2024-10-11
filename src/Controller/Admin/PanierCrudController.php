<?php

namespace App\Controller\Admin;

use App\Entity\Panier;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PanierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Panier::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('quantite'),
            MoneyField::new('total_prix')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),
            AssociationField::new('user'),
            DateTimeField::new('cree_le')
        ];
    }
    
}
