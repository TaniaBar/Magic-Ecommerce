<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
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
                ->setLabel('Utilisateur')
                // it show the user's surname and not his id
                ->setCrudController(UserCrudController::class),
            TextField::new('reference')
                ->setLabel('Numéro de commande'),
            DateTimeField::new('cree_le')
                ->setLabel('Créé le'),
            ChoiceField::new('statut')
                ->setChoices([
                    'Commandé' => 'COMMANDÉ',
                    'En préparation' => 'EN_PREPARATION',
                    'Envoyé' => 'ENVOYÉ',
                    'Remboursé' => 'REMBOURSÉ'
                ])
                ->renderAsBadges(),
            MoneyField::new('total_prix')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),
        ];
    }
    
}
