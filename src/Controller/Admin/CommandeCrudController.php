<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user')
            // it show the user's name and not the id
            ->formatValue(function ($value, $entity) {
                return $entity->getUser()->getLastname();
            }),
        TextField::new('reference')
            ->setLabel('Numéro de commande'),
        DateTimeField::new('cree_le')
            ->setLabel('Créé le'),
        ChoiceField::new('statut')
            ->setChoices([
                'Commandé' => 'COMMANDÉ',
                'En cours de livraison' => 'EN COURS DE LIVRAISON',
                'Livré' => 'LIVRÉ',
                'Remboursé' => 'REMBOURSÉ'
            ])
            ->renderAsBadges(),
        MoneyField::new('total_prix')
            ->setCurrency('EUR')
            ->setStoredAsCents(false),
        ];
    }
    
}
